@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/transactions/show.css') }}">
@endsection

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

        // ユーザーが出品者か購入者かを判定
        $isSeller = Auth::id() === $purchase->product->user_id;

        // ステータスに応じたメッセージ（出品者・購入者で分岐）
        $statusMessages = $isSeller
            ? [
                'purchased' => '商品が購入されました。購入者の支払いをお待ちください。',
                'paid' => '支払いが完了されました。商品を発送してください。',
                'shipped' => '商品を発送しました。購入者の受け取り確認をお待ちください。',
                'received' => '購入者が商品を受け取りました。購入者の評価をお待ちください。',
                'completed' => '取引が完了しました。',
            ]
            : [
                'purchased' => '商品を購入しました。支払いを完了してください。',
                'paid' => '支払いが完了されました。出品者の発送をお待ちください。',
                'shipped' => '商品が発送されました。到着までお待ちください。',
                'received' => '商品を受け取りました。出品者の評価をお願いします。',
                'completed' => '取引が完了しました。',
            ];

        $statusMessage = $statusMessages[$purchase->status] ?? $purchase->status;
    @endphp

    <img src="{{ $imgSrc }}" alt="{{ $purchase->product->name }}" style="max-width: 300px;">

    <h2>取引状況</h2>
    <p class="status-message">{{ $statusMessage }}</p>

    {{-- 出品者用：支払い完了 → 発送 --}}
    @if($isSeller && $purchase->status === 'paid')
        <form method="POST" action="{{ route('transactions.updateStatus', $purchase->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="shipped">
            <button type="submit">発送済みにする</button>
        </form>
    @endif

    {{-- 購入者用：発送済み → 受取済み --}}
    @if(!$isSeller && $purchase->status === 'purchased')
        <form method="POST" action="{{ route('transactions.updateStatus', $purchase->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="paid">
            <button type="submit">支払い完了にする（コンビニ支払い）</button>
        </form>
    @endif

    @if(!$isSeller && $purchase->status === 'shipped')
        <form method="POST" action="{{ route('transactions.updateStatus', $purchase->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="received">
            <button type="submit">受け取り済みにする</button>
        </form>
    @endif
</div>
@endsection
