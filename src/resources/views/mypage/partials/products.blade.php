@if ($products->count())
    <div class="product-grid">
        @foreach ($products as $product)
            @if ($product)
                @php
                    $imagePath = null;
                    if ($product->images && $product->images->first()) {
                        $imagePath = $product->images->first()->path;
                    }

                    if (empty($imagePath)) {
                        $imgSrc = asset('images/no-image.png');
                    } elseif (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                        $imgSrc = $imagePath;
                    } else {
                        $imgSrc = asset('storage/' . $imagePath);
                    }
                @endphp
                <a href="{{ route('products.show', $product->id) }}" class="product-card {{ $product->is_listed ? '' : 'sold-out-card' }}" style="position: relative;">
                    <img src="{{ $imgSrc }}" alt="{{ $product->name }}" style="{{ $product->is_listed ? '' : 'opacity: 0.5;' }}">
                    @unless($product->is_listed)
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                                    color: white; font-weight: bold; background-color: rgba(0,0,0,0.6);
                                    padding: 3px 8px; font-size: 1rem;">
                            SOLD OUT
                        </div>
                    @endunless
                    <p>{{ $product->name }}</p>
                    <p>¥{{ number_format($product->price) }}</p>
                </a>
            @else
                {{-- $productがnullの場合の表示 --}}
            @endif
        @endforeach
    </div>
@else
    <p>商品がありません。</p>
@endif
