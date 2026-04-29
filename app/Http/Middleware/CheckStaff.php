<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckStaff
{
   public function handle($request, Closure $next)
{
    // Cho phép cả staff và admin truy cập vào các link /staff/...
    if (auth()->check() && in_array(auth()->user()->role_id, [2, 3])) {
    return $next($request);
}

    // Nếu bị đá ra, bạn sẽ thấy thông báo này ở trang chủ
    return redirect('/')->with('error', 'Bạn không có quyền vào khu vực phục vụ!');
}
}
