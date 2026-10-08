<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function updatePayment(Order $order, \Illuminate\Http\Request $request)
{
    $request->validate([
        'payment_status' => 'required|in:paid,unpaid'
    ]);

    $order->update(['payment_status' => $request->payment_status]);

    return back()->with('success', '✅ Đã cập nhật trạng thái thanh toán!');
}
    public function index()
{
    $query = Order::with('user')->latest();

    // Lọc theo trạng thái
    if (request('status')) {
        $query->where('status', request('status'));
    }

    // Tìm kiếm theo mã đơn hàng
    if (request('search')) {
        $query->where('order_code', 'like', '%' . request('search') . '%');
    }

    $orders = $query->paginate(15)->withQueryString();

    return view('admin.orders.index', compact('orders'));
}
    public function show(Order $order)
    {
        $order->load('items', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Order $order, \Illuminate\Http\Request $request)
{
    $request->validate([
        'status' => 'required|in:pending,confirmed,shipping,delivered,cancelled'
    ]);

    $oldStatus = $order->status;
    $newStatus = $request->status;

    // Nếu hủy đơn thì hoàn lại tồn kho
    if ($newStatus == 'cancelled' && $oldStatus != 'cancelled') {
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }
    }

    // Nếu khôi phục từ cancelled sang trạng thái khác thì trừ lại tồn kho
    if ($oldStatus == 'cancelled' && $newStatus != 'cancelled') {
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->decrement('stock', $item->quantity);
            }
        }
    }

    $order->update(['status' => $newStatus]);

    return back()->with('success', 'Cập nhật trạng thái thành công!');
}
}