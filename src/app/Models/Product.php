<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'price', 'image_path',
        'condition', 'is_listed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
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

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function scopeSearch($query, array $params)
    {
        if (!empty($params['keyword'])) {
            $query->where('name', 'like', '%' . $params['keyword'] . '%');
        }

        if (!empty($params['category_id'])) {
            $query->whereHas('categories', function ($q) use ($params) {
                $q->where('categories.id', $params['category_id']);
            });
        } elseif (!empty($params['parent_category_id'])) {
            $childCategoryIds = Category::where('parent_id', $params['parent_category_id'])->pluck('id')->toArray();
            if (!empty($childCategoryIds)) {
                $query->whereHas('categories', function ($q) use ($childCategoryIds) {
                    $q->whereIn('categories.id', $childCategoryIds);
                });
            } else {
                $query->whereHas('categories', function ($q) use ($params) {
                    $q->where('categories.id', $params['parent_category_id']);
                });
            }
        }

        if (!empty($params['brand_id'])) {
            $query->whereHas('brands', function ($q) use ($params) {
                $q->where('brands.id', $params['brand_id']);
            });
        }

        if (isset($params['is_listed']) && $params['is_listed'] !== '') {
            $query->where('is_listed', $params['is_listed']);
        }

        return $query->with(['brands', 'categories']);
    }
}
