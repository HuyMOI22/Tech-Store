<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('orders')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function toggleAdmin(User $user)
{
    if ($user->id === auth()->id()) {
        return back()->with('error', 'Không thể thay đổi quyền của chính mình!');
    }

    $newStatus = !$user->is_admin;
    $user->update(['is_admin' => $newStatus]);

    // Lấy giá trị MỚI sau khi update
    $msg = $newStatus ? 'Đã cấp quyền Admin thành công!' : 'Đã thu hồi quyền Admin!';

    return back()->with('success', $msg);
}

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể xóa tài khoản đang đăng nhập!');
        }

        $user->delete();
        return back()->with('success', 'Đã xóa tài khoản!');
    }
}