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

        $isSeller = Auth::id() === $purchase->product->user_id;

        $statusMessages = [
            'purchased' => $isSeller ? '商品が購入されました。購入者の支払いをお待ちください。' : '商品を購入しました。支払いを完了してください。',
            'paid' => $isSeller ? '支払いが完了されました。商品を発送してください。' : '支払いが完了されました。出品者の発送をお待ちください。',
            'shipped' => $isSeller ? '商品を発送しました。購入者の受け取り確認をお待ちください。' : '商品が発送されました。到着までお待ちください。',
            'received' => $isSeller ? '購入者が商品を受け取りました。購入者の評価をお待ちください。' : '商品を受け取りました。出品者の評価をお願いします。',
            'completed' => '取引が完了しました。',
        ];

        $statusMessage = $statusMessages[$purchase->status] ?? $purchase->status;
    @endphp

    <img src="{{ $imgSrc }}" alt="{{ $purchase->product->name }}" style="max-width: 300px;">

    <h2>取引状況</h2>
    <p class="status-message">{{ $statusMessage }}</p>

    @if($isSeller && $purchase->status === 'paid')
        <form method="POST" action="{{ route('transactions.updateStatus', $purchase->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="shipped">
            <button type="submit">発送済みにする</button>
        </form>
    @endif

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

    @if($purchase->status === 'received' && !$userHasRated)
    <h2>評価を入力</h2>
    <form method="POST" action="{{ route('ratings.store') }}">
        @csrf
        <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
        <label for="rating">評価（1〜5）:</label>
        <select name="rating" id="rating">
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }} 星</option>
            @endfor
        </select>
        <label for="comment">コメント:</label>
        <textarea name="comment" id="comment"></textarea>
        <button type="submit">評価を送信</button>
    </form>
        <style>
            .star-rating {
                display: flex;
                gap: 5px;
                cursor: pointer;
                font-size: 24px;
                color: gray;
            }
            .star {
                transition: color 0.2s;
            }
            .star.selected, .star:hover {
                color: gold;
            }
        </style>

        <script>
            document.querySelectorAll(".star").forEach(star => {
                star.addEventListener("click", function() {
                    let rating = this.getAttribute("data-value");
                    document.getElementById("rating-value").value = rating;

                    document.querySelectorAll(".star").forEach(s => s.classList.remove("selected"));
                    this.classList.add("selected");
                });
            });
        </script>
    @endif
</div>
@endsection
