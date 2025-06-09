<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'address_id' => Address::factory(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'payment_method' => 'credit_card',
            'status' => 'completed',
            'purchased_at' => now(),
        ];
    }
}
