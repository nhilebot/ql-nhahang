<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Hiển thị trang đăng nhập
    public function showLogin()
    {
        return view('login');
    }

    // Xử lý đăng nhập
public function login(Request $request)
{
    
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
    $user = Auth::user();
    return match((int)$user->role_id) {
        2 => redirect()->route('admin.index'),
        3 => redirect()->route('staff.reservations.index'),
        4 => redirect()->route('chef.index'),
        1 => redirect()->route('cashier.index'),
        default => redirect('/'),
    };
}

    return back()->withErrors([
        'email' => 'Sai tài khoản hoặc mật khẩu'
    ]);
}

    // Hiển thị trang đăng ký
    public function showRegister()
    {
        return view('register');
    }

    // Xử lý đăng ký
   public function register(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required',
        'password' => 'required|confirmed|min:6'
    ]);

    // Thêm role_id vào đây để sửa lỗi SQL 1364
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'role_id' => 5, // Giả sử 5 là ID của khách hàng
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Đăng ký thành công'
    ]);
}

    // Xử lý đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Đã đăng xuất thành công');
    }
    protected function loggedOut(Request $request)
{
    return redirect('/');
}
// protected function authenticated(Request $request, $user)
// {
//     // Kiểm tra nếu là nhân viên hoặc admin thì đẩy vào trang quản lý đặt bàn
//     if (in_array($user->role_id, ['admin', 'staff', 'chef'])) {
//         return redirect()->route('admin.reservations.index');
//     }

//     return redirect('/'); // Khách thì về trang chủ
// }
}