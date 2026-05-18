<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Cart;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Trang đặt bàn cho khách
     */
    public function index(Request $request)
    {
        $menus  = Menu::all();
        $tables = Table::all();

        $busyTableIds = Reservation::whereIn('status', ['pending', 'confirmed', 'serving', 'served'])
            ->pluck('table_id')->toArray();

        $cleaningTableIds = Reservation::where('status', 'cleaning')
            ->where('cleanup_started_at', '>', now()->subMinutes(1))
            ->pluck('table_id')->toArray();

        $bookedTableIds = array_unique(array_merge($busyTableIds, $cleaningTableIds));

        // Dọn dẹp bàn đã hết thời gian dọn
        Reservation::where('status', 'cleaning')
            ->where('cleanup_started_at', '<=', now()->subMinutes(1))
            ->delete();

        return view('reservation', compact('menus', 'tables', 'bookedTableIds'));
    }

    /**
     * Lưu đặt bàn từ khách hàng
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name'        => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'table_id'         => 'required|integer',
        ]);

        $dbCart     = Cart::with('menu')->where('user_id', auth()->id())->get();
        $cartData   = [];
        $totalAmount = 0;

       foreach ($dbCart as $item) {
            // 🔥 CHỐT CHẶN AN TOÀN: Chỉ lấy món có tồn tại và số lượng lớn hơn 0
            if ($item->menu && $item->quantity > 0) {
                $subTotal     = $item->menu->price * $item->quantity;
                $totalAmount += $subTotal;
                $cartData[]   = [
                    'id'       => $item->menu_id,
                    'name'     => $item->menu->name,
                    'price'    => $item->menu->price,
                    'quantity' => $item->quantity,
                    'image'    => $item->menu->image,
                ];
            }
        }

        $reservation                     = new Reservation();
        $reservation->user_id            = auth()->id();
        $reservation->full_name          = $request->full_name;
        $reservation->email              = auth()->user()->email;
        $reservation->phone              = $request->phone;
        $reservation->reservation_date   = $request->reservation_date;
        $reservation->reservation_time   = $request->reservation_time;
        $reservation->table_id           = $request->table_id;
        $reservation->notes              = $request->notes;
        $reservation->status             = 'pending';
        $reservation->payment_status     = 'unpaid';
        $reservation->total_price        = $totalAmount;
        $reservation->cart_data          = $cartData;
        $reservation->save(); // Lưu đơn đặt bàn trước để lấy ID

        // LƯU CHI TIẾT MÓN ĂN VÀO BẢNG ORDER_ITEMS
        if (count($cartData) > 0) {
            foreach ($cartData as $item) {
                \App\Models\OrderItem::create([
                    'reservation_id' => $reservation->id, // Gắn đúng ID đơn đặt bàn vào đây
                    'order_id'       => null,             // Đơn tại bàn nên order_id để null
                    'menu_id'        => $item['id'],
                    'product_name'   => $item['name'],
                    'quantity'       => $item['quantity'],
                    'price'          => $item['price'],
                ]);
            }
        }

        // Cập nhật trạng thái bàn
        \App\Models\Table::where('id', $request->table_id)->update(['status' => 'reserved']);

        try {
            $reservation->refresh()->load('table');
            Mail::to($reservation->email)->send(new BookingConfirmation($reservation));
        } catch (\Exception $e) {
            \Log::error('MAIL ERROR: ' . $e->getMessage());
        }

        return redirect()->route('cart.index')->with('success', 'Ghi nhận thông tin đặt bàn thành công!');
    }

    /**
     * Thêm món vào giỏ qua AJAX
     */
    public function addToCartAjax(Request $request)
    {
        $id       = $request->menu_id ?? $request->food_id;
        $quantity = (int) ($request->quantity ?? 1);
        $isUpdate = $request->is_update ?? false;

        $menu = Menu::find($id);
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Món không tồn tại!']);
        }

        $dbCartItem = Cart::where('user_id', auth()->id())->where('menu_id', $id)->first();

        // 🔥 ĐOẠN FIX QUAN TRỌNG:
        // Nếu khách bấm giảm số lượng về <= 0, ta tiến hành XÓA món đó khỏi Database
        if ($isUpdate && $quantity <= 0) {
            if ($dbCartItem) {
                $dbCartItem->delete();
            }
        } 
        // Ngược lại, nếu > 0 thì mới thêm hoặc cập nhật
        else {
            if ($dbCartItem) {
                $dbCartItem->quantity = $isUpdate ? $quantity : $dbCartItem->quantity + 1;
                $dbCartItem->save();
            } else {
                Cart::create(['user_id' => auth()->id(), 'menu_id' => $id, 'quantity' => $quantity]);
            }
        }

        // Lấy lại giỏ hàng mới nhất để trả về giao diện
        $dbCart = Cart::with('menu')->where('user_id', auth()->id())->get();
        
        // Chỉ map những món CÓ số lượng > 0 trả về JS
        $cart   = $dbCart->filter(fn($i) => $i->menu && $i->quantity > 0)->map(fn($i) => [
            'id'       => $i->menu_id,
            'name'     => $i->menu->name,
            'price'    => $i->menu->price,
            'quantity' => $i->quantity,
            'image'    => $i->menu->image,
        ])->values()->toArray();

        return response()->json(['success' => true, 'cartCount' => count($cart), 'cartData' => $cart]);
    }
    public function saveNoteAjax(Request $request)
    {
        $reservation          = session()->get('reservation', []);
        $reservation['notes'] = $request->notes;
        session()->put('reservation', $reservation);
        return response()->json(['success' => true]);
    }

    public function autoSave(Request $request)
    {
        return response()->json(['success' => true]);
    }

    /**
     * Danh sách đặt bàn — FIX: dùng role_id thay vì tên role
     * Dùng chung cho Admin (role_id=2) và Staff (role_id=3)
     */
    public function adminIndex()
    {
        $roleId       = (int) auth()->user()->role_id;
        $reservations = Reservation::with(['user', 'table'])->latest()->get();

        // Staff (3) hoặc Admin vào route /staff/reservations
        if ($roleId === 3 || ($roleId === 2 && request()->is('staff/*'))) {
            return view('staff.reservations', compact('reservations'));
        }

        // Admin (2) vào route /admin/reservations
        if ($roleId === 2) {
            return view('admin.reservations.index', compact('reservations'));
        }

        return redirect('/')->with('error', 'Bạn không có quyền truy cập.');
    }

    /**
     * Cập nhật trạng thái đặt bàn (Admin & Staff)
     * Luồng trạng thái:
     *   pending → confirmed → arrived → serving → ready → served → completed
     *   Thanh toán: paid_cash / paid_transfer → payment_status = 'paid'
     */
  /**
     * Cập nhật trạng thái đặt bàn (Admin & Staff & Kitchen)
     */
    public function updateStatus(Request $request, $id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        $newStatus = $request->input('status');

        // Đọc mảng chef_statuses hiện tại
        $chefStatuses = $reservation->chef_statuses;
        if (!is_array($chefStatuses)) {
            $chefStatuses = is_string($chefStatuses) ? json_decode($chefStatuses, true) : [];
        }

        // Đọc mảng cart_data hành động ép kiểu mảng để chống lỗi JSON String
        $cartData = $reservation->cart_data;
        if (is_string($cartData)) {
            $cartData = json_decode($cartData, true);
        }

        // 🔥 XỬ LÝ ĐỒNG BỘ: Duyệt toàn bộ món ăn tại bàn để đổi trạng thái chi tiết
        if (is_array($cartData) && count($cartData) > 0) {
            foreach ($cartData as $item) {
                // Đảm bảo lấy đúng khóa ID (chống trường hợp mảng bị lệch cấu trúc)
                $itemId = $item['id'] ?? null;
                if (!$itemId) continue;

                if ($newStatus === 'serving') {
                    // Khi bếp bấm "TIẾP NHẬN & BẮT ĐẦU NẤU"
                    // Chuyển những món nào đang 'pending' sang 'cooking'
                    if (($chefStatuses[$itemId] ?? 'pending') === 'pending') {
                        $chefStatuses[$itemId] = 'cooking';
                    }
                } elseif ($newStatus === 'served') {
                    // Khi bếp bấm "ĐÃ XONG & CHỜ LÊN MÓN"
                    // Bắt buộc chuyển toàn bộ tất cả món ăn tại bàn sang 'ready'
                    $chefStatuses[$itemId] = 'ready'; 
                }
            }
        }

        // 2. Tiến hành cập nhật Database cho đơn đặt bàn
        $reservation->status = $newStatus;
        $reservation->chef_statuses = $chefStatuses;
        $reservation->save();

        // 3. ĐỒNG BỘ SANG HÓA ĐƠN (ORDER) VÀ MÀN HÌNH KHÁCH
        $order = \App\Models\Order::where('table_number', $reservation->table_id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                    
        if ($order) {
            $orderUpdateData = ['status' => $newStatus];
            
            // Đồng bộ mảng chef_statuses sang bảng orders nếu có cột
            if (\Schema::hasColumn('orders', 'chef_statuses')) {
                $orderUpdateData['chef_statuses'] = $chefStatuses;
            }
            $order->update($orderUpdateData);
        }

        // 4. GIẢI PHÓNG BÀN KHI HOÀN TẤT
        if ($newStatus === 'completed') {
            $table = \App\Models\Table::find($reservation->table_id);
            if ($table) {
                $table->update([
                    'status' => 'cleaning',
                    'cleanup_started_at' => now()
                ]); 
            }
        }

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái và đồng bộ dữ liệu món ăn!');
    }

    // -------------------------------------------------------
    // Các hàm phụ (Staff / Admin tạo đặt bàn trực tiếp)
    // -------------------------------------------------------

    public function staffCreate()
    {
        $menus  = Menu::all();
        $tables = Table::all();
        return view('admin.reservations.create', compact('menus', 'tables'));
    }

    public function staffStore(Request $request)
    {
        // 1. Kiểm tra xem có phải khách đến trực tiếp (Walk-in) hay không
        $isWalkIn = $request->has('is_walk_in');

        // 2. Validate dữ liệu linh hoạt
        $rules = [
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'table_id'         => 'required|integer',
        ];

        // Chỉ bắt buộc tên và SĐT nếu KHÔNG phải khách Walk-in
        if (!$isWalkIn) {
            $rules['full_name'] = 'required|string|max:255';
            $rules['phone']     = 'required|string|max:20';
            $rules['reservation_date'] .= '|after_or_equal:today';
        }

        $request->validate($rules);

        // 3. Xử lý mảng món ăn nhân viên gọi trực tiếp từ form
        $cartData = [];
        $totalAmount = 0;

        if ($request->has('foods')) {
            foreach ($request->foods as $foodId => $details) {
                $menu = \App\Models\Menu::find($foodId);
                if ($menu) {
                    $qty = (int) $details['quantity'];
                    $subTotal = $menu->price * $qty;
                    $totalAmount += $subTotal;
                    
                    $cartData[] = [
                        'id'       => $menu->id,
                        'name'     => $menu->name,
                        'price'    => $menu->price,
                        'quantity' => $qty,
                        'image'    => $menu->image,
                    ];
                }
            }
        }

        // 4. Lưu dữ liệu Đặt bàn (Reservation)
        $reservation                   = new \App\Models\Reservation();
        $reservation->user_id          = auth()->id(); // ID của nhân viên tạo đơn
        $reservation->full_name        = $request->full_name ?? 'Khách vãng lai';
        $reservation->email            = auth()->user()->email ?? null;
        $reservation->phone            = $request->phone ?? null;
        $reservation->reservation_date = $request->reservation_date;
        $reservation->reservation_time = $request->reservation_time;
        $reservation->table_id         = $request->table_id;
        $reservation->notes            = $request->notes;
        
        // Trạng thái: Walk-in thì 'serving' (đang phục vụ), Đặt trước thì 'confirmed'
        $reservation->status = $isWalkIn ? 'arrived' : 'confirmed'; 
        $reservation->payment_status   = 'unpaid';
        $reservation->total_price      = $totalAmount;
        $reservation->cart_data        = $cartData; 
        $reservation->save();

        // 5. Cập nhật trạng thái Bàn
        $tableStatus = $isWalkIn ? 'occupied' : 'reserved';
        \App\Models\Table::where('id', $request->table_id)->update(['status' => $tableStatus]);

        // =========================================================
        // 6. TỰ ĐỘNG TẠO HÓA ĐƠN (ORDER) NẾU CÓ GỌI MÓN (Dành cho Walk-in)
        // =========================================================
        if ($isWalkIn && count($cartData) > 0) {
            // Tạo Hóa đơn chuyển xuống Bếp & Thu ngân
            $order = \App\Models\Order::create([
                'user_id'      => auth()->id(),
                'table_number' => $request->table_id,
                'total_price'  => $totalAmount,
                'status'       => 'pending', // 'pending' để Bếp biết có món mới cần làm
            ]);

            // Tạo chi tiết các món trong Hóa đơn
            foreach ($cartData as $item) {
                \App\Models\OrderItem::create([
                    'order_id'     => $order->id,
                    'menu_id'      => $item['id'],
                    'product_name' => $item['name'],
                    'quantity'     => $item['quantity'],
                    'price'        => $item['price'],
                ]);
            }
        }

        return redirect()->route('admin.reservations.index')->with('success', 'Đã mở bàn và chuyển Order xuống bếp thành công!');
    }

    public function editItems($id)
    {
        $reservation = Reservation::findOrFail($id);
        $menus       = Menu::all();
        $cartItems   = $reservation->cart_data ?? [];

        return view('staff.reservations.edit-items', compact('reservation', 'menus', 'cartItems'));
    }

    public function updateItems(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $newCartData = [];
        $totalPrice  = 0;

        foreach ($request->items ?? [] as $item) {
            $menu = Menu::find($item['id']);
            if (!$menu) continue;

            $qty          = (int) $item['quantity'];
            $price        = (float) $menu->price;
            $newCartData[] = [
                'id'       => $menu->id,
                'name'     => $menu->name,
                'price'    => $price,
                'quantity' => $qty,
                'image'    => $menu->image,
            ];
            $totalPrice += $price * $qty;
        }

        $reservation->update(['cart_data' => $newCartData, 'total_price' => $totalPrice]);

        // Đồng bộ sang Order
        $order = Order::where('user_id', $reservation->user_id)
                      ->where('table_number', $reservation->table_id)
                      ->latest()->first();

        if ($order) {
            $order->update(['total_price' => $totalPrice]);
            $order->items()->delete();
            foreach ($newCartData as $data) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'menu_id'      => $data['id'],
                    'product_name' => $data['name'],
                    'quantity'     => $data['quantity'],
                    'price'        => $data['price'],
                ]);
            }
        }

        // Redirect thông minh theo role
        $roleId = (int) auth()->user()->role_id;
        if ($roleId === 2) {
            return redirect()->route('admin.reservations.index')->with('success', 'Admin đã cập nhật thực đơn thành công!');
        }

        return redirect()->route('staff.reservations.index')->with('success', 'Đã lưu thay đổi thực đơn!');
    }
  public function create()
    {
        // 1. ĐỒNG BỘ LOGIC DỌN BÀN: 
        // Tìm các bàn đang ở trạng thái 'cleaning' mà đã trôi qua 1 phút (60 giây)
        $expiredTables = \App\Models\Table::where('status', 'cleaning')
            ->where('cleanup_started_at', '<=', now()->subMinutes(1))
            ->get();

        // Tự động chuyển các bàn đó về trạng thái 'empty' (Sẵn sàng)
        foreach ($expiredTables as $table) {
            $table->update([
                'status' => 'empty',
                'cleanup_started_at' => null
            ]);
        }

        // 2. Sau khi đã làm mới trạng thái, mới tiến hành lấy danh sách Bàn và Menu
        $tables = \App\Models\Table::all();
        $menus = \App\Models\Menu::all(); 

        // 3. Gọi đúng file giao diện của Admin/Staff (file vừa được gắn code đếm ngược)
        return view('admin.reservations.create', compact('tables', 'menus'));
    }
}
