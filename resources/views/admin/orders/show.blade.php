@extends('admin.layouts.app')
@section('title', 'Chi tiết đơn hàng')
@section('content')

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">{{ $order->order_code }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
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
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success ms-1">Đã thanh toán</span>
                            @else
                                <span class="badge bg-secondary ms-1">Chưa thanh toán</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Ngày đặt</strong></td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
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

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0">Sản phẩm đã đặt</h6>
            </div>
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>SL</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-danger fw-bold">
                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                        <td class="text-danger fw-bold fs-5">
                            {{ number_format($order->total_amount, 0, ',', '.') }}đ
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0">Cập nhật trạng thái</h6>
            </div>
            <div class="card-body">
                @php
                    $steps = [
                        'pending'   => ['Chờ xác nhận', 'bi-clock',         'warning'],
                        'confirmed' => ['Đã xác nhận',  'bi-check-circle',  'primary'],
                        'shipping'  => ['Đang giao',    'bi-truck',         'info'],
                        'delivered' => ['Đã giao',      'bi-house-check',   'success'],
                        'cancelled' => ['Đã hủy',       'bi-x-circle',      'danger'],
                    ];
                @endphp

                <div class="mb-4">
                    @foreach($steps as $key => [$label, $icon, $color])
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi {{ $icon }} text-{{ $color }} me-2 fs-5"></i>
                        <span class="{{ $order->status == $key ? 'fw-bold' : 'text-muted' }}">
                            {{ $label }}
                            @if($order->status == $key)
                                <span class="badge bg-{{ $color }} ms-1">Hiện tại</span>
                            @endif
                        </span>
                    </div>
                    @endforeach
                </div>

                @if(!in_array($order->status, ['delivered', 'cancelled']))
                <form method="POST" action="/admin/orders/{{ $order->id }}/status">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chuyển sang trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="pending"   {{ $order->status == 'pending'   ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="shipping"  {{ $order->status == 'shipping'  ? 'selected' : '' }}>Đang giao</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Hủy đơn</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        Cập nhật trạng thái
                    </button>
                </form>
                @else
                <div class="alert {{ $order->status == 'delivered' ? 'alert-success' : 'alert-danger' }} mb-0">
                    {{ $order->status == 'delivered' ? '✅ Đơn hàng đã giao thành công!' : '❌ Đơn hàng đã bị hủy!' }}
                </div>
                @endif

                <a href="/admin/orders" class="btn btn-outline-secondary w-100 mt-2">
                    ← Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
    {{-- Cập nhật trạng thái thanh toán --}}
<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-white">
        <h6 class="fw-bold mb-0">💳 Trạng thái thanh toán</h6>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span>Hiện tại:</span>
            @if($order->payment_status == 'paid')
                <span class="badge bg-success fs-6">✅ Đã thanh toán</span>
            @else
                <span class="badge bg-secondary fs-6">⏳ Chưa thanh toán</span>
            @endif
        </div>

        <form method="POST" action="/admin/orders/{{ $order->id }}/payment">
            @csrf @method('PATCH')
            <div class="mb-3">
                <label class="form-label fw-bold">Chuyển sang</label>
                <select name="payment_status" class="form-select">
                    <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>
                        ⏳ Chưa thanh toán
                    </option>
                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>
                        ✅ Đã thanh toán
                    </option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100"
                    onclick="return confirm('Xác nhận cập nhật trạng thái thanh toán?')">
                Cập nhật thanh toán
            </button>
        </form>
    </div>
</div>
</div>
@endsection