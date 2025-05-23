@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/products/create.css') }}">
@endsection

@section('content')
<div class="form-container">
    <h2>商品を出品する</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>商品名</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>商品説明</label>
        <textarea name="description">{{ old('description') }}</textarea>

        <label>価格（円）</label>
        <input type="number" name="price" value="{{ old('price') }}" required>

        <label>商品画像</label>
<input type="file" name="image" id="imageInput">
<img id="imagePreview" src="#" alt="プレビュー画像" style="display: none; max-width: 200px; margin-top: 10px;">

        <label>カテゴリー</label>
        <select name="category_id">
            <option value="">選択してください</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label>ブランド</label>
        <select name="brand_id">
            <option value="">選択してください</option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>

        <label>商品の状態</label>
        <select name="condition" required>
            <option value="">選択してください</option>
            @foreach ($conditions as $value)
                <option value="{{ $value }}" {{ old('condition') == $value ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>

        <label>出品するか</label>
        <input type="checkbox" name="is_listed" value="1" {{ old('is_listed', true) ? 'checked' : '' }}> 出品中

        <button type="submit">出品する</button>
    </form>
</div>
@endsection

@section('js')
<script src="{{ asset('js/products-create.js') }}"></script>
@endsection

