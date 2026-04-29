<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                // Chuyển hướng đích danh theo ID số
                return match ((int)$user->role_id) {
                    2 => redirect()->route('admin.index'),
                    3 => redirect()->route('staff.reservations.index'),
                    4 => redirect()->route('chef.index'),
                    1 => redirect()->route('cashier.index'),
                    default => redirect('/'),
                };
            }
        }
        return $next($request);
    }
}