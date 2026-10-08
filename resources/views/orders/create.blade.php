@extends('layouts.app')
@section('title', 'Đặt hàng')
@section('content')
<h3 class="fw-bold mb-4">Thông tin đặt hàng</h3>
<div class="row g-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Thông tin giao hàng</h5>
                <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ tên người nhận *</label>
                        <input type="text" name="shipping_name" class="form-control"
                               value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Số điện thoại *</label>
                        <input type="text" name="shipping_phone" class="form-control"
                               placeholder="0912345678" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Địa chỉ giao hàng *</label>
                        <textarea name="shipping_address" class="form-control" rows="3"
                                  placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành"
                                  required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ghi chú</label>
                        <textarea name="note" class="form-control" rows="2"
                                  placeholder="Ghi chú thêm (nếu có)"></textarea>
                    </div>
                    <h5 class="fw-bold mb-3 mt-4">Phương thức thanh toán</h5>
                    <div class="row g-2">
                        <div class="col-3">
                            <input type="radio" class="btn-check" name="payment_method"
                                   id="cod" value="cod" checked>
                            <label class="btn btn-outline-secondary w-100 py-3" for="cod">
                                🚚<br><small>Tiền mặt (COD)</small>
                            </label>
                        </div>
                        <div class="col-3">
                            <input type="radio" class="btn-check" name="payment_method"
                                   id="vnpay" value="vnpay">
                            <label class="btn btn-outline-secondary w-100 py-3" for="vnpay">
                                💳<br><small>VNPay</small>
                            </label>
                        </div>
                        <div class="col-3">
                            <input type="radio" class="btn-check" name="payment_method"
                                   id="banking" value="banking">
                            <label class="btn btn-outline-secondary w-100 py-3" for="banking">
                                🏦<br><small>Chuyển khoản</small>
                            </label>
                        </div>
                        <div class="col-3">
                            <input type="radio" class="btn-check" name="payment_method"
                                   id="momo" value="momo">
                            <label class="btn btn-outline-secondary w-100 py-3" for="momo">
                                💜<br><small>Momo</small>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Đơn hàng của bạn</h5>
                @foreach($items as $item)
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ $item->product->name }}
                        <span class="badge bg-secondary">x{{ $item->quantity }}</span>
                    </span>
                    <span class="text-danger fw-bold">
                        {{ number_format($item->product->final_price * $item->quantity, 0, ',', '.') }}đ
                    </span>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Tổng cộng:</span>
                    <span class="text-danger">{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>
                <button type="submit" form="orderForm"
                        class="btn btn-danger w-100 mt-3 btn-lg fw-bold">
                    🛍️ Xác nhận đặt hàng
                </button>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                    ← Quay lại giỏ hàng
                </a>
            </div>
        </div>
    </div>
</div>
@endsection