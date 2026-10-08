@extends('admin.layouts.app')
@section('title', 'Quản lý danh mục')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5 class="fw-bold">Danh sách danh mục</h5>
    <a href="/admin/categories/create" class="btn btn-primary btn-sm">+ Thêm mới</a>
</div>
<div class="card border-0 shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Tên danh mục</th>
                <th>Slug</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $category->name }}</td>
                <td><code>{{ $category->slug }}</code></td>
                <td>
                    <a href="/admin/categories/{{ $category->id }}/edit"
                       class="btn btn-warning btn-sm">Sửa</a>
                    <form method="POST" action="/admin/categories/{{ $category->id }}"
                          class="d-inline"
                          onsubmit="return confirm('Xóa danh mục này?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-4">Chưa có danh mục nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection