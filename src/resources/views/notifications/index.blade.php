@extends('layouts.app')

@section('content')
<h1>お知らせ一覧</h1>

@if ($notifications->isEmpty())
    <p>お知らせはありません。</p>
@else
    <ul>
    @foreach ($notifications as $notification)
        <li @if(is_null($notification->read_at)) style="font-weight:bold;" @endif>
            <form method="POST" action="{{ route('notifications.read', $notification) }}" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit" style="all:unset; cursor:pointer; color:inherit;">
                    {{ $notification->content }}
                    <small>({{ $notification->created_at->format('Y/m/d H:i') }})</small>
                </button>
            </form>
        </li>
    @endforeach
    </ul>
@endif
@endsection