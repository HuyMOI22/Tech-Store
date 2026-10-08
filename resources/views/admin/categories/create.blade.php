@extends('admin.layouts.app')
@section('title', 'Thêm danh mục')
@section('content')
<div class="card border-0 shadow-sm" style="max-width:500px">
    <div class="card-body">
        <form method="POST" action="/admin/categories">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Tên danh mục</label>
                <input type="text" name="name" class="form-control"
                       placeholder="VD: Laptop, Điện thoại..."
                       value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Lưu danh mục</button>
            <a href="/admin/categories" class="btn btn-secondary ms-2">Hủy</a>
        </form>
    </div>
</div>
@endsection