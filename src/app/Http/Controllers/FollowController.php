<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FollowController extends Controller
{
    public function toggleFollow(User $user)
    {
        $authUser = auth()->user();

        if ($authUser->id === $user->id) {
            return redirect()->back()->with('error', '自分自身はフォローできません。');
        }

        if ($authUser->isFollowing($user->id)) {
            // フォロー解除
            $authUser->followings()->detach($user->id);
            $message = 'フォローを解除しました。';
        } else {
            // フォロー
            $authUser->followings()->attach($user->id, ['followed_at' => now()]);
            $message = 'フォローしました！';
        }

        return redirect()->back()->with('status', $message);
    }
}
