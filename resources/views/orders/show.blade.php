@extends('layouts.app')
@section('title', 'Chi tiết đơn hàng')
@section('content')

<div class="text-center mb-4">
    <div class="fs-1">✅</div>
    <h3 class="fw-bold text-success">Đặt hàng thành công!</h3>
    <p class="text-muted">Mã đơn hàng: <strong>{{ $order->order_code }}</strong></p>
</div>

<div class="row g-4 justify-content-center">
    <div class="col-md-8">

        {{-- Thông tin đơn hàng --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5 class="fw-bold mb-3">📋 Thông tin đơn hàng</h5>
                <table class="table table-sm mb-0">
                    <tr>
                        <td width="150"><strong>Người nhận</strong></td>
                        <td>{{ $order->shipping_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Điện thoại</strong></td>
                        <td>{{ $order->shipping_phone }}</td>
                    </tr>
                    <tr>
                        <td><strong>Địa chỉ</strong></td>
                        <td>{{ $order->shipping_address }}</td>
                    </tr>
                    <tr>
                        <td><strong>Thanh toán</strong></td>
                        <td>
                            {{ [
                                'cod'     => 'Tiền mặt (COD)',
                                'banking' => 'Chuyển khoản',
                                'vnpay'   => 'VNPay',
                                'momo'    => 'Momo',
                            ][$order->payment_method] ?? $order->payment_method }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Trạng thái</strong></td>
                        <td>
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
                        </td>
                    </tr>
                    @if($order->note)
                    <tr>
                        <td><strong>Ghi chú</strong></td>
                        <td>{{ $order->note }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        @foreach($order->items as $item)
<div class="d-flex justify-content-between align-items-center py-2 border-bottom">
    <div>
        <p class="mb-0 fw-bold">{{ $item->product_name }}</p>
        {{-- Hiển thị màu sắc và phiên bản --}}
        @if($item->color || $item->version)
        <small class="text-muted">
            @if($item->color)
                🎨 {{ $item->color }}
            @endif
            @if($item->version)
                📦 {{ $item->version }}
            @endif
        </small>
        @endif
        <small class="text-muted d-block">
            {{ number_format($item->price, 0, ',', '.') }}đ
            x {{ $item->quantity }}
        </small>
    </div>
    <span class="text-danger fw-bold">
        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
    </span>
</div>
@endforeach

        {{-- Đã thanh toán --}}
        @if($order->payment_status == 'paid')
        <div class="alert alert-success text-center fw-bold">
            ✅ Đã thanh toán thành công!
        </div>
        @endif

        {{-- VNPay chưa thanh toán --}}
        @if($order->payment_method == 'vnpay' && $order->payment_status == 'unpaid')
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5 class="fw-bold text-danger mb-3">💳 Thanh toán VNPay</h5>
                <div class="alert alert-warning">
                    ⚠️ Đơn hàng chưa được thanh toán!
                    Vui lòng thanh toán để xác nhận đơn hàng.
                </div>
                <form method="POST" action="{{ route('vnpay.create', $order) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 btn-lg">
                        💳 Thanh toán ngay qua VNPay
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Chuyển khoản --}}
        @if($order->payment_method == 'banking' && $order->payment_status == 'unpaid')
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5 class="fw-bold text-primary mb-3">🏦 Thông tin chuyển khoản</h5>
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="alert alert-info mb-3">
                            <p class="mb-2"><strong>Ngân hàng:</strong> MBBank</p>
                            <p class="mb-2"><strong>Số tài khoản:</strong>
                                <span class="fw-bold fs-5">552529122005</span>
                            </p>
                            <p class="mb-2"><strong>Chủ tài khoản:</strong> NGUYEN DUC HUY</p>
                            <p class="mb-2"><strong>Số tiền:</strong>
                                <span class="text-danger fw-bold fs-5">
                                    {{ number_format($order->total_amount, 0, ',', '.') }}đ
                                </span>
                            </p>
                            <p class="mb-0"><strong>Nội dung CK:</strong>
                                <span class="text-danger fw-bold">
                                    {{ $order->order_code }}
                                </span>
                            </p>
                        </div>
                        <div class="alert alert-warning mb-0">
                            ⚠️ Chuyển khoản đúng số tiền và nội dung.
                            Đơn hàng xác nhận trong <strong>1-2 giờ</strong>.
                        </div>
                    </div>
                    <div class="col-md-6 text-center mt-3 mt-md-0">
                        <p class="fw-bold mb-2">📱 Quét mã QR VietQR</p>
                        <img src="https://img.vietqr.io/image/MB-552529122005-compact2.png?amount={{ (int)$order->total_amount }}&addInfo={{ urlencode($order->order_code) }}&accountName=NGUYEN+DUC+HUY"
                             class="img-fluid rounded shadow border"
                             style="max-width:220px"
                             alt="QR Chuyển khoản">
                        <p class="text-muted small mt-2">
                            Hỗ trợ tất cả ngân hàng Việt Nam
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Momo --}}
        @if($order->payment_method == 'momo' && $order->payment_status == 'unpaid')
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5 class="fw-bold mb-3" style="color:#ae2070">💜 Thanh toán qua Momo</h5>
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="p-3 rounded mb-3"
                             style="background:#fff0f6; border:2px solid #ae2070">
                            <p class="mb-2"><strong>Số điện thoại:</strong>
                                <span class="fw-bold fs-5" style="color:#ae2070">
                                    0358294903
                                </span>
                            </p>
                            <p class="mb-2"><strong>Tên:</strong> NGUYEN DUC HUY</p>
                            <p class="mb-2"><strong>Số tiền:</strong>
                                <span class="text-danger fw-bold fs-5">
                                    {{ number_format($order->total_amount, 0, ',', '.') }}đ
                                </span>
                            </p>
                            <p class="mb-0"><strong>Nội dung:</strong>
                                <span class="fw-bold" style="color:#ae2070">
                                    {{ $order->order_code }}
                                </span>
                            </p>
                        </div>
                        <div class="p-3 rounded" style="background:#f8f8f8">
                            <p class="fw-bold mb-2">📱 Cách thanh toán:</p>
                            <ol class="mb-0 small">
                                <li>Mở app <strong>Momo</strong></li>
                                <li>Chọn <strong>Chuyển tiền</strong></li>
                                <li>Nhập SĐT:
                                    <strong style="color:#ae2070">0358294903</strong>
                                </li>
                                <li>Nhập đúng số tiền</li>
                                <li>Nội dung:
                                    <strong style="color:#ae2070">
                                        {{ $order->order_code }}
                                    </strong>
                                </li>
                                <li>Xác nhận thanh toán</li>
                            </ol>
                        </div>
                        <div class="alert alert-warning mt-3 mb-0">
                            ⚠️ Đơn hàng xác nhận trong
                            <strong>1-2 giờ</strong> sau khi nhận tiền.
                        </div>
                    </div>
                    <div class="col-md-6 text-center mt-3 mt-md-0">
                        <p class="fw-bold mb-2">📱 Quét mã QR Momo</p>
                        <img src="https://quickchart.io/qr?text={{ urlencode('2|99|0358294903|NGUYEN DUC HUY|' . $order->order_code . '|0|0|' . (int)$order->total_amount) }}&size=220&margin=2"
                             class="rounded shadow border"
                             width="220"
                             height="220"
                             alt="QR Momo">
                        <p class="text-muted small mt-2">
                            Mở app Momo → Quét mã QR → Xác nhận
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Nút hành động --}}
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('orders.index') }}"
               class="btn btn-outline-primary flex-fill">
                📦 Xem đơn hàng của tôi
            </a>
            <a href="{{ route('home') }}"
               class="btn btn-primary flex-fill">
                🛍️ Tiếp tục mua sắm
            </a>
        </div>

    </div>
</div>
@endsection