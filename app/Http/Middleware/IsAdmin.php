<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // ✅ Chỉ Admin (role_id = 2) mới vào được
        if (auth()->user()->role_id == 2) {
            return $next($request);
        }

        // ✅ Redirect đúng chỗ thay vì về '/'
        return match((int)auth()->user()->role_id) {
            3 => redirect()->route('staff.reservations.index')
                           ->with('error', 'Bạn không có quyền Admin!'),
            4 => redirect()->route('chef.index')
                           ->with('error', 'Bạn không có quyền Admin!'),
            1 => redirect()->route('cashier.index')
                           ->with('error', 'Bạn không có quyền Admin!'),
            default => redirect('/')->with('error', 'Bạn không có quyền truy cập!'),
        };
    }
}