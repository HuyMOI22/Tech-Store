@extends('admin.layouts.app')
@section('title', 'Quản lý đơn hàng')
@section('content')

<div class="row g-3 mb-4">
    @foreach([
        ['pending',   'Chờ xác nhận', 'warning'],
        ['confirmed', 'Đã xác nhận',  'primary'],
        ['shipping',  'Đang giao',    'info'],
        ['delivered', 'Đã giao',      'success'],
        ['cancelled', 'Đã hủy',       'danger'],
    ] as [$status, $label, $color])
    <div class="col">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-2">
                <p class="mb-0 small text-muted">{{ $label }}</p>
                <h4 class="fw-bold text-{{ $color }} mb-0">
                    {{ $orders->where('status', $status)->count() }}
                </h4>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="fw-bold mb-0">Danh sách đơn hàng</h5>
        <form method="GET" action="/admin/orders" class="d-flex gap-2">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="text"
               name="search"
               class="form-control"
               style="width:280px"
               placeholder="🔍 Tìm đơn hàng..."
               value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Tìm</button>
        @if(request('search'))
        <a href="/admin/orders?status={{ request('status') }}"
           class="btn btn-outline-secondary">✕ Xóa</a>
        @endif
    </form>
        <div class="d-flex gap-2 flex-wrap">
            @foreach([
                [''          , 'Tất cả'],
                ['pending'   , 'Chờ xác nhận'],
                ['confirmed' , 'Đã xác nhận'],
                ['shipping'  , 'Đang giao'],
                ['delivered' , 'Đã giao'],
                ['cancelled' , 'Đã hủy'],
            ] as [$val, $label])
            <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
               class="btn btn-sm {{ request('status') == $val ? 'btn-primary' : 'btn-outline-secondary' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_code }}</strong></td>
                    <td>{{ $order->shipping_name }}</td>
                    <td>{{ $order->shipping_phone }}</td>
                    <td class="text-danger fw-bold">
                        {{ number_format($order->total_amount, 0, ',', '.') }}đ
                    </td>
                    <td>
                        <span class="badge bg-secondary">
                            {{ [
                                'cod'     => 'COD',
                                'banking' => 'Chuyển khoản',
                                'vnpay'   => 'VNPay',
                                'momo'    => 'Momo',
                            ][$order->payment_method] ?? $order->payment_method }}
                        </span>
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success">Đã TT</span>
                        @endif
                    </td>
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
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="/admin/orders/{{ $order->id }}"
                           class="btn btn-sm btn-primary">Chi tiết</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        Chưa có đơn hàng nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection