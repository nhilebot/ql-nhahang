<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Reservation;

class CashierController extends Controller
{
    // ✅ Dùng role_id số thay vì middleware 'isCashier' (không rõ logic bên trong)
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $roleId = (int) auth()->user()->role_id;

            // Thu ngân (1) và Admin (2) đều được vào cashier
            if (!in_array($roleId, [1, 2])) {
                return match ($roleId) {
                    3 => redirect()->route('staff.reservations.index')->with('error', 'Không có quyền truy cập!'),
                    4 => redirect()->route('chef.index')->with('error', 'Không có quyền truy cập!'),
                    default => redirect('/')->with('error', 'Không có quyền truy cập!'),
                };
            }

            return $next($request);
        });
    }

    public function index()
    {
        $pendingPayment = Reservation::with(['table'])
            ->whereIn('status', ['served', 'arrived', 'serving'])
            ->latest()
            ->get();

        $paidToday = Reservation::whereIn('status', ['paid_cash', 'paid_transfer', 'completed'])
            ->whereDate('updated_at', today())
            ->get();

        $revenueToday = $paidToday->sum('total_price');
        $countPaid    = $paidToday->count();
        $countPending = $pendingPayment->count();

        return view('cashier.index', compact(
            'pendingPayment', 'paidToday', 'revenueToday', 'countPaid', 'countPending'
        ));
    }

    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:paid_cash,paid_transfer',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => $request->payment_method]);

        return redirect()->route('cashier.index')
                         ->with('success', 'Thanh toán thành công cho bàn ' . ($reservation->table_id ?? '#' . $id));
    }

    public function history()
    {
        $paidReservations = Reservation::with(['table'])
            ->whereIn('status', ['paid_cash', 'paid_transfer', 'completed'])
            ->latest()
            ->paginate(20);

        $revenueTotal = Reservation::whereIn('status', ['paid_cash', 'paid_transfer', 'completed'])
            ->sum('total_price');

        $revenueToday = Reservation::whereIn('status', ['paid_cash', 'paid_transfer', 'completed'])
            ->whereDate('updated_at', today())
            ->sum('total_price');

        return view('cashier.history', compact('paidReservations', 'revenueTotal', 'revenueToday'));
    }
}