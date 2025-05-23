@if ($followings->count())
    <div class="followings-list">
        @foreach ($followings as $following)
            <div class="following-user-card">
                <a href="{{ route('users.show', $following->id) }}">
                    <img src="{{ asset('storage/' . ($following->profile_image ?? 'images/default-profile.png')) }}" alt="{{ $following->name }}" class="profile-thumb">
                    <p>{{ $following->name }}</p>
                </a>
            </div>
        @endforeach
    </div>
@else
    <p>フォロー中のユーザーはいません。</p>
@endif
