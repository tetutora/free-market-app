@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/products/show.css') }}">
@endsection

@section('content')
<div class="product-detail-container">
    @php
        $images = $product->images;
        $imgPaths = $images->map(function($img) {
            if (empty($img->path)) {
                return asset('images/no-image.png');
            } elseif (filter_var($img->path, FILTER_VALIDATE_URL)) {
                return $img->path;
            } else {
                return asset('storage/' . $img->path);
            }
        })->toArray();

        if (empty($imgPaths)) {
            $imgPaths = [asset('images/no-image.png')];
        }
    @endphp

    <div class="product-images" data-images='@json($imgPaths)'>
        <img id="main-image" src="{{ $imgPaths[0] }}" alt="{{ $product->name }}" class="main-image">

        @if(count($imgPaths) > 1)
            <div class="arrow left" id="prev-arrow">&#8249;</div>
            <div class="arrow right" id="next-arrow">&#8250;</div>
        @endif
    </div>

    <x-product.info :product="$product" />
</div>

<div class="comment-section">
    <h2>コメント</h2>

    @foreach ($product->comments()->latest()->get() as $comment)
        @php
            $image = $comment->user->profile_image ?? 'images/default-profile.png';
            $isUrl = (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0);
            $imgSrc = $isUrl ? $image : asset('storage/' . $image);
        @endphp

        <div class="comment">
            <div class="comment-header">
                <img src="{{ $imgSrc }}" alt="{{ $comment->user->name }}" class="comment-avatar">
                <div>
                    <p class="comment-user">{{ $comment->user->name }}</p>
                    <p class="comment-body">{{ $comment->body }}</p>
                    <p class="comment-date">{{ $comment->created_at->format('Y/m/d H:i') }}</p>
                </div>
            </div>
        </div>
    @endforeach

    @auth
        <form action="{{ route('comments.store', $product) }}" method="POST" class="comment-form">
            @csrf
            <textarea name="body" rows="3" placeholder="コメントを入力…"></textarea>
            @error('body')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <button type="submit" class="btn">投稿</button>
        </form>
    @else
        <p><a href="{{ route('login') }}">ログイン</a>するとコメントできます。</p>
    @endauth
</div>

@if ($otherProducts->count())
    <div class="seller-products-section">
        <h2>
            <a href="{{ route('users.show', $product->user->id) }}" class="seller-link">
                {{ $product->user->name }}さんの他の商品
            </a>
        </h2>
        <div class="seller-products-scroll">
            @foreach ($otherProducts as $other)
                @php
                    $image = $other->images->first()->path ?? null;

                    if (empty($image)) {
                        $imgSrc = asset('images/no-image.png');
                    } elseif (filter_var($image, FILTER_VALIDATE_URL)) {
                        $imgSrc = $image;
                    } else {
                        $imgSrc = asset('storage/' . $image);
                    }
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

@section('js')
<script src="{{ asset('js/products-show.js') }}"></script>
@endsection
