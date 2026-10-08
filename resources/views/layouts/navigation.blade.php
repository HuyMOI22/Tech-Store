<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            🖥️ Tech Store
        </a>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('products.index') }}" class="text-white text-decoration-none">Sản phẩm</a>
            @auth
                <a href="{{ route('cart.index') }}" class="text-white text-decoration-none">🛒 Giỏ hàng</a>
                <a href="{{ route('orders.index') }}" class="text-white text-decoration-none">Đơn hàng</a>
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