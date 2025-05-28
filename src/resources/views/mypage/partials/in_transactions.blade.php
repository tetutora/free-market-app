@if ($inTransactions->count())
    <div class="product-grid">
        @foreach ($inTransactions as $purchase)
            @php
                $product = $purchase->product;
                $image = $product->images->first()->path ?? null;

                if (empty($image)) {
                    $imgSrc = asset('images/no-image.png');
                } elseif (filter_var($image, FILTER_VALIDATE_URL)) {
                    $imgSrc = $image;
                } else {
                    $imgSrc = asset('storage/' . $image);
                }
            @endphp
            <a href="{{ route('transactions.show', $purchase->id) }}" class="product-card">
                <img src="{{ $imgSrc }}" alt="{{ $product->name }}">
                <p>{{ $product->name }}</p>
                <p>¥{{ number_format($product->price) }}</p>
                <p>取引状況: {{ $purchase->status }}</p>
            </a>
        @endforeach
    </div>
@else
    <p>取引中の商品はありません。</p>
@endif
