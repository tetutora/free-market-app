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
        User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'phone' => '09012345678',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        User::factory()->count(10)->create();
    }
}
