<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'required',
        ]);

        $imagePath = 'no-image.jpg';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $extraImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $extraImages[] = $img->store('products', 'public');
            }
        }

        Product::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name) . '-' . time(),
            'category_id' => $request->category_id,
            'price'       => $request->price,
            'sale_price'  => $request->sale_price ?: null,
            'stock'       => $request->stock,
            'brand'       => $request->brand,
            'description' => $request->description,
            'image'       => $imagePath,
            'images'      => $extraImages,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect('/admin/products')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required',
            'category_id' => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $extraImages = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $extraImages[] = $img->store('products', 'public');
            }
        }
        $colors   = array_values(array_filter($request->colors ?? []));
    $versions = array_values(array_filter($request->versions ?? []));

        $product->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name) . '-' . $product->id,
            'category_id' => $request->category_id,
            'price'       => $request->price,
            'sale_price'  => $request->sale_price ?: null,
            'stock'       => $request->stock,
            'brand'       => $request->brand,
            'description' => $request->description,
            'image'       => $imagePath,
            'images'      => $extraImages, 
            'is_active'   => $request->has('is_active'),
            'colors'      => $colors,    // ← thêm
        'versions'    => $versions,
        ]);

        return redirect('/admin/products')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect('/admin/products')->with('success', 'Đã xóa sản phẩm!');
    }

    public function deleteImage(Product $product, $index)
{
    $images = $product->images ?? [];
    unset($images[$index]);
    $product->update(['images' => array_values($images)]);
    return back()->with('success', 'Đã xóa ảnh!');
}
}