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
        <div class="price-favorite-row">
            <p class="product-price">¥{{ number_format($product->price) }}</p>

            @auth
                @if (auth()->user()->hasVerifiedEmail())
                    @if (auth()->user()->favorites->contains($product->id))
                        <form method="POST" action="{{ route('favorites.destroy', $product) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="favorite-button large">★</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('favorites.store', $product) }}">
                            @csrf
                            <button type="submit" class="favorite-button large">☆</button>
                        </form>
                    @endif
                @endif
            @endauth
        </div>

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

@if ($otherProducts->count())
    <div class="seller-products-section">
        <h2>この出品者の他の商品</h2>
        <div class="seller-products-scroll">
            @foreach ($otherProducts as $other)
                @php
                    $image = $other->image_path;
                    $isUrl = (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0);
                    $imgSrc = $isUrl ? $image : asset('storage/' . $image);
                @endphp
                <a href="{{ route('products.show', $other->id) }}" class="seller-product-card">
                    <img src="{{ $imgSrc }}" alt="{{ $other->name }}">
                    <p class="name">{{ Str::limit($other->name, 20) }}</p>
                    <p class="price">¥{{ number_format($other->price) }}</p>
                </a>
            @endforeach
        </div>
    </div>
@endif

@endsection
