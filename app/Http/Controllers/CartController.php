<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, Product $product)
{
    if (!auth()->check()) {
        return redirect()->route('login')
            ->with('error', 'Vui lòng đăng nhập để mua hàng!');
    }

    if ($product->stock <= 0) {
        return back()->with('error', 'Sản phẩm đã hết hàng!');
    }

    $item = CartItem::firstOrCreate(
        ['user_id' => auth()->id(), 'product_id' => $product->id],
        ['quantity' => 0]
    );

    $qty    = $request->quantity ?? 1;
    $newQty = $item->quantity + $qty;
    if ($newQty > $product->stock) {
        $newQty = $product->stock;
    }

    $item->update([
        'quantity' => $newQty,
        'color'    => $request->selected_color ?? null,   // ← thêm
        'version'  => $request->selected_version ?? null, // ← thêm
    ]);

    if ($request->action == 'buynow') {
        return redirect()->route('orders.create');
    }

    return back()->with('success', '✅ Đã thêm vào giỏ hàng!');
}

    public function index()
    {
        $items = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $total = $items->sum(fn($i) => $i->product->final_price * $i->quantity);

        return view('cart.index', compact('items', 'total'));
    }

    public function update(Request $request, CartItem $item)
    {
        // Kiểm tra quyền sở hữu
        if ($item->user_id !== auth()->id()) {
            abort(403);
        }

        $qty = max(1, min($request->quantity, $item->product->stock));
        $item->update(['quantity' => $qty]);

        return back()->with('success', 'Đã cập nhật giỏ hàng!');
    }

    public function remove(CartItem $item)
    {
        // Kiểm tra quyền sở hữu
        if ($item->user_id !== auth()->id()) {
            abort(403);
        }

        $item->delete();
        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }
}