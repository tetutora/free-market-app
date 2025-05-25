@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/products/create.css') }}">
@endsection

@section('content')
<div class="form-container">
    <h2>商品を出品する</h2>
    <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>商品名</label>
        <input type="text" name="name" value="{{ old('name') }}">
        @error('name')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <label>商品説明</label>
        <textarea name="description">{{ old('description') }}</textarea>
        @error('description')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <label>価格（円）</label>
        <input type="number" name="price" value="{{ old('price') }}">
        @error('price')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <label>商品画像（最大10枚）</label>
        <input type="file" id="imageInput" name="images[]" multiple accept="image/*">
        <div id="imagePreviewContainer" style="margin-top: 10px;"></div>
        @error('images')
            <div class="error-message">{{ $message }}</div>
        @enderror
        @error('images.*')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <label>カテゴリー</label>
        <select name="category_ids[]" multiple>
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

        <label>ブランド</label>
        <select name="brand_ids[]" multiple>
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

        <label>商品の状態</label>
        <select name="condition">
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

        <button type="submit">出品する</button>
    </form>
</div>
@endsection

@section('js')
<script src="{{ asset('js/products-create.js') }}"></script>
@endsection
