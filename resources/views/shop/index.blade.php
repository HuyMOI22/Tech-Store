@extends('layouts.app')
@section('title', 'Tất cả sản phẩm')
@section('content')
@section('content')
<div class="row g-4">
    {{-- Sidebar danh mục --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">📦 Danh mục</h6>
                <a href="{{ route('products.index', request()->except('category')) }}"
                   class="d-block py-2 px-4 rounded mb-1 text-decoration-none
                          {{ !request('category') ? 'bg-primary text-white' : 'text-dark' }}">
                    Tất cả sản phẩm
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('products.index', array_merge(request()->except('category'), ['category' => $cat->slug])) }}"
                   class="d-block py-2 px-4 rounded mb-1 text-decoration-none
                          {{ request('category') == $cat->slug ? 'bg-primary text-white' : 'text-dark' }}">
                    {{ $cat->name }}
                    <span class="badge bg-secondary float-end">{{ $cat->products_count }}</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Lọc giá --}}
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">💰 Lọc theo giá</h6>
                <form method="GET" action="{{ route('products.index') }}">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <div class="mb-2">
                        <select name="price" class="form-select form-select-sm"
                                onchange="this.form.submit()">
                            <option value="">Tất cả mức giá</option>
                            <option value="under_100"
                                {{ request('price') == 'under_100' ? 'selected' : '' }}>
                                Dưới 100.000đ
                            </option>
                            <option value="100_500"
                                {{ request('price') == '100_500' ? 'selected' : '' }}>
                                100.000đ - 500.000đ
                            </option>
                            <option value="500_1000"
                                {{ request('price') == '500_1000' ? 'selected' : '' }}>
                                500.000đ - 1.000.000đ
                            </option>
                            <option value="over_1000"
                                {{ request('price') == 'over_1000' ? 'selected' : '' }}>
                                Trên 1.000.000đ
                            </option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Danh sách sản phẩm --}}
    <div class="col-md-9">

        {{-- Tag tìm kiếm --}}
        @if(request('search'))
        <div class="mb-3">
            <span class="badge bg-primary fs-6 py-2 px-3">
                🔍 "{{ request('search') }}"
                <a href="{{ route('products.index') }}" class="text-white ms-2 text-decoration-none">✕</a>
            </span>
        </div>
        @endif

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">
                @if(request('search'))
                    Kết quả tìm kiếm
                @elseif(request('category'))
                    {{ $categories->where('slug', request('category'))->first()?->name ?? 'Danh mục' }}
                @else
                    Tất cả sản phẩm
                @endif
                <span class="text-muted fs-6 fw-normal">
                    ({{ $products->total() }} sản phẩm)
                </span>
            </h5>

            {{-- Sắp xếp --}}
            <select class="form-select form-select-sm" style="width:auto"
                    onchange="window.location=this.value">
                <option value="{{ request()->fullUrlWithQuery(['sort' => '']) }}">
                    Mặc định
                </option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}"
                    {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                    Giá tăng dần
                </option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}"
                    {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                    Giá giảm dần
                </option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
                    {{ request('sort') == 'newest' ? 'selected' : '' }}>
                    Mới nhất
                </option>
            </select>
        </div>

        {{-- Grid sản phẩm --}}
        <div class="row g-3">
            @forelse($products as $product)
            <div class="col-6 col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    @if($product->sale_price)
                        <span class="badge bg-danger position-absolute m-2" style="z-index:1">
                            -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                        </span>
                    @endif
                    <img src="{{ asset('storage/' . $product->image) }}"
                         class="card-img-top"
                         style="height:200px; object-fit:cover;"
                         onerror="this.src='https://placehold.co/300x200?text=No+Image'"
                         alt="{{ $product->name }}">
                    <div class="card-body">
                        <p class="text-muted small mb-1">{{ $product->brand }}</p>
                        <h6 class="card-title">{{ $product->name }}</h6>
                        @if($product->sale_price)
                            <div>
                                <span class="text-danger fw-bold">
                                    {{ number_format($product->sale_price, 0, ',', '.') }}đ
                                </span>
                                <del class="text-muted small ms-1">
                                    {{ number_format($product->price, 0, ',', '.') }}đ
                                </del>
                            </div>
                        @else
                            <span class="text-danger fw-bold">
                                {{ number_format($product->price, 0, ',', '.') }}đ
                            </span>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <a href="{{ route('products.show', $product->slug) }}"
                           class="btn btn-primary w-100 btn-sm">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <div class="fs-1">😕</div>
                @if(request('search'))
                    <p>Không tìm thấy sản phẩm nào với từ khóa <strong>"{{ request('search') }}"</strong></p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        Xem tất cả sản phẩm
                    </a>
                @else
                    <p>Không có sản phẩm nào!</p>
                @endif
            </div>
            @endforelse
        </div>

        {{-- Phân trang --}}
        @if($products->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $products->withQueryString()->links('vendor.pagination.bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection