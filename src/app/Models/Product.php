<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;

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
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $params['category_id']));
        } elseif (!empty($params['parent_category_id'])) {
            $childCategoryIds = Category::where('parent_id', $params['parent_category_id'])->pluck('id')->toArray();

            $query->whereHas('categories', function ($q) use ($params, $childCategoryIds) {
                $q->whereIn('categories.id', $childCategoryIds ?: [$params['parent_category_id']]);
            });
        }

        if (!empty($params['brand_id'])) {
            $query->whereHas('brands', fn ($q) => $q->where('brands.id', $params['brand_id']));
        }

        if (isset($params['is_listed']) && $params['is_listed'] !== '') {
            $query->where('is_listed', $params['is_listed']);
        }

        return $query->with(['brands', 'categories']);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function recordViewHistory($userId)
    {
        History::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $this->id],
            ['viewed_at' => now()]
        );
    }

    public function getOtherProductsFromSameUser($limit = 10)
    {
        return self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->latest()
            ->take($limit)
            ->get();
    }

    public static function createWithRelations($request, $userId)
    {
        $validated = $request->validated();

        $product = self::create([
            'user_id' => $userId,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'is_listed' => true,
        ]);

        $product->categories()->sync($validated['category_ids'] ?? []);
        $product->brands()->sync($validated['brand_ids'] ?? []);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return $product;
    }

    public function createKonbiniPaymentIntent($user, $addressId)
{
    Stripe::setApiKey(config('services.stripe.secret'));

    $purchase = \App\Models\Purchase::create([
    'user_id' => $user->id,
    'product_id' => $this->id,
    'address_id' => $addressId,
    'price' => $this->price,
    'payment_method' => 'konbini',
    'status' => 'purchased',
    'purchased_at' => now(),
]);

\Log::info('Purchase created', ['purchase' => $purchase->toArray(), 'purchase_id' => $purchase->id]);



if (empty($purchase->id)) {
    \Log::error('Purchase ID is empty!');
    throw new \Exception('Purchase creation failed: ID is null.');
}

$name = $user->name ?? 'No name';
$email = $user->email ?? 'no-email@example.com';

$paymentIntent = PaymentIntent::create([
    'amount' => (int)$this->price,
    'currency' => 'jpy',
    'payment_method_types' => ['konbini'],
    'metadata' => [
        'purchase_id' => (string)$purchase->id,
        'user_id' => (string)$user->id,
        'product_id' => (string)$this->id,
        'address_id' => (string)$addressId,
    ],
    'payment_method_data' => [
        'type' => 'konbini',
        'billing_details' => [
            'name' => $name,
            'email' => $email,
        ],
    ],
    'payment_method_options' => [
        'konbini' => [
            'expires_after_days' => 7,
        ],
    ],
    'confirm' => true,
]);

\Log::info('PaymentIntent full data:', $paymentIntent->toArray());


    return $paymentIntent;
}


    public function createCheckoutSession($user, $addressId)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $purchase = \App\Models\Purchase::create([
            'user_id' => $user->id,
            'product_id' => $this->id,
            'address_id' => $addressId,
            'price' => $this->price,
            'payment_method' => 'card',
            'status' => 'purchased',
            'purchased_at' => now(),
        ]);

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $this->name,
                    ],
                    'unit_amount' => $this->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'metadata' => [ // これはCheckout Sessionのmetadata
                'product_id' => $this->id,
                'user_id' => $user->id,
                'address_id' => $addressId,
                'purchase_id' => $purchase->id,
            ],
            'payment_intent_data' => [  // ここでPaymentIntentにmetadataを設定
                'metadata' => [
                    'purchase_id' => $purchase->id,
                    'product_id' => $this->id,
                    'user_id' => $user->id,
                    'address_id' => $addressId,
                ],
            ],
            'success_url' => route('purchase.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('products.show', $this),
        ]);
    }

    public function markAsSold()
    {
        $this->update(['is_listed' => false]);
    }
}