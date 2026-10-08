@extends('admin.layouts.app')
@section('title', 'Quản lý sản phẩm')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5 class="fw-bold">Danh sách sản phẩm</h5>
    <a href="/admin/products/create" class="btn btn-primary btn-sm">+ Thêm sản phẩm</a>
</div>
<div class="card border-0 shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Tồn kho</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ asset('storage/' . $product->image) }}"
                         width="60" height="60"
                         style="object-fit:cover; border-radius:8px"
                         onerror="this.src='https://placehold.co/60x60?text=No'">
                </td>
                <td>
                    <p class="mb-0 fw-bold">{{ $product->name }}</p>
                    <small class="text-muted">{{ $product->brand }}</small>
                </td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>
                    <span class="text-danger fw-bold">
                        {{ number_format($product->price, 0, ',', '.') }}đ
                    </span>
                </td>
                <td>
                    <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                        {{ $product->stock }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $product->is_active ? 'Hiện' : 'Ẩn' }}
                    </span>
                </td>
                <td>
                    <a href="/admin/products/{{ $product->id }}/edit"
                       class="btn btn-warning btn-sm">Sửa</a>
                    <form method="POST" action="/admin/products/{{ $product->id }}"
                          class="d-inline"
                          onsubmit="return confirm('Xóa sản phẩm này?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-4">Chưa có sản phẩm nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection