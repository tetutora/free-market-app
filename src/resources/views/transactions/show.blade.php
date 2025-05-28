@extends('layouts.app')

@section('content')
<div class="transaction-detail">
    <h1>取引詳細</h1>

    <h2>商品情報</h2>
    <p>商品名: {{ $purchase->product->name }}</p>
    <p>価格: ¥{{ number_format($purchase->product->price) }}</p>

    @php
        $image = $purchase->product->images->first()->path ?? null;

        if (empty($image)) {
            $imgSrc = asset('images/no-image.png');
        } elseif (filter_var($image, FILTER_VALIDATE_URL)) {
            $imgSrc = $image;
        } else {
            $imgSrc = asset('storage/' . $image);
        }
    @endphp

    <img src="{{ $imgSrc }}" alt="{{ $purchase->product->name }}" style="max-width: 300px;">

    <h2>取引状況</h2>
    <p>{{ $purchase->status }}</p>

    @if (Auth::id() === $purchase->product->user_id && $purchase->status === 'paid')
        <form method="POST" action="{{ route('transactions.updateStatus', $purchase->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="shipped">
            <button type="submit">発送済みにする</button>
        </form>
    @endif

    @if (Auth::id() === $purchase->buyer_id && $purchase->status === 'shipped')
        <form method="POST" action="{{ route('transactions.updateStatus', $purchase->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="received">
            <button type="submit">受け取り完了にする</button>
        </form>
    @endif
</div>
@endsection
