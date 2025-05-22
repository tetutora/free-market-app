<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Address;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    // プロフィール編集フォームの表示
    public function edit()
    {
        $user = Auth::user();
        $addresses = $user->addresses ?: [];

        return view('profile.edit', compact('user', 'addresses'));
    }

    // プロフィール更新処理
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        // プロフィール写真のアップロード処理
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        // ユーザー情報の更新
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? '',
            'profile_picture' => $validated['profile_picture'] ?? $user->profile_picture,
        ]);

        // 住所情報の更新
        if (isset($validated['addresses'])) {
            foreach ($validated['addresses'] as $addressData) {
                // 少なくとも1項目が入力されていれば保存処理
                if (collect($addressData)->filter()->isNotEmpty()) {
                    $user->addresses()->updateOrCreate(
                        ['user_id' => $user->id, 'postal_code' => $addressData['postal_code']],
                        $addressData
                    );
                }
            }
        }

        return redirect()->route('profile.edit')->with('success', 'プロフィールが更新されました。');
    }

    public function showMypage()
    {
        return view('profile.mypage');
    }
}
