<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductDisplayTest extends TestCase
{
    use RefreshDatabase;

    private function assertProductSummaryDisplayed($response, Product $product)
    {
        $response->assertSee($product->name)
            ->assertSee(number_format($product->price));
    }
    private function assertProductDisplayed($response, Product $product)
    {
        $response->assertSee($product->name)
            ->assertSee(number_format($product->price))
            ->assertSee($product->condition ?? '')
            ->assertSee($product->description ?? '');
    }

    public function test_product_content_are_displayed()
    {
        $product = Product::factory()->hasImages(1)->create([
            'price' => 12345,
        ]);
        $response = $this->get(route('products.index'));

        $this->assertProductSummaryDisplayed($response, $product);
        $response->assertSee('<img', false);
    }

    public function test_product_link_detail_page()
    {
        $product = Product::factory()->create();
        $response = $this->get(route('products.index'));

        $response->assertSee(route('products.show', $product));
    }

    public function test_product_detail_diplayed_all_info()
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $product = Product::factory()
            ->hasAttached($brand)
            ->hasAttached($category)
            ->create([
                'condition' => '新品・未使用',
                'description' => 'これは説明文です。',
            ]);
        $response = $this ->get(route('products.show', $product));

        $this->assertProductDisplayed($response, $product);
        $response->assertSee($brand->name)
            ->assertSee($category->name);
    }

    public function test_can_search_by_keyword()
    {
        Product::factory()->create(['name' => 'テスト商品A']);
        Product::factory()->create(['name' => '他の商品']);

        $response = $this->get(route('products.index', ['keyword' => 'テスト']));

        $response->assertSee('テスト商品A');
        $response->assertDontSee('他の商品');
    }

    public function test_can_filter_by_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create();
        $product->categories()->attach($category);

        $otherProduct = Product::factory()->create();

        $response = $this->get(route('products.index', ['category_id' => $category->id]));

        $response->assertSee($product->name);
        $response->assertDontSee($otherProduct->name);
    }

    public function test_can_filter_by_brand()
    {
        $brand = Brand::factory()->create();
        $product = Product::factory()->create();
        $product->brands()->attach($brand);

        $response = $this->get(route('products.index', ['brand_id' => $brand->id]));

        $response->assertSee($product->name);
    }

    public function test_can_filter_by_listing_status()
    {
        $listed = Product::factory()->create(['is_listed' => true]);
        $unlisted = Product::factory()->create(['is_listed' => false]);

        $response = $this->get(route('products.index', ['is_listed' => 1]));

        $response->assertSee($listed->name);
        $response->assertDontSee($unlisted->name);
    }
}
