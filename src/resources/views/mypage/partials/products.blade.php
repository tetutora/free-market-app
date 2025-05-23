@if ($products->count())
    <div class="product-grid">
        @foreach ($products as $product)
            @php
                $image = $product->image_path;
                $isUrl = (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0);
                $imgSrc = $isUrl ? $image : asset('storage/' . $image);
            @endphp
            <a href="{{ route('products.show', $product->id) }}" class="product-card">
                <img src="{{ $imgSrc }}" alt="{{ $product->name }}">
                <p>{{ $product->name }}</p>
                <p>¥{{ number_format($product->price) }}</p>
            </a>
        @endforeach
    </div>
@else
    <p>該当する商品はありません。</p>
@endif
