@php
    $image = $product->images->first()->path ?? null;
    $imgSrc = $image ? (filter_var($image, FILTER_VALIDATE_URL) ? $image : asset('storage/' . $image)) : asset('images/no-image.png');
@endphp

<div class="product-card">
    <a href="{{ route('products.show', $product->id) }}" style="display: inline-block; position: relative;">
        <img src="{{ $imgSrc }}" alt="{{ $product->name }}" @if(!$product->is_listed) style="opacity: 0.5;" @endif>
        @if(!$product->is_listed)
            <div class="sold-out-overlay">SOLD OUT</div>
        @endif
    </a>

    <div class="product-info">
        <h2 class="product-name" title="{{ $product->name }}">{{ $product->name }}</h2>

        <div class="price-favorite-row">
            <p class="product-price">¥{{ number_format($product->price) }}</p>

            @auth
                @if (auth()->user()->hasVerifiedEmail())
                    <form method="POST" action="{{ auth()->user()->favorites->contains($product->id) ? route('favorites.destroy', $product) : route('favorites.store', $product) }}">
                        @csrf
                        @if (auth()->user()->favorites->contains($product->id))
                            @method('DELETE')
                            <button type="submit" class="favorite-button">★</button>
                        @else
                            <button type="submit" class="favorite-button">☆</button>
                        @endif
                    </form>
                @endif
            @endauth
        </div>
    </div>
</div>
