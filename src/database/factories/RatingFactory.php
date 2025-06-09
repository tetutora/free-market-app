<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'purchase_id' => Purchase::factory(),
            'user_id' => User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'is_seller' => $this->faker->boolean(),
            'comment' => $this->faker->sentence(),
        ];
    }
}
