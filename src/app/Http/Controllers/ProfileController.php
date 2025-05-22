<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $addresses = $user->addresses;

        return view('profile.edit', compact('user', 'addresses'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $user->updateProfile(
            $request->validated(),
            $request->file('profile_picture')
        );

        return redirect()->route('profile.edit')->with('success', 'プロフィールが更新されました。');
    }

    public function showMypage()
    {
        return view('profile.mypage');
    }
}
