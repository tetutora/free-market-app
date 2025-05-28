@extends('layouts.app')

@section('content')
<div class="thank-you-page">
    <h1>ご購入ありがとうございました！</h1>
    <p>クレジットカードでの支払いが正常に完了しました。</p>
    <a href="{{ route('mypage') }}" class="btn btn-primary mt-3">マイページへ</a>
</div>
@endsection
