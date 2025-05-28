@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/products/receipt.css') }}">
@endsection

@section('content')
<div class="receipt-container">
    <h1 class="receipt-title">ご購入ありがとうございます</h1>
    <p class="receipt-description">以下の情報をコンビニで提示し、支払いを行ってください。</p>

    <ul class="receipt-info-list">
    <li class="receipt-info-item">
        商品名：<span class="receipt-product-name">{{ $product->name }}</span>
    </li>
    <li class="receipt-info-item">
        金額：<span class="receipt-product-price">¥{{ number_format($product->price) }}</span>
    </li>
    <li class="receipt-info-item">
        注文番号：<span class="receipt-order-id">#{{ $product->id }}{{ $intent->id }}</span>
    </li>
</ul>

@php
    $paymentDetails = $intent->next_action->konbini_display_details ?? null;
    $fallbackCode = optional($intent->charges->data[0]->payment_method_details->konbini ?? null)->payment_code ?? null;
    $expiresAt = $paymentDetails?->expires_at;
@endphp

@if($paymentDetails || $fallbackCode)
<ul class="receipt-info-list">
    <li class="receipt-info-item">
        支払い番号：<span>{{ $paymentDetails->payment_code ?? $fallbackCode }}</span>
    </li>
    @if($expiresAt)
        <li class="receipt-info-item">
            支払い期限：<span>{{ \Carbon\Carbon::parse($expiresAt)->toDateTimeString() }}</span>
        </li>
    @endif
</ul>
@else
    <p class="receipt-warning">支払い情報が取得できませんでした。時間をおいて再度ご確認ください。</p>
@endif


    <button class="receipt-print-button" onclick="window.print()">レシートを印刷</button>
    <a href="{{ route('products.index') }}" class="receipt-back-button">商品一覧に戻る</a>
</div>
@endsection
