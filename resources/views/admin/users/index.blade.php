@extends('admin.layouts.app')
@section('title', 'Quản lý tài khoản')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">👤 Quản lý tài khoản</h5>
    <span class="badge bg-primary fs-6">{{ $users->total() }} tài khoản</span>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card border-0 shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Tên tài khoản</th>
                <th>Email</th>
                <th>Quyền hiện tại</th>
                <th>Số đơn hàng</th>
                <th>Ngày đăng ký</th>
                <th>Cấp quyền</th>
                <th>Xóa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td class="text-muted">{{ $user->id }}</td>

                <td>
                    <div class="fw-bold">{{ $user->name }}</div>
                    @if($user->id === auth()->id())
                        <small class="badge bg-secondary">Tài khoản của bạn</small>
                    @endif
                </td>

                <td class="text-muted">{{ $user->email }}</td>

                {{-- Quyền hiện tại --}}
                <td>
                    @if($user->is_admin)
                        <span class="badge bg-warning text-dark fs-6">
                            ⚙️ Admin
                        </span>
                    @else
                        <span class="badge bg-secondary fs-6">
                            👤 Khách hàng
                        </span>
                    @endif
                </td>

                {{-- Số đơn hàng --}}
                <td>
                    <span class="badge bg-primary">
                        {{ $user->orders_count }} đơn
                    </span>
                </td>

                {{-- Ngày đăng ký --}}
                <td class="text-muted small">
                    {{ $user->created_at->format('d/m/Y H:i') }}
                </td>

                {{-- Cấp / Thu hồi quyền Admin --}}
                <td>
                    @if($user->id !== auth()->id())
                    <form method="POST"
                          action="{{ route('admin.users.toggleAdmin', $user) }}">
                        @csrf @method('PATCH')
                        @if($user->is_admin)
                            <button type="submit"
                                    class="btn btn-warning btn-sm"
                                    onclick="return confirm('Thu hồi quyền Admin của {{ $user->name }}?')">
                                ⬇️ Thu hồi Admin
                            </button>
                        @else
                            <button type="submit"
                                    class="btn btn-success btn-sm"
                                    onclick="return confirm('Cấp quyền Admin cho {{ $user->name }}?')">
                                ⬆️ Cấp Admin
                            </button>
                        @endif
                    </form>
                    @else
                        <span class="text-muted small">—</span>
                    @endif
                </td>

                {{-- Xóa tài khoản --}}
                <td>
                    @if($user->id !== auth()->id())
                    <form method="POST"
                          action="{{ route('admin.users.destroy', $user) }}">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="btn btn-outline-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn xóa tài khoản {{ $user->name }}?\nHành động này không thể hoàn tác!')">
                            🗑️ Xóa
                        </button>
                    </form>
                    @else
                        <span class="text-muted small">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-5">
                    <div class="fs-1">👤</div>
                    <p>Chưa có tài khoản nào</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Phân trang --}}
<div class="mt-3">
    {{ $users->links('pagination::bootstrap-5') }}
</div>

@endsection