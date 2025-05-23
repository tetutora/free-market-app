@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/user-profile.css') }}">
@endsection

@section('content')
<div class="user-profile-container">
    <div class="profile-header">
        <img src="{{ asset('storage/' . ($user->profile_image ?? 'images/default-profile.png')) }}" alt="プロフィール画像" class="profile-image">
        <h1>{{ $user->name }}</h1>
        @if ($user->profile_introduction)
        <p class="introduction">{{ $user->profile_introduction }}</p>
        @endif
        <div class="follow-info">
    <p>フォロー数: {{ $user->followings()->count() }}</p>
    <p>フォロワー数: {{ $user->followers()->count() }}</p>
</div>

@if(auth()->check() && auth()->id() !== $user->id)
    <form action="{{ route('users.follow.toggle', $user->id) }}" method="POST">
        @csrf
        @if (auth()->user()->isFollowing($user->id))
            <button type="submit" class="btn btn-secondary">フォロー中</button>
        @else
            <button type="submit" class="btn btn-primary">フォローする</button>
        @endif
    </form>
@endif

    </div>

    <div class="user-products-section">
    <h2>出品中の商品</h2>
        @if ($products->count())
        <div class="product-grid">
            @foreach ($products as $product)
                @php
                    $image = $product->image_path;
                    $isUrl = (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0);
                    $imgSrc = $isUrl ? $image : asset('storage/' . $image);
                @endphp
                <a href="{{ route('products.show', $product->id) }}" class="product-card">
                    <img src="{{ $imgSrc }}" alt="{{ $product->name }}">
                    <p>{{ $product->name }}</p>
                    <p>¥{{ number_format($product->price) }}</p>
                </a>
            @endforeach
        </div>
        @else
        <p>出品中の商品はありません。</p>
        @endif
    </div>

</div>
@endsection
