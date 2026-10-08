<?php

namespace App\Http\Controllers\Admin;
use App\Exports\RevenueExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $year = request('year', date('Y'));

        // Thống kê tổng quan
        $stats = [
            'products'   => Product::count(),
            'orders'     => Order::count(),
            'users'      => User::count(),
            'categories' => Category::count(),
            'revenue'    => Order::where('status', 'delivered')->sum('total_amount'),
            'pending'    => Order::where('status', 'pending')->count(),
        ];

        // Doanh thu theo tháng
        $monthlyRevenue = Order::where('status', 'delivered')
            ->whereYear('created_at', $year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Fill đủ 12 tháng
        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartData[] = [
                'month'   => 'T' . $m,
                'revenue' => $monthlyRevenue->get($m)?->revenue ?? 0,
                'orders'  => $monthlyRevenue->get($m)?->orders ?? 0,
            ];
        }

        // Top sản phẩm bán chạy
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'delivered')
            ->whereYear('orders.created_at', $year)
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Đơn hàng theo trạng thái
        $ordersByStatus = Order::whereYear('created_at', $year)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Doanh thu theo phương thức thanh toán
        $revenueByPayment = Order::where('status', 'delivered')
            ->whereYear('created_at', $year)
            ->select(
                'payment_method',
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('payment_method')
            ->get();

        $years = range(date('Y'), 2024);

        return view('admin.dashboard', compact(
            'stats', 'chartData', 'topProducts',
            'ordersByStatus', 'revenueByPayment', 'year', 'years'
        ));
    }
    public function exportExcel(Request $request)
{
    $year  = $request->year ?? date('Y');
    $month = $request->month ?? null;

    $filename = 'doanh-thu-' . $year;
    if ($month) {
        $filename .= '-thang-' . $month;
    }
    $filename .= '.xlsx';

    return Excel::download(new RevenueExport($year, $month), $filename);
}
}