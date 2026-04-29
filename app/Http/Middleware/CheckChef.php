<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckChef
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role_id == 4) {
    return $next($request);
}
        return redirect('/')->with('error', 'Khu vực này chỉ dành cho Đầu bếp!');
    }
}
