<div class="product-info">
    <h1 class="product-name">{{ $product->name }}</h1>

    <div class="interaction-buttons">
        <x-product.favorite-button :product="$product" />
        <x-product.comment-icon :count="$product->comments()->count()" />
    </div>

    <p class="product-price">¥{{ number_format($product->price) }}</p>

    <p class="product-brand">
        ブランド: 
        @if($product->brands->isNotEmpty())
            @foreach($product->brands as $brand)
                {{ $brand->name }}@if(!$loop->last)、@endif
            @endforeach
        @else
            なし
        @endif
    </p>

    <p class="product-category">
        カテゴリ: 
        @if($product->categories->isNotEmpty())
            @foreach($product->categories as $category)
                {{ $category->name }}@if(!$loop->last)、@endif
            @endforeach
        @else
            なし
        @endif
    </p>

    <p class="product-condition">状態: {{ $product->condition }}</p>

    <p class="product-description">{{ $product->description }}</p>

    @if($product->is_listed)
        <a href="{{ route('purchase.create', $product) }}" class="btn btn-primary">購入する</a>
    @else
        <p class="text-muted">売り切れです</p>
    @endif
</div>
