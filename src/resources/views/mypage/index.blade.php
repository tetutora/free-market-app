@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')
<div class="mypage-container">
    <div class="notification-icon-wrapper" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
        <a href="{{ route('notifications.index') }}" title="お知らせ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="30" height="30">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a2 2 0 10-4 0v1.083A6 6 0 004 11v3.159c0 .538-.214 1.055-.595 1.436L2 17h5m7 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            @if ($unreadCount > 0)
                <span class="notification-badge">{{ $unreadCount }}</span>
            @endif
        </a>
    </div>
    <div class="profile-section">
        <img src="{{ asset('storage/' . ($user->profile_image ?? 'images/default-profile.png')) }}" alt="プロフィール画像" class="profile-image">
        <h2>{{ $user->name }}</h2>
        <a href="{{ route('profile.edit') }}" class="btn">プロフィール設定</a>
    </div>

    <div class="tabs">
        <div class="tab-buttons">
            <button class="tab-button active" data-tab="favorites">お気に入り商品</button>
            <button class="tab-button" data-tab="purchases">購入商品</button>
            <button class="tab-button" data-tab="products">出品商品</button>
            <button class="tab-button" data-tab="in-transactions">取引中の商品</button>
            <button class="tab-button" data-tab="histories">閲覧履歴</button>
            <button class="tab-button" data-tab="followings">フォロー中</button>
        </div>

        <div class="tab-content active" id="favorites">
            @include('mypage.partials.products', ['products' => $favorites->pluck('product')])
        </div>

        <div class="tab-content" id="purchases">
            @include('mypage.partials.products', ['products' => $purchases->pluck('product')])
        </div>

        <div class="tab-content" id="listings">
            @include('mypage.partials.products', ['products' => $products])
        </div>

        <div class="tab-content" id="in-transactions">
            @include('mypage.partials.products', ['products' => $inTransactions->pluck('product')])
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
