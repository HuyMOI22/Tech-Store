@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')

{{-- Chọn năm --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">📊 Thống kê năm {{ $year }}</h5>
    <div class="d-flex gap-2 align-items-center">
        <form method="GET" action="/admin/dashboard" class="d-flex gap-2">
            <select name="year" class="form-select form-select-sm" style="width:100px">
                @foreach($years as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <select name="month" class="form-select form-select-sm" style="width:120px">
                <option value="">Cả năm</option>
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                    Tháng {{ $m }}
                </option>
                @endfor
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Xem</button>
        </form>

        {{-- Nút xuất Excel --}}
        <a href="{{ route('admin.export.revenue', ['year' => $year, 'month' => request('month')]) }}"
   class="btn btn-success btn-sm">
    📥 Xuất Excel
</a>
    </div>
</div>

{{-- Thống kê tổng quan --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-1 opacity-75 small">Tổng doanh thu</p>
                        <h4 class="fw-bold mb-0">
                            {{ number_format($stats['revenue'], 0, ',', '.') }}đ
                        </h4>
                    </div>
                    <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-1 opacity-75 small">Tổng đơn hàng</p>
                        <h4 class="fw-bold mb-0">{{ $stats['orders'] }}</h4>
                    </div>
                    <i class="bi bi-cart fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-1 opacity-75 small">Chờ xử lý</p>
                        <h4 class="fw-bold mb-0">{{ $stats['pending'] }}</h4>
                    </div>
                    <i class="bi bi-clock fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-1 opacity-75 small">Sản phẩm</p>
                        <h4 class="fw-bold mb-0">{{ $stats['products'] }}</h4>
                    </div>
                    <i class="bi bi-box fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Biểu đồ doanh thu --}}
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                📈 Doanh thu theo tháng ({{ $year }})
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                🥧 Trạng thái đơn hàng
            </div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Top sản phẩm --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                🏆 Top 5 sản phẩm bán chạy ({{ $year }})
            </div>
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $i => $product)
                    <tr>
                        <td>
                            @if($i == 0) 🥇
                            @elseif($i == 1) 🥈
                            @elseif($i == 2) 🥉
                            @else {{ $i + 1 }}
                            @endif
                        </td>
                        <td>{{ $product->product_name }}</td>
                        <td><span class="badge bg-primary">{{ $product->total_qty }}</span></td>
                        <td class="text-danger fw-bold">
                            {{ number_format($product->total_revenue, 0, ',', '.') }}đ
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">
                            Chưa có dữ liệu
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Doanh thu theo thanh toán --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                💳 Doanh thu theo thanh toán
            </div>
            <div class="card-body">
                @php
                    $paymentLabels = [
                        'cod'     => '🚚 Tiền mặt (COD)',
                        'banking' => '🏦 Chuyển khoản',
                        'vnpay'   => '💳 VNPay',
                        'momo'    => '💜 Momo',
                    ];
                    $totalRevenue = $revenueByPayment->sum('revenue');
                @endphp
                @forelse($revenueByPayment as $item)
                @php
                    $percent = $totalRevenue > 0
                        ? round($item->revenue / $totalRevenue * 100)
                        : 0;
                @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>{{ $paymentLabels[$item->payment_method] ?? $item->payment_method }}</span>
                        <span class="fw-bold">{{ $percent }}%</span>
                    </div>
                    <div class="progress" style="height:8px">
                        <div class="progress-bar" style="width:{{ $percent }}%"></div>
                    </div>
                    <small class="text-muted">
                        {{ $item->count }} đơn —
                        {{ number_format($item->revenue, 0, ',', '.') }}đ
                    </small>
                </div>
                @empty
                <p class="text-muted text-center">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dữ liệu từ Laravel
    const chartData = @json($chartData);
    const labels    = chartData.map(d => d.month);
    const revenues  = chartData.map(d => d.revenue);
    const orders    = chartData.map(d => d.orders);

    // Biểu đồ doanh thu
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Doanh thu (đ)',
                    data: revenues,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: 'Số đơn',
                    data: orders,
                    type: 'line',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return 'Doanh thu: ' +
                                    new Intl.NumberFormat('vi-VN').format(context.raw) + 'đ';
                            }
                            return 'Số đơn: ' + context.raw;
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    ticks: {
                        callback: val =>
                            new Intl.NumberFormat('vi-VN', {
                                notation: 'compact'
                            }).format(val) + 'đ'
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });

    // Biểu đồ trạng thái đơn hàng
    const statusData = @json($ordersByStatus);
    const statusLabels = {
        pending:   'Chờ xác nhận',
        confirmed: 'Đã xác nhận',
        shipping:  'Đang giao',
        delivered: 'Đã giao',
        cancelled: 'Đã hủy'
    };
    const statusColors = [
        '#ffc107', '#0d6efd', '#0dcaf0', '#198754', '#dc3545'
    ];

    const statusKeys   = Object.keys(statusData);
    const statusCounts = statusKeys.map(k => statusData[k].count);
    const statusNames  = statusKeys.map(k => statusLabels[k] || k);

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusNames,
            datasets: [{
                data: statusCounts,
                backgroundColor: statusColors,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endsection