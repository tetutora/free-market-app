<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'price', 'image_path',
        'category_id', 'condition', 'is_listed', 'brand_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public static function search(array $params)
    {
        $query = self::query();

        if (!empty($params['keyword'])) {
            $query->where('name', 'like', '%' . $params['keyword'] . '%');
        }

        if (!empty($params['category_id'])) {
            $query->where('category_id', $params['category_id']);
        } elseif (!empty($params['parent_category_id'])) {
            $childCategoryIds = Category::where('parent_id', $params['parent_category_id'])->pluck('id')->toArray();

            if (!empty($childCategoryIds)) {
                $query->whereIn('category_id', $childCategoryIds);
            } else {
                $query->where('category_id', $params['parent_category_id']);
            }
        }

        if (!empty($params['brand_id'])) {
            $query->where('brand_id', $params['brand_id']);
        }

        if (isset($params['is_listed']) && $params['is_listed'] !== '') {
            $query->where('is_listed', $params['is_listed']);
        }

        return $query->with(['brand', 'category']);
    }
}
