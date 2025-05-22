@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/create.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>{{ isset($address) ? '住所を編集' : '住所を追加' }}</h1>

    <form action="{{ isset($address) ? route('addresses.update', $address) : route('addresses.store') }}" method="POST" class="form">
        @csrf
        @isset($address)
            @method('PATCH')
        @endisset

        <x-form.input name="postal_code" label="郵便番号" :value="$address->postal_code ?? ''" />
        <x-form.input name="prefecture" label="都道府県" :value="$address->prefecture ?? ''" />
        <x-form.input name="city" label="市区町村" :value="$address->city ?? ''" />
        <x-form.input name="street" label="番地" :value="$address->street ?? ''" />
        <x-form.input name="building" label="建物名" :value="$address->building ?? ''" />

        <button type="submit" class="btn btn-primary">
            {{ isset($address) ? '更新する' : '追加する' }}
        </button>
        <a href="{{ route('profile.edit') }}" class="btn btn-secondary">キャンセル</a>
    </form>
    @isset($address)
    <form action="{{ route('addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('本当にこの住所を削除してもよろしいですか？');"style="margin-top: 1rem;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">削除する</button>
    </form>
    @endisset
</div>
@endsection