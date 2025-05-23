<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function viewHistories()
    {
        return $this->hasMany(History::class);
    }

    public function notices()
    {
        return $this->hasMany(Notification::class);
    }

    public function isFollowing($userId)
    {
        return $this->followings()->where('followed_id', $userId)->exists();
    }

    public function followings()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'follower_id',
            'followed_id'
        )->withPivot('followed_at')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'followed_id',
            'follower_id'
        )->withPivot('followed_at')->withTimestamps();
    }


    public function updateProfile(array $data, $profilePicture = null)
    {
        if ($profilePicture) {
            if ($this->profile_picture) {
                Storege::disk('public')->delete($this->profile_picture);
            }
            $path = $profilePicture->store('profile_pictures', 'public');
            $data['profile_picture'] = $path;
        }

        $this->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'profile_picture' => $data['profile_picture'] ?? $this->profile_picture,
        ]);

        $this->save();

        return $this;
    }
}
