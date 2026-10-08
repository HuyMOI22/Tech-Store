<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VNPayController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

// ===== SHOP (PUBLIC) =====
Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/san-pham', [ShopController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [ShopController::class, 'show'])->name('products.show');
Route::get('/danh-muc/{slug}', [ShopController::class, 'category'])->name('category');

// VNPay return (không cần auth)
Route::get('/vnpay/return', [VNPayController::class, 'return'])->name('vnpay.return');

// ===== AUTH REQUIRED =====
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => redirect()->route('home'))->name('dashboard');

    // Giỏ hàng
    Route::post('/gio-hang/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/gio-hang/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/gio-hang/{item}', [CartController::class, 'update'])->name('cart.update');

    // Đặt hàng
    Route::get('/dat-hang', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/dat-hang', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/don-hang/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/don-hang-cua-toi', [OrderController::class, 'index'])->name('orders.index');

    // VNPay
    Route::get('/vnpay/create/{order}', [VNPayController::class, 'create'])->name('vnpay.create');
    Route::post('/vnpay/create/{order}', [VNPayController::class, 'create'])->name('vnpay.create');
});

// ===== ADMIN =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::get('/products/{product}/delete-image/{index}',
        [ProductController::class, 'deleteImage'])->name('products.deleteImage');

    Route::resource('categories', CategoryController::class);

    Route::resource('orders', AdminOrderController::class);
    Route::patch('/orders/{order}/status',
        [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::patch('/orders/{order}/payment', [AdminOrderController::class, 'updatePayment'])->name('orders.updatePayment');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::patch('/users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggleAdmin');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
Route::get('/export-revenue', [DashboardController::class, 'exportExcel'])->name('admin.export.revenue');

require __DIR__ . '/auth.php';