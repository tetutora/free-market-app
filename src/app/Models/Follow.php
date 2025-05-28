<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = [
        'follower_id',
        'followed_id',
        'followed_at',
    ];

    public static function toggle(User $follower, User $followed)
    {
        if ($follower->id === $followed->id) {
            return [false, '自分自身はフォローできません。'];
        }

        if ($follower->isFollowing($followed->id)) {
            $follower->followings()->detach($followed->id);
            return [true, 'フォローを解除しました。'];
        }

        $follower->followings()->attach($followed->id, ['followed_at' => now()]);
        return [true, 'フォローしました！'];
    }
}
