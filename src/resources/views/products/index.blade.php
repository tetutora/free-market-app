@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/products/index.css') }}">
@endsection

@section('content')
<form method="GET" action="{{ route('products.index') }}" id="search-form">
    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="キーワード" id="keyword-input">

    <select id="parent-category" name="parent_category_id">
        <option value="" {{ request('parent_category_id') == '' ? 'selected' : '' }}>ジャンルを選択</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ request('parent_category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <select id="child-category" name="category_id" {{ request('parent_category_id') ? '' : 'disabled' }}>
        <option value="">サブジャンルを選択</option>
    </select>

    <select name="brand_id" id="brand-select">
        <option value="">ブランドを選択</option>
        @foreach ($brands as $brand)
            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>

    <select name="is_listed" id="is-listed-select">
        <option value="">販売状況</option>
        <option value="1" {{ request('is_listed') === '1' ? 'selected' : '' }}>販売中</option>
        <option value="0" {{ request('is_listed') === '0' ? 'selected' : '' }}>売り切れ</option>
    </select>
</form>

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

                        <div class="price-favorite-row">
            <p class="product-price">¥{{ number_format($product->price) }}</p>

            @auth
                @if (auth()->user()->hasVerifiedEmail())
                    @if (auth()->user()->favorites->contains($product->id))
                        <form method="POST" action="{{ route('favorites.destroy', $product) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="favorite-button">★</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('favorites.store', $product) }}">
                            @csrf
                            <button type="submit" class="favorite-button">☆</button>
                        </form>
                    @endif
                @endif
            @endauth
        </div>
    </div>
</div>

            @endforeach
        </div>

        {{-- ページネーション --}}
        <x-pagination :paginator="$products" />

    @else
        <p>該当の商品はありません。</p>
    @endif
</div>
@endsection

@section('js')
    <script>
        window.selectedCategoryId = "{{ request('category_id') }}";
    </script>
    <script src="{{ asset('js/products-index.js') }}"></script>
@endsection

