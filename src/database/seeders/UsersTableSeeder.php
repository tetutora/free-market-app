<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::factory()->create([
            'name' => 'テストユーザー1',
            'email' => 'test@example.com',
            'phone' => '09012345678',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);
        $user1->addresses()->create([
            'postal_code' => '123-4567',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'street' => '西新宿1-1-1',
            'town' => null,
            'building' => '新宿ビル101',
        ]);

        $user2 = User::factory()->create([
            'name' => 'テストユーザー2',
            'email' => 'test2@example.com',
            'phone' => '0908765-4321',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);
        $user2->addresses()->create([
            'postal_code' => '987-6543',
            'prefecture' => '大阪府',
            'city' => '大阪市北区',
            'street' => '梅田3-3-3',
            'town' => null,
            'building' => '梅田タワー202',
        ]);

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($user) {
                $user->addresses()->create([
                    'postal_code' => '000-0000',
                    'prefecture' => '未設定',
                    'city' => '未設定',
                    'street' => null,
                    'town' => null,
                    'building' => null,
                ]);
            });
    }
}
