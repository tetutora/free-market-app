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
    <div class="interaction-buttons">
        @if($product->is_listed)
            @if(Auth::check() && Auth::id() === $product->user_id)
                <!-- 出品者本人は購入不可なので購入ボタンは表示しない -->
                <p class="info-message">※ご自身の商品は購入できません</p>
            @else
                <form action="{{ route('purchase.create', $product) }}" method="GET">
                    <button type="submit" class="btn">購入する</button>
                </form>
            @endif
        @else
            <p class="sold-out-label">SOLD OUT</p>
        @endif
    </div>
</div>
