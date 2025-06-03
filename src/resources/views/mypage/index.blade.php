@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')
<div class="mypage-container">
    <div class="notification-icon-wrapper">
        <a href="{{ route('notifications.index') }}" title="お知らせ">
            @if ($unreadCount > 0)
                <span class="notification-badge">{{ $unreadCount }}</span>
            @endif
        </a>
    </div>
    <div class="profile-section">
        <img src="{{ asset('storage/' . ($user->profile_image ?? 'images/default-profile.png')) }}" alt="プロフィール画像" class="profile-image">
        <h2>{{ $user->name }}</h2>

        @if($user->receivedRatings()->count() > 0)
            <div class="user-rating">
                @php
                    $averageRating = $user->receivedRatings()->avg('rating') ?? 0;
                    $roundedRating = floor($averageRating);
                @endphp
                <span class="stars">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= $roundedRating ? 'filled' : '' }}">★</span>
                    @endfor
                </span>
                <span class="rating-number">（{{ number_format($averageRating, 1) }}）</span>
            </div>
        @else
            <p>まだ評価はありません。</p>
        @endif

        <a href="{{ route('profile.edit') }}" class="btn">プロフィール設定</a>
    </div>

    <div class="tabs">
        <div class="tab-buttons">
            <button class="tab-button active" data-tab="favorites">お気に入り商品</button>
            <button class="tab-button" data-tab="purchases">購入商品</button>
            <button class="tab-button" data-tab="listings">出品商品</button>
            <button class="tab-button" data-tab="in-transactions">取引中の商品</button>
            <button class="tab-button" data-tab="histories">閲覧履歴</button>
            <button class="tab-button" data-tab="followings">フォロー中</button>
        </div>
        <div class="tab-content active" id="favorites">
            @include('mypage.partials.products', ['products' => $favorites])
        </div>
        <div class="tab-content" id="purchases">
            @include('mypage.partials.products', ['products' => $purchases->pluck('product')])
        </div>
        <div class="tab-content" id="listings">
            @include('mypage.partials.products', ['products' => $products])
        </div>
        <div class="tab-content" id="in-transactions">
            @include('mypage.partials.in_transactions', ['purchases' => $inTransactions])
        </div>
        <div class="tab-content" id="histories">
            @include('mypage.partials.products', ['products' => $histories->pluck('product')])
        </div>
        <div class="tab-content" id="followings">
            @include('mypage.partials.followings', ['followings' => $followings])
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/mypage-tabs.js') }}"></script>
@endsection