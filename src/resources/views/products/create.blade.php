@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/products/create.css') }}">
@endsection

@section('content')
<div class="product-form-container">
    <h2 class="product-form-title">商品を出品する</h2>
    <form id="productForm" class="product-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">商品名</label>
            <input id="name" type="text" name="name" class="form-input" value="{{ old('name') }}">
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">商品説明</label>
            <textarea id="description" name="description" class="form-textarea">{{ old('description') }}</textarea>
            @error('description')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="price" class="form-label">価格（円）</label>
            <input id="price" type="number" name="price" class="form-input" value="{{ old('price') }}">
            @error('price')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="imageInput" class="form-label">商品画像（最大10枚）</label>
            <input id="imageInput" type="file" name="images[]" class="form-file" multiple accept="image/*">
            <div id="imagePreviewContainer" class="image-preview-container"></div>
            @error('images')
                <div class="error-message">{{ $message }}</div>
            @enderror
            @error('images.*')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="category_ids" class="form-label">カテゴリー</label>
            <select id="category_ids" name="category_ids[]" class="form-multiselect" multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        @if(is_array(old('category_ids')) && in_array($category->id, old('category_ids'))) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_ids')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="brand_ids" class="form-label">ブランド</label>
            <select id="brand_ids" name="brand_ids[]" class="form-multiselect" multiple>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}"
                        @if(is_array(old('brand_ids')) && in_array($brand->id, old('brand_ids'))) selected @endif>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
            @error('brand_ids')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="condition" class="form-label">商品の状態</label>
            <select id="condition" name="condition" class="form-select">
                <option value="">選択してください</option>
                @foreach ($conditions as $value)
                    <option value="{{ $value }}" {{ old('condition') == $value ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @error('condition')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="form-submit-button">出品する</button>
    </form>
</div>
@endsection

@section('js')
<script src="{{ asset('js/products-create.js') }}"></script>
@endsection