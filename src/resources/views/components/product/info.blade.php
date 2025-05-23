<div class="product-info">
    <h1 class="product-name">{{ $product->name }}</h1>

    <div class="interaction-buttons">
        <x-product.favorite-button :product="$product" />
        <x-product.comment-icon :count="$product->comments()->count()" />
    </div>

    <p class="product-price">¥{{ number_format($product->price) }}</p>
    <p class="product-brand">ブランド: {{ $product->brand?->name ?? 'なし' }}</p>
    <p class="product-category">カテゴリ: {{ $product->category?->name ?? 'なし' }}</p>
    <p class="product-condition">状態: {{ $product->condition }}</p>
    <p class="product-description">{{ $product->description }}</p>

    @if($product->is_listed)
        <button class="btn btn-primary">購入する</button>
    @else
        <p class="text-muted">売り切れです</p>
    @endif
</div>
