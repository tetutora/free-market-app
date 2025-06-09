<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Favorite;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MypageTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    protected function getMypageResponse()
    {
        return $this->actingAs($this->user)->get(route('mypage'));
    }

    public function test_mypage_display()
    {
        $response = $this->getMypageResponse();

        $response->assertStatus(200);
        $response->assertViewHas('user', $this->user);
    }

    public function test_guest_cannot_access_mypage()
    {
        $response = $this->get(route('mypage'));

        $response->assertRedirect(route('login'));
    }

    public function test_profile_image_is_displayed_correctly()
    {
        $this->user->profile_picture = 'images/test-profile.png';
        $this->user->save();

        $response = $this->getMypageResponse();

        $response->assertSee(asset('storage/images/test-profile.png'));
    }

    public function test_tabs_render_correct_data()
    {
        $response = $this->getMypageResponse();

        $response->assertViewHasAll(['favorites', 'purchases', 'products', 'inTransactions', 'histories', 'followings']);
    }

    public function test_favorites_retrieved_correctly()
    {
        $favoriteProduct = Product::factory()->create();

        Favorite::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $favoriteProduct->id
        ]);

        $response = $this->getMypageResponse();

        $response->assertViewHas('favorites', fn($favorites) => $favorites->contains($favoriteProduct));
    }

    public function test_products_retrieved_correctly()
    {
        $listingProduct = Product::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getMypageResponse();

        $response->assertViewHas('products', fn($products) => $products->contains($listingProduct));
    }

    public function test_purchases_retrieved_correctly()
    {
        $purchaseProduct = Product::factory()->create();

        Purchase::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $purchaseProduct->id
        ]);

        $response = $this->getMypageResponse();

        $response->assertViewHas('purchases', fn($purchases) => $purchases->contains('product_id', $purchaseProduct->id));
    }

    public function test_in_transactions_retrieved_correctly()
    {
        $product = Product::factory()->create(['user_id' => $this->user->id]);

        Purchase::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'status' => 'paid',
        ]);

        $response = $this->getMypageResponse();

        $response->assertViewHas('inTransactions', fn($items) => $items->contains('product_id', $product->id));
    }

    public function test_histories_retrieved_correctly()
    {
        $products = Product::factory()->count(1000)->create();
        foreach ($products as $product) {
            $this->user->viewHistories()->create([
                'product_id' => $product->id,
                'viewed_at' => now()
            ]);
        }

        $response = $this->getMypageResponse();

        $response->assertViewHas('histories', fn($histories) => $histories->count() === 1000);
    }

    public function test_followings_retrieved_correctly()
    {
        $followedUser = User::factory()->create();
        $this->user->followings()->attach($followedUser->id);

        $response = $this->getMypageResponse();

        $response->assertViewHas('followings', fn($followings) => $followings->contains($followedUser));
    }

    public function test_unread_notifications_display_correctly()
    {
        Notification::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'read_at' => null
        ]);

        $response = $this->getMypageResponse();

        $response->assertViewHas('unreadCount', 3);
    }

    public function test_other_users_data_is_not_shown()
    {
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->get(route('mypage'));

        $response->assertViewHas('user', function ($user) use ($otherUser) {
            return $user->id === $otherUser->id;
        });

        $response->assertDontSee($this->user->name);
    }
}
