@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    {{-- Banner Slider --}}
<div id="bannerSlider" class="carousel slide mb-4 rounded-3 overflow-hidden shadow"
     data-bs-ride="carousel" data-bs-interval="4000">

    {{-- Indicators --}}
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#bannerSlider" data-bs-slide-to="0"
                class="active" aria-current="true"></button>
        <button type="button" data-bs-target="#bannerSlider" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#bannerSlider" data-bs-slide-to="2"></button>
    </div>

    {{-- Slides --}}
    <div class="carousel-inner" style="height:650px">

        {{-- Slide 1 - Banner chính --}}
        <div class="carousel-item active h-100">
            <img src="{{ asset('images/vua-phu-kien-bao-da-ipad-lung-trong-suot-banner.jpg') }}"
                 class="d-block w-100 h-100"
                 style="object-fit:cover; object-position:center"
                 alt="Banner 1">
            
        </div>

        {{-- Slide 2 --}}
        <div class="carousel-item h-100">
            <img src="{{ asset('images/Vua-Phu-Kien-dien-thoai-Thai-Ha-Ha-Noi.jpg') }}"
                 class="d-block w-100 h-100"
                 style="object-fit:cover; object-position:center"
                 alt="Banner 2">
        </div>

        {{-- Slide 3 --}}
        <div class="carousel-item h-100">
            <img src="{{ asset('images/vuaphukien-vua-phu-kien-bao-da-kem-ban-phim-ipad-may-tinh-bang-cap-cap.jpg') }}"
                 class="d-block w-100 h-100"
                 style="object-fit:cover; object-position:center"
                 alt="Banner 3">
        </div>

    </div>

    {{-- Controls --}}
    <button class="carousel-control-prev" type="button"
            data-bs-target="#bannerSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button"
            data-bs-target="#bannerSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

    {{-- Danh mục --}}
    @if($categories->count() > 0)
    <h3 class="mb-3">Danh mục sản phẩm</h3>
    <div class="row g-3 mb-5">
        @foreach($categories as $category)
        <div class="col-6 col-md-3">
            <a href="{{ route('category', $category->slug) }}"
               class="card text-center text-decoration-none h-100 border-0 shadow-sm">
                <div class="card-body">
    <p class="fw-bold mb-0">{{ $category->name }}</p>
</div>
            </a>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Sản phẩm mới nhất --}}
    <h3 class="mb-3">Sản phẩm mới nhất</h3>
    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-6 col-md-3">
            <div class="card h-100 shadow-sm border-0">
                <img src="{{ asset('storage/' . $product->image) }}"
                     class="card-img-top"
                     style="height:200px; object-fit:cover;"
                     onerror="this.src='https://placehold.co/300x200?text=No+Image'"
                     alt="{{ $product->name }}">
                <div class="card-body">
                    <h6 class="card-title">{{ $product->name }}</h6>
                    <p class="text-muted small">{{ $product->brand }}</p>
                    @if($product->sale_price)
                        <span class="text-danger fw-bold">
                            {{ number_format($product->sale_price, 0, ',', '.') }}đ
                        </span>
                        <del class="text-muted small ms-1">
                            {{ number_format($product->price, 0, ',', '.') }}đ
                        </del>
                    @else
                        <p class="text-danger fw-bold mb-0">
                            {{ number_format($product->price, 0, ',', '.') }}đ
                        </p>
                    @endif
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="{{ route('products.show', $product->slug) }}"
                       class="btn btn-primary w-100 btn-sm">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">
            <p>Chưa có sản phẩm nào!</p>
            <a href="/admin/products/create" class="btn btn-success">
                + Thêm sản phẩm đầu tiên
            </a>
        </div>
        @endforelse
    </div>
@endsection