@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/products/index.css') }}">
@endsection

@section('content')
<div class="products-container">
    <h1 class="mb-4">商品一覧</h1>

    @if ($products->count())
        <div class="products-row">
            @foreach ($products as $product)
                @php
                    $image = $product->image_path;
                    $isUrl = (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0);
                    $imgSrc = $isUrl ? $image : asset('storage/' . $image);
                @endphp

                <div class="product-card">
                    <a href="{{ route('products.show', $product->id) }}">
                        <img src="{{ $imgSrc }}" alt="{{ $product->name }}">
                    </a>
                    <div class="product-info">
                        <h2 class="product-name" title="{{ $product->name }}">{{ $product->name }}</h2>
                        <p class="product-price">¥{{ number_format($product->price) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ページネーションコンポーネント呼び出し --}}
        <x-pagination :paginator="$products" />

    @else
        <p>現在出品されている商品はありません。</p>
    @endif
</div>
@endsection
