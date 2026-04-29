<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CartController extends Controller
{
    /**
     * 1. HIỂN THỊ TRANG GIỎ HÀNG & BILL THANH TOÁN
     */
/**
     * 1. HIỂN THỊ TRANG GIỎ HÀNG & BILL THANH TOÁN
     */
    public function index()
    {
        $userId = auth()->id();
        
        // ✅ DEBUG
        \Log::info('=== CART INDEX DEBUG ===', [
            'user_id' => $userId,
            'user_name' => auth()->user()->name ?? 'No user',
        ]);
        
        // 1. LẤY GIỎ HÀNG TỪ DATABASE
        $dbCart = \App\Models\Cart::with('menu')
                    ->where('user_id', $userId)
                    ->get();

        $cart = $dbCart->map(function($item) {
            return [
                'id'       => $item->menu_id,
                'name'     => $item->menu->name ?? 'Món đã bị xóa',
                'price'    => $item->menu->price ?? 0,
                'quantity' => $item->quantity,
                'image'    => $item->menu->image ?? '',
            ];
        })->toArray();

        // 2. LẤY THÔNG TIN ĐẶT BÀN MỚI NHẤT TỪ DATABASE (Tuyệt đối không sợ mất Session)
        $latestReservation = \App\Models\Reservation::where('user_id', $userId)
                            ->orderBy('created_at', 'desc')
                            ->first();

        // ✅ DEBUG
        \Log::info('=== LATEST RESERVATION ===', [
            'found' => $latestReservation ? 'YES' : 'NO',
            'reservation' => $latestReservation ? [
                'id' => $latestReservation->id,
                'full_name' => $latestReservation->full_name,
                'table_id' => $latestReservation->table_id,
            ] : null
        ]);

        if ($latestReservation) {
            // Lấy tên bàn từ bảng tables (nếu không thấy thì in ra mã ID chữa cháy)
            $tableName = \App\Models\Table::find($latestReservation->table_id)->name ?? ('Số ' . $latestReservation->table_id);
            
            $reservation = [
    'name'      => $latestReservation->full_name,
    'table'     => $tableName,
    'table_id'  => $latestReservation->table_id, // ✅ THÊM DÒNG NÀY
    'date'      => \Carbon\Carbon::parse($latestReservation->reservation_date)->format('d/m/Y'),
    'time'      => \Carbon\Carbon::parse($latestReservation->reservation_time)->format('H:i'),
    'notes'     => $latestReservation->notes ?? 'Không có yêu cầu đặc biệt',
    'status'    => 'Chờ xác nhận'
];
        } else {
            // Khách chưa đặt bàn, chỉ rẽ ngang vào xem Giỏ hàng
            $reservation = [
    'name'      => auth()->check() ? auth()->user()->name : 'Khách hàng',
    'table'     => 'Chưa chọn',
    'table_id'  => null, // ✅ THÊM DÒNG NÀY
    'date'      => '--/--/----',
    'time'      => '--:--',
    'notes'     => 'Chưa có ghi chú',
    'status'    => 'Chưa đặt'
];
        }

        // 3. TÍNH TỔNG TIỀN
        $total = 0;
        foreach($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }

        // Trả dữ liệu ra view
        return view('cart.index', compact('cart', 'total', 'reservation'));
    }

    /**
     * 2. THÊM MÓN VÀO GIỎ HÀNG
     */
    /**
     * 2. THÊM MÓN VÀO GIỎ HÀNG
     */
public function addToCart(Request $request)
{
    // 1. Kiểm tra đăng nhập
    if (!auth()->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Bạn cần đăng nhập!'
        ], 401);
    }

    // 2. Validate dữ liệu đầu vào cơ bản
    $request->validate([
        'quantity' => 'required|integer|min:1|max:10',
    ], [
        'quantity.min' => 'Số lượng ít nhất phải là 1.',
        'quantity.max' => 'Bạn không thể đặt quá 10 phần cho mỗi món ăn.',
    ]);

    $userId = auth()->id();
    $foodId = $request->menu_id;
    $quantity = (int)$request->input('quantity', 1);

    // 3. Tìm món ăn trong Database
    $menu = \App\Models\Menu::find($foodId);

    if (!$menu) {
        return response()->json([
            'success' => false,
            'message' => 'Món không tồn tại'
        ]);
    }

    // 🔥 TRƯỜNG HỢP 1: HẾT HÀNG HOÀN TOÀN
    if ($menu->stock <= 0) {
        return response()->json([
            'success' => false,
            'message' => 'Rất tiếc, món ăn này hiện đã hết hàng!'
        ]);
    }

    // 4. Kiểm tra giỏ hàng hiện tại của User
    $cartItem = \App\Models\Cart::where('user_id', $userId)
        ->where('menu_id', $foodId)
        ->first();

    if ($cartItem) {
        $newQuantity = $cartItem->quantity + $quantity;
        
        // 🔥 TRƯỜNG HỢP 2: TỔNG TRONG GIỎ VƯỢT QUÁ KHO
        if ($newQuantity > $menu->stock) {
            return response()->json([
                'success' => false,
                'message' => "Rất tiếc, bạn đã có {$cartItem->quantity} phần trong giỏ. Kho chỉ còn tổng cộng {$menu->stock} phần."
            ]);
        }

        $cartItem->quantity = $newQuantity;
        $cartItem->save();
    } else {
        // 🔥 TRƯỜNG HỢP 3: THÊM MỚI VƯỢT QUÁ KHO
        if ($quantity > $menu->stock) {
            return response()->json([
                'success' => false,
                'message' => "Món này hiện chỉ còn {$menu->stock} phần."
            ]);
        }

        \App\Models\Cart::create([
            'user_id' => $userId,
            'menu_id' => $foodId,
            'quantity' => $quantity
        ]);
    }

    // 5. Lấy tổng số lượng món trong giỏ để cập nhật badge trên giao diện
    $count = \App\Models\Cart::where('user_id', $userId)->sum('quantity');

    // ✅ PHẢN HỒI THÀNH CÔNG
    return response()->json([
        'success' => true,
        'message' => 'Đã thêm vào thực đơn!',
        'count' => $count
    ]);
}
public function getCart()
{
    $userId = auth()->id();

    $cart = Cart::with('menu')
        ->where('user_id', $userId)
        ->get();

    return response()->json([
        'success' => true,
        'cart' => $cart->map(function ($item) {
            return [
                'id' => $item->menu->id,
                'name' => $item->menu->name,
                'price' => $item->menu->price,
                'image' => $item->menu->image,
                'quantity' => $item->quantity
            ];
        })
    ]);
}
    /**
     * 3. XÓA SẠCH GIỎ HÀNG
     */
    public function clear()
    {
        if (Auth::check()) {
            // Xóa dự phòng trong DB nếu có
            $latestRes = Reservation::where('user_id', Auth::id())->latest()->first();
            if ($latestRes) {
                $latestRes->update(['cart_data' => null]);
            }
        }
        
        session()->forget(['cart']);
        return redirect()->route('reservation.index')->with('success', 'Đã xóa sạch giỏ hàng!');
    }

    /**
     * 4. THANH TOÁN (Xử lý chốt đơn cuối cùng)
     */
/**
     * 4. THANH TOÁN (Xử lý chốt đơn cuối cùng)
     */
 public function checkout(Request $request)
{
    $userId = auth()->id();

    // 1. Lấy giỏ hàng từ DATABASE
    $cartItems = \App\Models\Cart::with('menu')
        ->where('user_id', $userId)
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống.');
    }

    // 🔥 2. KIỂM TRA TỒN KHO (STOCK) TRƯỚC KHI XỬ LÝ
    foreach ($cartItems as $item) {
        if (!$item->menu || $item->quantity > $item->menu->stock) {
            $stockAvailable = $item->menu->stock ?? 0;
            return redirect()->back()->with('error', "Món '{$item->menu->name}' hiện không đủ số lượng (Chỉ còn {$stockAvailable} phần). Vui lòng điều chỉnh lại giỏ hàng.");
        }
    }

    // 3. Tính tổng tiền
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += ($item->menu->price ?? 0) * $item->quantity;
    }

    // 4. Lấy phương thức thanh toán & Tạo mã đơn hàng
    $pm = $request->input('payment_method');
    $orderCode = (string)rand(100000, 999999);

    // 5. Lấy TABLE_ID từ RESERVATION
    $latestReservation = \App\Models\Reservation::where('user_id', $userId)
        ->latest()
        ->first();
    $tableId = $latestReservation->table_id ?? null;

    // 6. Tạo Order
    $order = \App\Models\Order::create([
        'user_id'         => $userId,
        'code'            => $orderCode,
        'total_price'     => $totalAmount,
        'status'          => ($pm == 'BANK') ? 'paid' : 'pending',
        'payment_method'  => $pm,
        'table_number'    => $tableId,
        'name'            => auth()->user()->name,
        'phone'           => auth()->user()->phone,
        'notes'           => $request->order_notes ?? null,
    ]);

    // 7. Lưu OrderItem & 🔥 TRỪ TỒN KHO TRONG DATABASE
    foreach ($cartItems as $item) {
        // Lưu chi tiết đơn hàng
        \App\Models\OrderItem::create([
            'order_id'     => $order->id,
            'menu_id'      => $item->menu_id,
            'quantity'     => $item->quantity,
            'price'        => $item->menu->price ?? 0,
            'product_name' => $item->menu->name ?? '',
        ]);

        // Cập nhật giảm số lượng trong bảng menus
        $item->menu->decrement('stock', $item->quantity);
    }

    // 8. Xóa giỏ hàng
    \App\Models\Cart::where('user_id', $userId)->delete();
    session()->forget('cart');

    return redirect('/')->with('success', 'Thanh toán thành công! Đơn hàng đã được chuyển tới nhà bếp.');
}
public function removeItem($id)
{
    $userId = auth()->id();

    // Tìm món đó trong giỏ của đúng User này và xóa
    \App\Models\Cart::where('user_id', $userId)
                    ->where('menu_id', $id)
                    ->delete();

    // THÊM DÒNG NÀY: Xóa thêm giỏ hàng trong Session (nếu Nhi có dùng session để hiện badge số lượng)
    session()->forget('cart');
    return redirect()->route('order-history') 
        ->with('success', 'Đơn hàng của bạn đã được gửi đi thành công!');
}


public function history()
{
    // Lấy danh sách đơn hàng của người dùng đang đăng nhập
    // Sắp xếp theo thời gian mới nhất
    $orders = \App\Models\Order::where('user_id', auth()->id())->get();
    return view('cart.history', compact('orders')); // Dấu chấm đại diện cho thư mục cart/
}
/**
     * CẬP NHẬT SỐ LƯỢNG (KHI KHÁCH BẤM +/-)
     */
    public function update(Request $request)
    {
        $userId = auth()->id();
        $menuId = $request->id;
        $quantity = (int) $request->quantity;

        // 1. Cập nhật số lượng mới vào Database giỏ hàng
        if ($quantity > 0) {
            \App\Models\Cart::where('user_id', $userId)
                ->where('menu_id', $menuId)
                ->update(['quantity' => $quantity]);
        }

        // 2. Đồng bộ ngay lập tức cho Nhân viên
        $this->syncCustomerCartToStaff($userId);

        return response()->json(['success' => true]);
    }

    /**
     * XÓA MÓN ĂN (KHI KHÁCH BẤM NÚT THÙNG RÁC)
     */
    public function remove($id)
    {
        $userId = auth()->id();

        // 1. Xóa món khỏi Database giỏ hàng
        \App\Models\Cart::where('user_id', $userId)
                        ->where('menu_id', $id)
                        ->delete();

        // Xóa luôn session phụ trợ (nếu có dùng để hiển thị icon giỏ hàng)
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) { unset($cart[$id]); }
        session()->put('cart', $cart);

        // 2. Đồng bộ ngay lập tức cho Nhân viên
        $this->syncCustomerCartToStaff($userId);

        return redirect()->back()->with('success', 'Đã xóa món ăn khỏi giỏ hàng!');
    }

    /**
     * HÀM TIỆN ÍCH: ĐỒNG BỘ TỪ GIỎ HÀNG KHÁCH -> MÀN HÌNH NHÂN VIÊN
     */
    private function syncCustomerCartToStaff($userId)
    {
        // 1. Lấy giỏ hàng mới nhất
        $cartItems = \App\Models\Cart::with('menu')->where('user_id', $userId)->get();
        $cartData = [];
        $totalPrice = 0;

        foreach ($cartItems as $item) {
            if (!$item->menu) continue;
            $cartData[] = [
                'id'       => $item->menu_id,
                'name'     => $item->menu->name,
                'price'    => $item->menu->price,
                'quantity' => $item->quantity,
                'image'    => $item->menu->image
            ];
            $totalPrice += $item->quantity * $item->menu->price;
        }

        // 2. Cập nhật Bảng Đặt Bàn (Reservations) để Nhân viên quản lý
        $reservation = \App\Models\Reservation::where('user_id', $userId)->latest()->first();
        if ($reservation) {
            $reservation->update([
                'cart_data'   => $cartData,
                'total_price' => $totalPrice
            ]);

            // 3. Cập nhật Hóa đơn (Orders)
            $order = \App\Models\Order::where('table_number', $reservation->table_id)
                        ->whereIn('status', ['pending', 'paid', 'serving'])
                        ->latest()->first();
                        
            if ($order) {
                $order->update(['total_price' => $totalPrice]);
                \App\Models\OrderItem::where('order_id', $order->id)->delete();
                foreach ($cartData as $cd) {
                    \App\Models\OrderItem::create([
                        'order_id'     => $order->id,
                        'menu_id'      => $cd['id'],
                        'quantity'     => $cd['quantity'],
                        'price'        => $cd['price'],
                        'product_name' => $cd['name']
                    ]);
                }
            }
        }
    }
}
