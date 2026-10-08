@extends('layouts.app')
@section('title', $category->name)
@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item active">{{ $category->name }}</li>
    </ol>
</nav>

{{-- Header danh mục --}}
<div class="bg-primary text-white rounded-3 p-4 mb-4">
    <h3 class="fw-bold mb-1">📦 {{ $category->name }}</h3>
    <p class="mb-0 opacity-75">{{ $products->total() }} sản phẩm</p>
</div>

{{-- Grid sản phẩm --}}
<div class="row g-3">
    @forelse($products as $product)
    <div class="col-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            @if($product->sale_price)
                <span class="badge bg-danger position-absolute m-2">
                    -{{ round((1 - $product->sale_price/$product->price) * 100) }}%
                </span>
            @endif
            <img src="{{ asset('storage/' . $product->image) }}"
                 class="card-img-top"
                 style="height:180px; object-fit:cover;"
                 onerror="this.src='https://placehold.co/300x180?text=No+Image'"
                 alt="{{ $product->name }}">
            <div class="card-body">
                <p class="text-muted small mb-1">{{ $product->brand }}</p>
                <h6 class="card-title">{{ $product->name }}</h6>
                @if($product->sale_price)
                    <span class="text-danger fw-bold">
                        {{ number_format($product->sale_price, 0, ',', '.') }}đ
                    </span>
                    <del class="text-muted small ms-1">
                        {{ number_format($product->price, 0, ',', '.') }}đ
                    </del>
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
        <p>Danh mục này chưa có sản phẩm nào!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Xem tất cả sản phẩm</a>
    </div>
    @endforelse
</div>

{{-- Phân trang --}}
<div class="mt-4 d-flex justify-content-center">
    {{ $products->links() }}
</div>
@endsection