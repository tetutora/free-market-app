<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'postal_code', 'prefecture', 'city', 'street', 'town', 'building'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function createForUser(array $data, int $userId)
    {
        $data['user_id'] = $userId;
        return self::create($data);
    }

    public function updateForUser(array $data, int $userId)
    {
        if ($this->user_id !== $userId) {
            abort(403, 'この住所を更新する権限がありません。');
        }
        return $this->update($data);
    }

    public function deleteForUser(int $userId)
    {
        if ($this->user_id !== $userId) {
            abort(403, 'この住所を削除する権限がありません。');
        }
        return $this->delete();
    }
}
