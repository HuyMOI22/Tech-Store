@extends('layouts.app')
@section('title', $product->name)
@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item">
            <a href="{{ route('category', $product->category->slug) }}">
                {{ $product->category->name }}
            </a>
        </li>
        <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row g-4">
    {{-- Ảnh sản phẩm --}}
    <div class="col-md-5">
        <img id="mainImg"
             src="{{ asset('storage/' . $product->image) }}"
             class="img-fluid rounded shadow w-100"
             style="height:550px; object-fit:cover;"
             onerror="this.src='https://placehold.co/400x400?text=No+Image'"
             alt="{{ $product->name }}">

        @if($product->images && count($product->images) > 0)
        <div class="d-flex gap-2 mt-3 flex-wrap">
            <img src="{{ asset('storage/' . $product->image) }}"
                 class="rounded border border-primary"
                 style="width:70px; height:70px; object-fit:cover; cursor:pointer"
                 onclick="document.getElementById('mainImg').src=this.src"
                 onerror="this.src='https://placehold.co/70x70'">
            @foreach($product->images as $img)
            <img src="{{ asset('storage/' . $img) }}"
                 class="rounded border"
                 style="width:70px; height:70px; object-fit:cover; cursor:pointer"
                 onclick="document.getElementById('mainImg').src=this.src"
                 onerror="this.src='https://placehold.co/70x70'">
            @endforeach
        </div>
        @endif
    </div>

    {{-- Thông tin sản phẩm --}}
    <div class="col-md-7">
        <h2 class="fw-bold">{{ $product->name }}</h2>
        <p class="text-muted">
            Thương hiệu: <strong>{{ $product->brand }}</strong>
            &nbsp;|&nbsp;
            Danh mục: <a href="{{ route('category', $product->category->slug) }}">
                {{ $product->category->name }}
            </a>
        </p>

        {{-- Giá --}}
        <div class="mb-3">
            @if($product->sale_price)
                <span class="fs-3 text-danger fw-bold">
                    {{ number_format($product->sale_price, 0, ',', '.') }}đ
                </span>
                <del class="text-muted ms-2 fs-5">
                    {{ number_format($product->price, 0, ',', '.') }}đ
                </del>
                <span class="badge bg-danger ms-2 fs-6">
                    -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                </span>
            @else
                <span class="fs-3 text-danger fw-bold">
                    {{ number_format($product->price, 0, ',', '.') }}đ
                </span>
            @endif
        </div>

        {{-- Tồn kho --}}
        <p>
            Tình trạng:
            @if($product->stock > 0)
                <span class="badge bg-success fs-6">Còn hàng ({{ $product->stock }})</span>
            @else
                <span class="badge bg-danger fs-6">Hết hàng</span>
            @endif
        </p>

        {{-- Form mua hàng --}}
        @if($product->stock > 0)
        <form method="POST" action="{{ route('cart.add', $product) }}">
            @csrf

            {{-- Input hidden lưu lựa chọn - PHẢI NẰM TRONG FORM --}}
            <input type="hidden" name="selected_color"   id="selected_color_input"   value="">
            <input type="hidden" name="selected_version" id="selected_version_input" value="">

            {{-- Màu sắc --}}
            @if($product->colors && count($product->colors) > 0)
            <div class="mb-3">
                <label class="fw-bold">Màu sắc:</label>
                <div class="d-flex gap-2 flex-wrap mt-1">
                    @foreach($product->colors as $color)
                    <button type="button"
                            class="btn btn-outline-secondary btn-sm color-btn"
                            data-value="{{ $color }}"
                            onclick="selectOption(this, 'color')">
                        {{ $color }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Phiên bản --}}
            @if($product->versions && count($product->versions) > 0)
            <div class="mb-3">
                <label class="fw-bold">Phiên bản:</label>
                <div class="d-flex gap-2 flex-wrap mt-1">
                    @foreach($product->versions as $version)
                    <button type="button"
                            class="btn btn-outline-secondary btn-sm version-btn"
                            data-value="{{ $version }}"
                            onclick="selectOption(this, 'version')">
                        {{ $version }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Số lượng --}}
            <div class="d-flex gap-2 align-items-center mb-3">
                <label class="fw-bold">Số lượng:</label>
                <div class="input-group" style="width:130px">
                    <button type="button" class="btn btn-outline-secondary"
                            onclick="changeQty(-1)">−</button>
                    <input type="number" name="quantity" id="qty"
                           value="1" min="1" max="{{ $product->stock }}"
                           class="form-control text-center">
                    <button type="button" class="btn btn-outline-secondary"
                            onclick="changeQty(1)">+</button>
                </div>
            </div>

            {{-- Nút mua --}}
            <div class="d-flex gap-2">
                <button type="submit" name="action" value="cart"
                        class="btn btn-primary btn-lg px-4">
                    🛒 Thêm vào giỏ
                </button>
                <button type="submit" name="action" value="buynow"
                        class="btn btn-danger btn-lg px-4">
                    ⚡ Mua ngay
                </button>
            </div>
        </form>
        @else
            <button class="btn btn-secondary btn-lg" disabled>Hết hàng</button>
        @endif

        {{-- Mô tả --}}
        <div class="mt-4 p-3 bg-light rounded">
            <h5 class="fw-bold">Mô tả sản phẩm</h5>
            <p class="mb-0" style="white-space: pre-line">{{ $product->description }}</p>
        </div>
    </div>
</div>

{{-- Sản phẩm liên quan --}}
@if($related->count() > 0)
<div class="mt-5">
    <h4 class="fw-bold mb-3">Sản phẩm liên quan</h4>
    <div class="row g-3">
        @foreach($related as $item)
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <img src="{{ asset('storage/' . $item->image) }}"
                     class="card-img-top"
                     style="height:250px; object-fit:cover;"
                     onerror="this.src='https://placehold.co/300x180?text=No+Image'"
                     alt="{{ $item->name }}">
                <div class="card-body">
                    <h6 class="card-title">{{ $item->name }}</h6>
                    @if($item->sale_price)
                        <span class="text-danger fw-bold">
                            {{ number_format($item->sale_price, 0, ',', '.') }}đ
                        </span>
                        <del class="text-muted small ms-1">
                            {{ number_format($item->price, 0, ',', '.') }}đ
                        </del>
                    @else
                        <p class="text-danger fw-bold mb-0">
                            {{ number_format($item->price, 0, ',', '.') }}đ
                        </p>
                    @endif
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                    <a href="{{ route('products.show', $item->slug) }}"
                       class="btn btn-outline-primary w-100 btn-sm">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Có thể bạn cũng thích --}}
@php
    $suggested = \App\Models\Product::where('is_active', true)
        ->where('id', '!=', $product->id)
        ->inRandomOrder()
        ->take(4)
        ->get();
@endphp

@if($suggested->count() > 0)
<div class="mt-5">
    <h4 class="fw-bold mb-4">💡 Có thể bạn cũng thích</h4>
    <div class="row g-3">
        @foreach($suggested as $item)
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                @if($item->sale_price)
                <span class="badge bg-danger position-absolute m-2" style="z-index:1">
                    -{{ round((1 - $item->sale_price / $item->price) * 100) }}%
                </span>
                @endif
                <img src="{{ asset('storage/' . $item->image) }}"
                     class="card-img-top"
                     style="height:250px; object-fit:cover;"
                     onerror="this.src='https://placehold.co/300x180?text=No+Image'"
                     alt="{{ $item->name }}">
                <div class="card-body">
                    <p class="text-muted small mb-1">{{ $item->brand }}</p>
                    <h6 class="card-title">{{ $item->name }}</h6>
                    @if($item->sale_price)
                        <span class="text-danger fw-bold">
                            {{ number_format($item->sale_price, 0, ',', '.') }}đ
                        </span>
                        <del class="text-muted small ms-1">
                            {{ number_format($item->price, 0, ',', '.') }}đ
                        </del>
                    @else
                        <span class="text-danger fw-bold">
                            {{ number_format($item->price, 0, ',', '.') }}đ
                        </span>
                    @endif
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                    <a href="{{ route('products.show', $item->slug) }}"
                       class="btn btn-outline-primary w-100 btn-sm">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Scripts --}}
<script>
function selectOption(btn, type) {
    document.querySelectorAll('.' + type + '-btn').forEach(b => {
        b.classList.remove('btn-dark', 'active');
        b.classList.add('btn-outline-secondary');
    });
    btn.classList.remove('btn-outline-secondary');
    btn.classList.add('btn-dark', 'active');

    // Lưu vào input hidden trong form
    document.getElementById('selected_' + type + '_input').value = btn.dataset.value;
}

function changeQty(delta) {
    const input = document.getElementById('qty');
    const max   = parseInt(input.max);
    const min   = parseInt(input.min);
    let val     = parseInt(input.value) + delta;
    if (val < min) val = min;
    if (val > max) val = max;
    input.value = val;
}
</script>
@endsection