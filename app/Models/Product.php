<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    'category_id', 'name', 'slug', 'description',
    'price', 'sale_price', 'stock', 'brand',
    'specs', 'image', 'images', 'is_active',
    'colors', 'versions',   // ← thêm
];

    protected $casts = [
    'specs'    => 'array',
    'images'   => 'array',
    'colors'   => 'array',   // ← thêm
    'versions' => 'array',   // ← thêm
];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    // Giá hiển thị (ưu tiên giá sale)
    public function getFinalPriceAttribute() {
        return $this->sale_price ?? $this->price;
    }

    

}
