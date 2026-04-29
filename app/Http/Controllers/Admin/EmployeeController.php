<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\EmployeeShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
   public function index(Request $request)
{
    $role = $request->get('role', 'staff'); 
    $roleModel = Role::where('name', $role)->first();

    if (!$roleModel) {
        abort(404);
    }

    $employees = User::with('role') // 👈 QUAN TRỌNG
        ->where('role_id', $roleModel->id)
        ->orderBy('name')
        ->get();

    $roles = Role::whereIn('name', ['staff', 'chef'])->get();

    return view('admin.employees.index', compact('employees', 'role', 'roles'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role_id'  => 'required|exists:roles,id',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
            'phone'    => $request->phone,
        ]);

        $role = Role::find($request->role_id);
        return redirect()->route('admin.employees.index', ['role' => $role?->name])
                         ->with('success', 'Đã thêm nhân viên ' . $user->name);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $role = $user->role?->name ?? 'staff';
        $user->delete();
        return redirect()->route('admin.employees.index', ['role' => $role])
                         ->with('success', 'Đã xóa nhân viên.');
    }

public function updateShift(Request $request)
{
    $user = \App\Models\User::findOrFail($request->user_id);
    
    // Lấy mảng shifts hiện tại
    $shifts = $user->shifts ?? [];
    
    // SỬA DÒNG NÀY: Lấy trực tiếp key (T2, T3...) từ request gửi lên
    $dayKey = $request->day_of_week; 
    
    $shifts[$dayKey] = $request->shift;
    
    // Lưu lại vào cột JSON
    $user->shifts = $shifts;
    $user->save();

    return response()->json(['status' => 'success']);
}
}