@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/create.css') }}">
@endsection

@section('content')
<div class="purchase-page">
    <h1 class="purchase-page__title">{{ $product->name }} を購入</h1>

    <div class="product-summary">
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
        @endphp

        <div class="product-images" data-images='@json($imgPaths)'>
            <img id="main-image" src="{{ $imgPaths[0] }}" alt="{{ $product->name }}" class="product-image">

            @if(count($imgPaths) > 1)
                <div class="arrow left" id="prev-arrow">&#8249;</div>
                <div class="arrow right" id="next-arrow">&#8250;</div>
            @endif
        </div>

        <p class="product-summary__text">価格: ¥{{ number_format($product->price) }}</p>
        <p class="product-summary__text">状態: {{ $product->condition }}</p>
        <p class="product-summary__text">説明: {{ $product->description }}</p>
    </div>

    <form id="payment-form" method="POST" action="{{ route('purchase.payment', $product) }}" class="form">
        @csrf

        <div class="form-section">
            <label for="address_id" class="section-title">配送先を選択してください</label>
            <div class="address-select-wrapper">
                <select name="address_id" id="address_id">
                    @foreach ($addresses as $address)
                        <option value="{{ $address->id }}" {{ old('address_id') == $address->id ? 'selected' : '' }}>
                            {{ $address->postal_code }} {{ $address->prefecture }}{{ $address->city }}{{ $address->street }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('address_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <label class="section-title">支払い方法</label>
        <div class="radio-group">
            <div class="radio-option">
                <input type="radio" name="payment_method" value="card" id="pay_card"
                    {{ old('payment_method') === 'card' ? 'checked' : '' }}>
                <label for="pay_card">クレジットカード</label>
            </div>
            <div class="radio-option">
                <input type="radio" name="payment_method" value="konbini" id="pay_konbini"
                    {{ old('payment_method') === 'konbini' ? 'checked' : '' }}>
                <label for="pay_konbini">コンビニ払い</label>
            </div>
        </div>
        @error('payment_method')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <button type="submit" class="button--submit">購入を確定する</button>
    </form>
</div>
@endsection

@section('js')
<script src="{{ asset('js/purchases-create.js') }}"></script>
@endsection
