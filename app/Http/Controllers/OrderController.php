<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create()
    {
        $items = CartItem::with('product')
            ->where('user_id', auth()->id())->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng trống!');
        }

        $total = $items->sum(fn($i) => $i->product->final_price * $i->quantity);
        return view('orders.create', compact('items', 'total'));
    }

    public function store(Request $request)
{
    $request->validate([
        'shipping_name'    => 'required|string',
        'shipping_phone'   => 'required|string',
        'shipping_address' => 'required|string',
        'payment_method'   => 'required|in:cod,banking,momo,vnpay',
    ]);

    $cartItems = CartItem::with('product')
        ->where('user_id', auth()->id())->get();

    if ($cartItems->isEmpty()) {
        return back()->with('error', 'Giỏ hàng trống!');
    }

    $total = $cartItems->sum(
        fn($i) => $i->product->final_price * $i->quantity
    );

    $order = Order::create([
        'user_id'          => auth()->id(),
        'order_code' => 'ORD-' . strtoupper(bin2hex(random_bytes(6))) . '-' . time(),
        'total_amount'     => $total,
        'shipping_name'    => $request->shipping_name,
        'shipping_phone'   => $request->shipping_phone,
        'shipping_address' => $request->shipping_address,
        'payment_method'   => $request->payment_method,
        'note'             => $request->note,
    ]);

    foreach ($cartItems as $item) {
        $order->items()->create([
            'product_id'   => $item->product_id,
            'product_name' => $item->product->name,
            'price'        => $item->product->final_price,
            'quantity'     => $item->quantity,
            'color'        => $item->color,    // ← thêm
            'version'      => $item->version,
        ]);
        $item->product->decrement('stock', $item->quantity);
    }

    CartItem::where('user_id', auth()->id())->delete();

    if ($request->payment_method == 'vnpay') {
        return redirect()->route('vnpay.create', $order);
    }

    return redirect()->route('orders.show', $order)
        ->with('success', 'Đặt hàng thành công! 🎉');
}
    public function show(Order $order)
    {
        $order->load('items');
        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()->get();
        return view('orders.index', compact('orders'));
    }
}