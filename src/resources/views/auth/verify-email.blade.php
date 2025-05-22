@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection


@section('content')
<div class="verify-container">
    <h1 class="verify-title">メールアドレスの確認</h1>

    @if (session('message'))
        <div class="alert-success">{{ session('message') }}</div>
    @endif

    <p class="verify-message">
        登録したメールアドレス宛に確認リンクを送信しました。<br>
        メールを確認し、リンクをクリックしてください。
    </p>

    <form method="POST" action="{{ route('verification.send') }}" class="verify-form">
        @csrf
        <button type="submit" class="btn-primary">確認メールを再送信</button>
    </form>
</div>
@endsection