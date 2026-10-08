@extends('admin.layouts.app')
@section('title', 'Thêm sản phẩm')
@section('content')
<div class="card border-0 shadow-sm" style="max-width:700px">
    <div class="card-body">
        <form method="POST" action="/admin/products" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Tên sản phẩm *</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Thương hiệu</label>
                    <input type="text" name="brand" class="form-control"
                           placeholder="Apple, Samsung..."
                           value="{{ old('brand') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Danh mục *</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Giá gốc *</label>
                    <input type="number" name="price" class="form-control"
                           placeholder="0" value="{{ old('price') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Giá sale</label>
                    <input type="number" name="sale_price" class="form-control"
                           placeholder="0" value="{{ old('sale_price') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tồn kho *</label>
                    <input type="number" name="stock" class="form-control"
                           placeholder="0" value="{{ old('stock') }}" required>
                </div>
                <div class="col-md-9">
                    <label class="form-label fw-bold">Ảnh sản phẩm</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                {{-- Thêm vào sau field ảnh chính --}}
<div class="col-12">
    <label class="form-label fw-bold">Ảnh phụ (có thể chọn nhiều ảnh)</label>
    <input type="file" name="images[]" class="form-control"
           accept="image/*" multiple>
    <small class="text-muted">Giữ Ctrl để chọn nhiều ảnh cùng lúc</small>
</div>
                <div class="col-12">
                    <label class="form-label fw-bold">Mô tả *</label>
                    <textarea name="description" class="form-control"
                              rows="4" required>{{ old('description') }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input"
                               id="is_active" checked>
                        <label class="form-check-label" for="is_active">
                            Hiển thị sản phẩm
                        </label>
                    </div>
                </div>
            </div>
            {{-- Màu sắc --}}
<div class="col-12">
    <label class="form-label fw-bold">Màu sắc</label>
    <div id="colorList">
        @if(isset($product) && $product->colors)
            @foreach($product->colors as $color)
            <div class="input-group mb-2">
                <input type="text" name="colors[]" class="form-control"
                       value="{{ $color }}" placeholder="VD: Đen, Trắng, Xanh...">
                <button type="button" class="btn btn-outline-danger"
                        onclick="this.parentElement.remove()">✕</button>
            </div>
            @endforeach
        @else
            <div class="input-group mb-2">
                <input type="text" name="colors[]" class="form-control"
                       placeholder="VD: Đen, Trắng, Xanh...">
                <button type="button" class="btn btn-outline-danger"
                        onclick="this.parentElement.remove()">✕</button>
            </div>
        @endif
    </div>
    <button type="button" class="btn btn-outline-secondary btn-sm"
            onclick="addField('colorList', 'colors[]', 'VD: Đỏ, Vàng...')">
        + Thêm màu
    </button>
</div>

{{-- Phiên bản --}}
<div class="col-12">
    <label class="form-label fw-bold">Phiên bản</label>
    <div id="versionList">
        @if(isset($product) && $product->versions)
            @foreach($product->versions as $version)
            <div class="input-group mb-2">
                <input type="text" name="versions[]" class="form-control"
                       value="{{ $version }}" placeholder="VD: 128GB, 256GB...">
                <button type="button" class="btn btn-outline-danger"
                        onclick="this.parentElement.remove()">✕</button>
            </div>
            @endforeach
        @else
            <div class="input-group mb-2">
                <input type="text" name="versions[]" class="form-control"
                       placeholder="VD: 128GB, 256GB...">
                <button type="button" class="btn btn-outline-danger"
                        onclick="this.parentElement.remove()">✕</button>
            </div>
        @endif
    </div>
    <button type="button" class="btn btn-outline-secondary btn-sm"
            onclick="addField('versionList', 'versions[]', 'VD: Pro, Plus...')">
        + Thêm phiên bản
    </button>
</div>

{{-- Script thêm field động --}}
<script>
function addField(containerId, name, placeholder) {
    const container = document.getElementById(containerId);
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" name="${name}" class="form-control" placeholder="${placeholder}">
        <button type="button" class="btn btn-outline-danger"
                onclick="this.parentElement.remove()">✕</button>
    `;
    container.appendChild(div);
}
</script>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
                <a href="/admin/products" class="btn btn-secondary ms-2">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection