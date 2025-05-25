@if ($products->count())
    <div class="product-grid">
        @foreach ($products as $product)
            @php
                // 画像パスを取得（複数画像対応なら $product->images->first()->path 等に変更してください）
                $image = $product->image_path ?? ($product->images->first()->path ?? null);

                if (empty($image)) {
                    $imgSrc = asset('images/no-image.png');  // 画像なしのデフォルト画像
                } elseif (filter_var($image, FILTER_VALIDATE_URL)) {
                    $imgSrc = $image;
                } else {
                    $imgSrc = asset('storage/' . $image);
                }
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
