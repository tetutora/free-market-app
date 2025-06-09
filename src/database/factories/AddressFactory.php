<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'postal_code' => $this->faker->postcode(),
            'prefecture' => $this->faker->randomElement([
                '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
                '東京都', '大阪府', '愛知県', '京都府', '福岡県', '沖縄県'
            ]),
            'city' => $this->faker->city(),
            'street' => $this->faker->streetAddress(),
            'town' => $this->faker->secondaryAddress(),
            'building' => $this->faker->buildingNumber(),
        ];
    }
}
