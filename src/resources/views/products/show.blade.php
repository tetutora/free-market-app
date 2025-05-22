商品詳細@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/products/show.css') }}">
@endsection

@section('content')
<div class="product-detail-container">
    <div class="product-images">
        @php
            $image = $product->image_path;
            $isUrl = (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0);
            $imgSrc = $isUrl ? $image : asset('storage/' . $image);
        @endphp
        <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="main-image">
    </div>

    <div class="product-info">
        <h1 class="product-name">{{ $product->name }}</h1>
        <p class="product-price">¥{{ number_format($product->price) }}</p>
        <p class="product-brand">ブランド: {{ $product->brand ? $product->brand->name : 'なし' }}</p>
        <p class="product-category">カテゴリ: {{ $product->category ? $product->category->name : 'なし' }}</p>
        <p class="product-condition">状態: {{ $product->condition }}</p>
        <p class="product-description">{{ $product->description }}</p>

        @if($product->is_listed)
            <button class="btn btn-primary">購入する</button>
        @else
            <p class="text-muted">売り切れです</p>
        @endif
    </div>
</div>
@endsection
