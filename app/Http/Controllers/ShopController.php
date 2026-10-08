<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function home()
    {
        $products = Product::where('is_active', true)->latest()->take(8)->get();
        $categories = Category::all();
        return view('home', compact('products', 'categories'));
    }

    public function index(Request $request)
{
    $categories = Category::withCount('products')->get();
    $query      = Product::where('is_active', true)->with('category');

    // Tìm kiếm
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('brand', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    // Lọc theo danh mục
    if ($request->filled('category')) {
        $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
    }

    // Lọc theo giá
    match ($request->price) {
        'under_100'  => $query->where('price', '<', 100000),
        '100_500'    => $query->whereBetween('price', [100000, 500000]),
        '500_1000'   => $query->whereBetween('price', [500000, 1000000]),
        'over_1000'  => $query->where('price', '>', 1000000),
        default      => null,
    };

    // Sắp xếp
    match ($request->sort) {
        'price_asc'  => $query->orderBy('price'),
        'price_desc' => $query->orderByDesc('price'),
        default      => $query->latest(),
    };

    $products = $query->paginate(12);
    return view('shop.index', compact('products', 'categories'));
}

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)->get();
        return view('shop.show', compact('product', 'related'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)->paginate(12);
        return view('shop.category', compact('category', 'products'));
    }
}