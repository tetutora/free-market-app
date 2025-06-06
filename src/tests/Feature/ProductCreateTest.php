<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_create_page()
    {
        $response = $this->get(route('products.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_create_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('products.create'));

        $response->assertStatus(200);
        $response->assertSee('商品を出品する');
    }

    public function test_validation_errors_are_shown()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('products.store'), []);

        $response->assertSessionHasErrors([
            'name', 'description', 'price', 'images', 'category_ids', 'brand_ids', 'condition'
        ]);
    }

    public function test_product_can_be_created_successfully()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $categories = Category::factory()->count(2)->create();
        $brands = Brand::factory()->count(2)->create();

        $data = [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 5000,
            'images' => [
                UploadedFile::fake()->image('test1.jpg'),
                UploadedFile::fake()->image('test2.jpg')
            ],
            'category_ids' => $categories->pluck('id')->toArray(),
            'brand_ids' => $brands->pluck('id')->toArray(),
            'condition' => '新品・未使用',
        ];

        $response = $this->actingAs($user)->post(route('products.store'), $data);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 5000,
            'condition' => '新品・未使用',
            'user_id' => $user->id,
        ]);

        // 関連の確認
        $product = \App\Models\Product::where('name', 'テスト商品')->first();
        $this->assertNotNull($product);
        $this->assertCount(2, $product->categories);
        $this->assertCount(2, $product->brands);
        $this->assertCount(2, $product->images);
    }
}
