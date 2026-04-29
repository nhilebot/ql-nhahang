<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Role ID trong hệ thống:
     *  1 = Cashier (Thu ngân)
     *  2 = Admin   (Quản trị viên)
     *  3 = Staff   (Nhân viên phục vụ)
     *  4 = Chef    (Đầu bếp)
     */
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRoleId    = (int) Auth::user()->role_id;
        $requiredRoleId = (int) $role;

        // 1. Đúng quyền → cho qua
        if ($userRoleId === $requiredRoleId) {
            return $next($request);
        }

        // 2. Admin (2) có thể vào tất cả khu vực của Staff (3)
        if ($userRoleId === 2 && $requiredRoleId === 3) {
            return $next($request);
        }

        // 3. Sai quyền → redirect về trang phù hợp với role hiện tại
        return $this->redirectByRole($userRoleId);
    }

    private function redirectByRole(int $roleId)
    {
        return match ($roleId) {
            2 => redirect()->route('admin.index')->with('error', 'Bạn không có quyền vào khu vực này!'),
            3 => redirect()->route('staff.reservations.index')->with('error', 'Bạn không có quyền vào khu vực này!'),
            4 => redirect()->route('chef.index')->with('error', 'Bạn không có quyền vào khu vực này!'),
            1 => redirect()->route('cashier.index')->with('error', 'Bạn không có quyền vào khu vực này!'),
            default => redirect('/')->with('error', 'Bạn không có quyền truy cập!'),
        };
    }
}
