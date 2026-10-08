<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; background: #2d3748; }
        .sidebar a { color: #a0aec0; text-decoration: none; display: block; padding: 10px 20px; }
        .sidebar a:hover, .sidebar a.active { background: #4a5568; color: white; }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        {{-- Sidebar --}}
        <div class="sidebar" style="width:250px; min-width:250px;">
            <div class="p-4 text-white fw-bold fs-5 border-bottom border-secondary">
                🖥️ ADMIN PANEL
            </div>
            <nav class="mt-2">
                <a href="/admin/dashboard" class="d-flex align-items-center gap-2">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Tài khoản
                </a>
                <a href="/admin/categories" class="d-flex align-items-center gap-2">
                    <i class="bi bi-grid"></i> Danh mục
                </a>
                <a href="/admin/products" class="d-flex align-items-center gap-2">
                    <i class="bi bi-box"></i> Sản phẩm
                </a>
                <a href="/admin/orders" class="d-flex align-items-center gap-2">
                    <i class="bi bi-cart"></i> Đơn hàng
                </a>
                <hr class="border-secondary">
                <a href="{{ route('home') }}" class="d-flex align-items-center gap-2">
                    <i class="bi bi-house"></i> Về trang chủ
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger px-4 py-2">
                        <i class="bi bi-box-arrow-right"></i> Đăng xuất
                    </button>
                </form>
            </nav>
        </div>

        {{-- Main Content --}}
        <div class="flex-grow-1">
            <nav class="navbar bg-white border-bottom px-4">
                <span class="fw-bold">@yield('title')</span>
                <span class="text-muted">{{ auth()->user()->name }}</span>
            </nav>
            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>