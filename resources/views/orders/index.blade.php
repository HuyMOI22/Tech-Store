@extends('layouts.app')
@section('title', 'Đơn hàng của tôi')
@section('content')
<h3 class="fw-bold mb-4">📦 Đơn hàng của tôi</h3>

@forelse($orders as $order)
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-1">{{ $order->order_code }}</h6>
                <small class="text-muted">
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </small>
                <div class="mt-1">
                    <small class="text-muted">
                        {{ [
                            'cod'     => '🚚 Tiền mặt (COD)',
                            'banking' => '🏦 Chuyển khoản',
                            'vnpay'   => '💳 VNPay',
                            'momo'    => '💜 Momo',
                        ][$order->payment_method] ?? $order->payment_method }}
                    </small>
                </div>
            </div>
            <div class="text-end">
                @php
                    $statusMap = [
                        'pending'   => ['Chờ xác nhận', 'warning'],
                        'confirmed' => ['Đã xác nhận',  'primary'],
                        'shipping'  => ['Đang giao',    'info'],
                        'delivered' => ['Đã giao',      'success'],
                        'cancelled' => ['Đã hủy',       'danger'],
                    ];
                @endphp
                <span class="badge bg-{{ $statusMap[$order->status][1] }}">
                    {{ $statusMap[$order->status][0] }}
                </span>
                @if($order->payment_status == 'paid')
                    <span class="badge bg-success ms-1">Đã thanh toán</span>
                @else
                    <span class="badge bg-secondary ms-1">Chưa thanh toán</span>
                @endif
                <p class="text-danger fw-bold mb-0 mt-1 fs-5">
                    {{ number_format($order->total_amount, 0, ',', '.') }}đ
                </p>
            </div>
        </div>
        <div class="mt-2 pt-2 border-top d-flex gap-2">
            <a href="{{ route('orders.show', $order) }}"
               class="btn btn-sm btn-primary">
                Xem chi tiết
            </a>
            @if($order->payment_method == 'vnpay' && $order->payment_status == 'unpaid')
            <form method="POST"
                  action="{{ route('vnpay.create', $order) }}"
                  class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">
                    💳 Thanh toán VNPay
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@empty
<div class="text-center py-5 text-muted">
    <div class="fs-1">📦</div>
    <p>Bạn chưa có đơn hàng nào</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Mua sắm ngay</a>
</div>
@endforelse
@endsection