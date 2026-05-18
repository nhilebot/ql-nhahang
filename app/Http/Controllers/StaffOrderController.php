<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
class StaffOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Hiển thị form tạo order mới cho bàn
     */
    public function create($reservationId)
    {
        $reservation = Reservation::with('table')->findOrFail($reservationId);
        $menus       = Menu::all();
        $categories  = Menu::distinct()->pluck('category')->filter();

        return view('staff.order-create', compact('reservation', 'menus', 'categories'));
    }

    /**
     * Lưu order mới (chưa gửi bếp)
     */
    public function store(Request $request, $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        $cartData   = [];
        $totalPrice = 0;

        foreach ($request->items ?? [] as $item) {
            $menu = Menu::find($item['id']);    
            if (!$menu) continue;

            $qty         = (int) $item['quantity'];
            $totalPrice += $menu->price * $qty;

            $cartData[] = [
                'id'       => $menu->id,
                'name'     => $menu->name,
                'price'    => $menu->price,
                'quantity' => $qty,
                'image'    => $menu->image,
            ];
        }

        $reservation->update([
            'cart_data'   => $cartData,
            'total_price' => $totalPrice,
            'notes'       => $request->notes ?? $reservation->notes,
        ]);

        return redirect()->route('staff.reservations.index')
                         ->with('success', 'Đã lưu đơn thành công!');
    }

    /**
     * Gửi đơn lên bếp — FIX: dùng đúng tên cột 'status'
     * Luồng: Staff xác nhận khách đã ngồi → status = 'serving' → Bếp nhận đơn
     */
    public function sendToKitchen($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        // Đảm bảo đã có món mới được gửi bếp
        if (empty($reservation->cart_data)) {
            return redirect()->back()->with('error', 'Vui lòng thêm món trước khi gửi bếp!');
        }

        $reservation->update(['status' => 'serving']);

        return redirect()->route('staff.reservations.index')
                         ->with('success', 'Đã gửi bếp! Bếp sẽ bắt đầu chế biến.');
    }

    /**
     * Hiển thị form sửa order
     */
    public function edit($reservationId)
    {
        $reservation = Reservation::with('table')->findOrFail($reservationId);
        $menus       = Menu::all();

        return view('staff.order-edit', compact('reservation', 'menus'));
    }

    /**
     * Cập nhật order (Staff sửa món)
     */
    /**
     * Cập nhật order (Staff sửa món) và Đồng bộ sang Hóa đơn khách hàng
     */
   /**
     * Cập nhật đơn món ăn (Staff gọi thêm món cho khách tại bàn)
     */
    public function update(Request $request, $reservationId)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $reservation = Reservation::findOrFail($reservationId);
            $inputItems = $request->input('items');

            $newCartData = [];
            $newTotalPrice = 0;
            $chefStatuses = $reservation->chef_statuses ?? [];
            
            // Mảng thu thập các phần món ăn phát sinh gọi thêm đợt này để báo sang bếp
            $kitchenAdditions = [];

            foreach ($inputItems as $item) {
                $menuId = $item['id'];
                $newQty = (int)$item['quantity'];
                $oldQty = (int)($item['old_quantity'] ?? 0);

                if ($newQty <= 0) continue;

                $menu = Menu::find($menuId);
                if (!$menu) continue;

                // Tính toán lượng chênh lệch khách gọi thêm đợt này
                $addedQty = $newQty - $oldQty;

                if ($addedQty > 0) {
                    // Kiểm tra tồn kho vật lý của nhà hàng
                    if ($menu->stock < $addedQty) {
                        return redirect()->back()->with('error', "Món '{$menu->name}' hiện không đủ phần đáp ứng (Chỉ còn {$menu->stock} phần).");
                    }
                    
                    // Trừ kho vật lý lượng gọi thêm phát sinh
                    $menu->decrement('stock', $addedQty);

                    // Ghi nhận thông tin món chuyển tiếp xuống bộ phận bếp nấu
                    $kitchenAdditions[$menuId] = [
                        'name' => $menu->name,
                        'quantity' => $addedQty
                    ];

                    // Quản lý trạng thái KDS của bếp:
                    // Nếu là món mới hoàn toàn hoặc món cũ đã nấu xong giờ gọi thêm -> Đặt về 'pending' để bếp nhận diện làm bù
                    $chefStatuses[$menuId] = 'pending';
                }

                // Đóng gói dữ liệu giỏ hàng mới cho bàn ăn
                $newCartData[] = [
                    'id'       => $menuId,
                    'name'     => $menu->name,
                    'price'    => $menu->price,
                    'quantity' => $newQty,
                    'image'    => $menu->image
                ];

                $newTotalPrice += $newQty * $menu->price;
            }

            // 1. Cập nhật lại thông tin bảng đặt bàn (Reservations)
           // Trong vòng foreach xử lý $inputItems — GIỮ NGUYÊN toàn bộ logic hiện tại
// Chỉ thay đoạn UPDATE cuối cùng:

// 1. Cập nhật reservation
$updateData = [
    'cart_data'     => $newCartData,
    'total_price'   => $newTotalPrice,
    'chef_statuses' => $chefStatuses,
];

// 🔥 KEY FIX: Nếu có món gọi thêm mới → đặt lại status = 'arrived'
// để KDS hiện card đơn bổ sung cho bếp thấy
if (!empty($kitchenAdditions)) {
    $updateData['status'] = 'arrived';
}

$reservation->update($updateData);

            // 2. ĐỒNG BỘ DỮ LIỆU SANG BẢNG ORDER VÀ ORDER_ITEMS (Dành cho hóa đơn của khách)
            $order = \App\Models\Order::where('table_number', $reservation->table_id)
                ->whereIn('status', ['pending', 'paid', 'serving']) // Tìm hóa đơn chưa thanh toán của bàn này
                ->latest()
                ->first();

            if ($order) {
                // Cập nhật lại tổng tiền bill mới
                $order->total_price = $newTotalPrice;
                $order->save();

                // Xóa chi tiết hóa đơn cũ và tạo lại toàn bộ để đồng bộ cho kế toán xuất bill
                \App\Models\OrderItem::where('order_id', $order->id)->delete();
                foreach ($newCartData as $cd) {
                    \App\Models\OrderItem::create([
                        'order_id'     => $order->id,
                        'menu_id'      => $cd['id'],
                        'quantity'     => $cd['quantity'],
                        'price'        => $cd['price'],
                        'product_name' => $cd['name']
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Đã lưu thay đổi và chuyển món gọi thêm xuống bếp thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi phát sinh: ' . $e->getMessage());
        }
    }
    public function editItems($id)
{
    $reservation = \App\Models\Reservation::with('table')->findOrFail($id);
    $menus = \App\Models\Menu::all();
    
    // THÊM DÒNG NÀY: Trích xuất giỏ hàng để truyền sang View
    $cartItems = $reservation->cart_data ?? []; 

    return view('staff.reservations.edit-items', compact('reservation', 'menus', 'cartItems'));
}
}
