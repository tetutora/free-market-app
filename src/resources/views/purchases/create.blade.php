@extends('layouts.app')

@section('content')
<div class="purchase-page">
    <h1>{{ $product->name }} を購入</h1>

    <div class="product-summary">
        <p>価格: ¥{{ number_format($product->price) }}</p>
        <p>状態: {{ $product->condition }}</p>
        <p>説明: {{ $product->description }}</p>
    </div>

    <form action="{{ route('purchase.store', $product) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">購入を確定する</button>
        <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">戻る</a>
    </form>
</div>
@endsection
