<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $users = User::all();
        $categories = Category::all();
        $brands = Brand::all();

        if ($users->isEmpty() || $categories->isEmpty() || $brands->isEmpty()) {
            $this->command->error('Users, Categories or Brands table is empty. Please seed them first.');
            return;
        }

        // あなたの提示された画像URLリスト
        $sampleImages = [
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
        ];

        for ($i = 0; $i < 100; $i++) {
            Product::create([
                'user_id' => $users->random()->id,
                'name' => $faker->words(3, true),
                'description' => $faker->sentence(10),
                'price' => $faker->numberBetween(1000, 100000),
                'image_path' => $faker->randomElement($sampleImages),
                'category_id' => $categories->random()->id,
                'condition' => $faker->randomElement(['新品', '中古', '未使用']),
                'is_listed' => true,
                'brand_id' => $brands->random()->id,
            ]);
        }
    }
}