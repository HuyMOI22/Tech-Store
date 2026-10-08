<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HUYMOI STORE - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    /* Fix pagination size */
    .pagination .page-link {
        font-size: 14px !important;
        padding: 6px 12px !important;
        line-height: 1.5 !important;
    }
    .pagination .page-item .page-link svg {
        width: 16px !important;
        height: 16px !important;
    }
</style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
    {{-- Logo SVG --}}
    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect width="36" height="36" rx="8" fill="#0f172a"/>
        <rect x="6" y="8" width="22" height="14" rx="3" fill="none" stroke="#38bdf8" stroke-width="2"/>
        <rect x="12" y="22" width="12" height="2" rx="1" fill="#38bdf8"/>
        <rect x="14" y="24" width="8" height="3" rx="1" fill="#38bdf8"/>
        <rect x="9" y="11" width="14" height="2" rx="1" fill="#38bdf8" opacity="0.6"/>
        <rect x="9" y="15" width="10" height="2" rx="1" fill="#38bdf8" opacity="0.4"/>
        <circle cx="26" cy="10" r="2" fill="#f472b6"/>
    </svg>
    {{-- Text --}}
    <div class="lh-1">
        <span style="font-size:16px; font-weight:700; color:#f8fafc; letter-spacing:1px">HUYMOI</span>
        <span style="font-size:10px; color:#38bdf8; letter-spacing:3px; display:block">STORE</span>
    </div>
</a>
            {{-- Ô tìm kiếm --}}
        <form action="{{ route('products.index') }}" method="GET"
              class="d-flex mx-auto" style="width:40%">
            <input type="text" name="search" class="form-control"
                   placeholder="🔍 Tìm sản phẩm..."
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary ms-2">Tìm</button>
        </form>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('products.index') }}" class="text-white text-decoration-none">Sản phẩm</a>
                @auth
                    <a href="{{ route('cart.index') }}" class="text-white text-decoration-none">🛒 Giỏ hàng</a>
                    <a href="{{ route('orders.index') }}" class="text-white text-decoration-none">Đơn hàng</a>
                    @if(auth()->user()->is_admin)
                    <a href="/admin/dashboard" class="btn btn-warning btn-sm">
                        ⚙️ Admin
                    </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Đăng ký</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
    </div>

    <main class="container my-4">
        @yield('content')
    </main>

    <footer class="bg-dark text-white text-center py-2 mt-5">
        <p class="mb-0">© 2026 HUYMOI STORE. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>