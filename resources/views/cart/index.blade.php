@extends('layouts.app')
@section('title', 'Giỏ hàng')
@section('content')
<h3 class="fw-bold mb-4">🛒 Giỏ hàng của bạn</h3>

@if($items->count() > 0)
<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                     width="60" height="60"
                                     style="object-fit:cover; border-radius:8px"
                                     onerror="this.src='https://placehold.co/60x60'">
                                <div>
                                    <p class="mb-0 fw-bold">{{ $item->product->name }}</p>
                                    <small class="text-muted">{{ $item->product->brand }}</small>
                                </div>
                                <div>
    <p class="mb-0 fw-bold">{{ $item->product->name }}</p>
    @if($item->color || $item->version)
    <small class="text-muted">
        @if($item->color) 🎨 {{ $item->color }} @endif
        @if($item->version) 📦 {{ $item->version }} @endif
    </small>
    @endif
    <small class="text-muted">{{ $item->product->brand }}</small>
</div>
                            </div>
                        </td>
                        <td class="text-danger fw-bold">
                            {{ number_format($item->product->final_price, 0, ',', '.') }}đ
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-danger fw-bold">
                            {{ number_format($item->product->final_price * $item->quantity, 0, ',', '.') }}đ
                        </td>
                        <td>
                            <form method="POST" action="{{ route('cart.remove', $item) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">✕</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold">Tổng đơn hàng</h5>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tạm tính:</span>
                    <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Phí ship:</span>
                    <span class="text-success">Miễn phí</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Tổng cộng:</span>
                    <span class="text-danger">{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>
                <a href="{{ route('orders.create') }}" class="btn btn-danger w-100 mt-3 btn-lg">
                    Đặt hàng ngay
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>
@else
<div class="text-center py-5">
    <div class="fs-1">🛒</div>
    <h4 class="text-muted">Giỏ hàng trống!</h4>
    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
        Mua sắm ngay
    </a>
</div>
@endif
@endsection