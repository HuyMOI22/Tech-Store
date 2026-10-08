@extends('admin.layouts.app')
@section('title', 'Sửa danh mục')
@section('content')
<div class="card border-0 shadow-sm" style="max-width:500px">
    <div class="card-body">
        <form method="POST" action="/admin/categories/{{ $category->id }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-bold">Tên danh mục</label>
                <input type="text" name="name" class="form-control"
                       value="{{ $category->name }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="/admin/categories" class="btn btn-secondary ms-2">Hủy</a>
        </form>
    </div>
</div>
@endsection