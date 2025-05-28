@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/products/index.css') }}">
@endsection

@section('content')
    <x-product.search-form :categories="$categories" :brands="$brands" />

    <div class="products-container">
        <h1 class="mb-4">商品一覧</h1>

        @if ($products->count())
            <div class="products-row">
                @foreach ($products as $product)
                    <x-product.card :product="$product" />
                @endforeach
            </div>
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
