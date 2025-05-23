<div class="favorite-block">
    @auth
        @if (auth()->user()->hasVerifiedEmail())
            @if (auth()->user()->favorites->contains($product->id))
                <form method="POST" action="{{ route('favorites.destroy', $product) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="favorite-button large">★</button>
                </form>
            @else
                <form method="POST" action="{{ route('favorites.store', $product) }}">
                    @csrf
                    <button type="submit" class="favorite-button large">☆</button>
                </form>
            @endif
        @endif
    @endauth
    <p class="count-text">{{ $product->favorites()->count() }}</p>
</div>
