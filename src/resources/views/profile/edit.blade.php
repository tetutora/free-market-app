@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
@endsection

@section('content')
<div class="container">
    <h1 class="container__title">プロフィール設定</h1>
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="text-center mb-4">
        <img
            src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('storage/images/default-profile.png') }}"
            data-default="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('storage/images/default-profile.png') }}"
            alt="プロフィール画像"
            class="profile-picture-preview">
    </div>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="form">
        @csrf
        @method('PATCH')
        <x-form.input 
            label="ユーザー名" 
            name="name" 
            :value="$user->name" 
            :error="$errors->first('name')" 
        />

        <x-form.input 
            label="メールアドレス" 
            name="email" 
            type="email" 
            :value="$user->email" 
            :error="$errors->first('email')" 
        />

        <x-form.input 
            label="電話番号" 
            name="phone" 
            :value="$user->phone" 
            :error="$errors->first('phone')" 
        />

        <div class="form-group">
            <label for="profile_picture" class="form-group__label">プロフィール写真</label>
            <input type="file" id="profile_picture" name="profile_picture" style="display:none;">
            <label for="profile_picture" class="btn btn-secondary" style="cursor:pointer;">画像を選択</label>
            @error('profile_picture')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <h3 class="address-section__title">住所情報</h3>

        @php
            $addresses = $user->addresses;
        @endphp

        @if ($addresses->isNotEmpty())
            <x-form.address 
                :address="$addresses->first()" 
                prefix="addresses[0]" 
                :errors="$errors" 
                label="住所 1" 
            />

            @if ($addresses->count() > 1)
                <div class="mt-4">
                    @foreach ($addresses->skip(1) as $index => $address)
                        <a href="{{ route('addresses.edit', ['address' => $address->id]) }}">
                            住所 {{ $index + 1 }} （{{ $address->prefecture }} {{ $address->city }} {{ $address->street }}）
                        </a>
                    @endforeach
                </div>
            @endif
        @else
            <p>住所が登録されていません。</p>
        @endif

        <a href="{{ route('addresses.create') }}" class="btn btn-secondary mt-3">住所を追加</a>
        <button type="submit" class="btn btn-primary mt-4">更新</button>
    </form>
</div>
@endsection

@section('js')
<script src="{{ asset('js/profile.js') }}"></script>
@endsection
