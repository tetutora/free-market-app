<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'address_id',
        'price',
        'payment_method',
        'status',
        'purchased_at',
    ];

    protected $dates = ['purchased_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public static function createFromStripeData($metadata, $price, $paymentMethod, $status)
    {
        $existingPurchase = self::where('user_id', $metadata->user_id)
                                ->where('product_id', $metadata->product_id)
                                ->where('status', '<>', 'completed')
                                ->first();

        if ($existingPurchase) {
            return $existingPurchase; // すでに取引がある場合は新規作成しない
        }

        return self::create([
            'user_id' => $metadata->user_id,
            'product_id' => $metadata->product_id,
            'address_id' => $metadata->address_id,
            'price' => $price,
            'payment_method' => $paymentMethod,
            'status' => $status,
            'purchased_at' => Carbon::now(),
        ]);
    }
}
