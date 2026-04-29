<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Reservation;

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
    public function update(Request $request, $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        $cartData   = [];
        $totalPrice = 0;

        // 1. Tính toán lại giỏ hàng và tổng tiền
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

        // 2. Lưu vào bảng reservations (Dành cho màn hình Staff)
        $reservation->update([
            'cart_data'   => $cartData,
            'total_price' => $totalPrice,
        ]);

        // =========================================================
        // 3. ĐỒNG BỘ DỮ LIỆU SANG BẢNG ORDER (Dành cho màn hình Khách)
        // =========================================================
        $order = \App\Models\Order::where('table_number', $reservation->table_id)
                    ->whereIn('status', ['pending', 'paid']) // Chỉ tìm đơn đang hoạt động
                    ->latest()
                    ->first();

        if ($order) {
            // Cập nhật tổng tiền hóa đơn
            $order->total_price = $totalPrice;
            $order->save();

            // Xóa chi tiết món cũ
            \App\Models\OrderItem::where('order_id', $order->id)->delete();

            // Thêm chi tiết món mới dựa trên $cartData vừa tạo ở trên
            foreach ($cartData as $item) {
                if ($item['quantity'] > 0) {
                    \App\Models\OrderItem::create([
                        'order_id'     => $order->id,
                        'menu_id'      => $item['id'],
                        'quantity'     => $item['quantity'],
                        'price'        => $item['price'],
                        'product_name' => $item['name'],
                    ]);
                }
            }
        }
        // =========================================================

        // 4. Trả về lại trang chỉnh sửa kèm thông báo xanh lá
        return redirect()->back()
                         ->with('success', 'Đã cập nhật đơn và đồng bộ cho khách!');
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
