<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Khách đặt hàng online (không qua đặt bàn)
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id'      => Auth::id(),
                'table_number' => $request->table_number ?? 'Mang về',
                'total_price'  => $request->total_price,
                'status'       => 'pending',
                'name'         => Auth::user()->name,
                'phone'        => Auth::user()->phone,
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'menu_id'      => $item['id'],
                    'product_name' => $item['name'],
                    'quantity'     => $item['quantity'],
                    'price'        => $item['price'],
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Đã chuyển đơn hàng vào hệ thống!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Lịch sử đơn hàng của khách
     */
    public function history()
    {
       $orders = \App\Models\Order::with(['user.role', 'orderItems.product.category'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(20);
    
    return view('orders.history', compact('orders'));
    }

    /**
     * Chi tiết đơn hàng — FIX: Admin có thể xem bất kỳ đơn nào
     */
    public function show($id)
{
    $order = Order::with([
        'items',
        'items.menu',
        'user',
        'table'
    ])->findOrFail($id);

    return view('orders.show', compact('order'));
}

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->input('status')]);
        return redirect()->back()->with('success', 'Đã cập nhật trạng thái!');
    }

    /**
     * Hủy đơn hàng (chỉ khi ở trạng thái pending)
     */
    public function cancel($id)
    {
        try {
            $order = Order::findOrFail($id);

            // Chỉ cho phép hủy khi ở trạng thái pending
            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ có thể hủy đơn ở trạng thái chờ duyệt!'
                ], 400);
            }

            // Cập nhật trạng thái thành cancelled
            $order->update(['status' => 'cancelled']);

            // Broadcast cập nhật cho admin, nhân viên và khách hàng qua Echo
            event(new \App\Events\OrderStatusUpdated($order));

            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã bị hủy thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // =========================================================
    // MÀN HÌNH BẾP (KDS - Kitchen Display System)
    // Luồng: pending → confirmed → arrived → serving → ready → served
    // =========================================================

    /**
     * Dashboard bếp — Bếp thấy đơn khi status = 'serving' hoặc 'arrived'
     */
    public function chefIndex()
    {
        // Đơn đang cần nấu (Staff đã gửi bếp)
        $pendingOrders = Reservation::with('table')
            ->whereIn('status', ['arrived', 'serving'])
            ->oldest('updated_at')
            ->get();

        // Đơn đã nấu xong (chờ Staff bưng ra)
        $readyOrders = Reservation::where('status', 'ready')
            ->latest('updated_at')
            ->get();

        $pendingItems = 0;
        $cookingItems = 0;

        foreach ($pendingOrders as $res) {
            $statuses = $res->chef_statuses ?? [];
            foreach (($res->cart_data ?? []) as $item) {
                $st  = $statuses[$item['id']] ?? 'pending';
                $qty = $item['quantity'] ?? 1;
                if ($st === 'pending') $pendingItems += $qty;
                if ($st === 'cooking') $cookingItems += $qty;
            }
        }

        return view('chef.dashboard', compact('pendingOrders', 'readyOrders', 'pendingItems', 'cookingItems'));
    }

    /**
     * Cập nhật trạng thái từng món (pending → cooking → done)
     */
    public function updateItemStatus(Request $request, $reservationId, $menuId)
    {
        $reservation             = Reservation::findOrFail($reservationId);
        $statuses                = $reservation->chef_statuses ?? [];
        $statuses[$menuId]       = $request->chef_status;
        $reservation->chef_statuses = $statuses;

        // Khi bắt đầu nấu → chuyển trạng thái đơn sang 'serving'
        if ($request->chef_status === 'cooking' && $reservation->status === 'arrived') {
            $reservation->status = 'serving';
        }

        $reservation->save();
        return back()->with('success', 'Đã cập nhật trạng thái món!');
    }

    /**
     * Bếp bấm "Bắt đầu nấu" toàn bộ đơn
     */
    public function startOrder($id)
    {
        $reservation = Reservation::findOrFail($id);
        $statuses    = [];

        foreach (($reservation->cart_data ?? []) as $item) {
            $statuses[$item['id']] = 'cooking';
        }

        $reservation->chef_statuses = $statuses;
        $reservation->status        = 'serving';
        $reservation->save();

        return back()->with('success', 'Đã bắt đầu chế biến toàn bộ món!');
    }

    /**
     * Bếp bấm "Nấu xong" → Staff biết để bưng lên
     */
    public function finishOrder($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'ready']);

        // Đồng bộ sang Order
        $order = Order::where('user_id', $reservation->user_id)->latest()->first();
        if ($order) {
            $order->update(['status' => 'ready']);
        }

        return back()->with('success', 'Bếp đã nấu xong! Staff hãy bưng món ra.');
    }

    /**
     * Bếp đánh dấu đơn đã hoàn tất (Served)
     */
    public function markAsDone($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'served']);
        return back()->with('success', 'Đã báo hoàn thành!');
    }
    // Danh sách đơn (Admin)
public function adminIndex()
{
    $orders = Order::with('user')->latest()->paginate(20);
    return view('admin.orders.index', compact('orders'));
}

// Form sửa trạng thái
public function edit($id)
{
    $order = Order::with('user')->findOrFail($id);
    return view('admin.orders.edit', compact('order'));
}

// Lưu trạng thái mới
public function update(Request $request, $id)
{
    $order = Order::findOrFail($id);
    $order->update(['status' => $request->status]);
    return redirect()->route('admin.orders.show', $id)
                     ->with('success', 'Cập nhật trạng thái thành công!');
}

// Xoá đơn
public function destroy($id)
{
    Order::findOrFail($id)->delete();
    return redirect()->route('admin.index')
                     ->with('success', 'Đã xoá đơn hàng!');
}
}
