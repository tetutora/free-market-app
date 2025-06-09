<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_profile_page_displays_correct_user_data()
    {
        $response = $this->actingAs($this->user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertViewHas('user', $this->user);
    }

    public function test_profile_update_reflects_changes()
    {
        $newData = [
            'name' => '新しい名前',
            'email' => 'newemail@example.com',
            'phone' => '08012345678'
        ];

        $response = $this->actingAs($this->user)->patch(route('profile.update'), $newData);

        $this->assertDatabaseHas('users', $newData);
        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'プロフィールが更新されました。');
    }

    public function test_address_can_be_added()
    {
        $newAddress = [
            'postal_code' => '123-4567',
            'prefecture' => '東京都',
            'city' => '渋谷区',
            'street' => '1丁目',
            'building' => '〇〇マンション'
        ];

        $response = $this->actingAs($this->user)->post(route('addresses.store'), $newAddress);

        $this->assertDatabaseHas('addresses', array_merge($newAddress, ['user_id' => $this->user->id]));
        $response->assertRedirect(route('profile.edit'));
    }

    public function test_address_can_be_updated()
    {
        $address = Address::factory()->create(['user_id' => $this->user->id]);

        $updatedData = [
            'postal_code' => $address->postal_code,
            'prefecture'  => $address->prefecture,
            'city'        => $address->city,
            'street'      => '2丁目',
            'building'    => $address->building,
        ];

        $response = $this->actingAs($this->user)->patch(route('addresses.update', $address), $updatedData);

        $this->assertDatabaseHas('addresses', array_merge($updatedData, ['id' => $address->id]));
        $response->assertRedirect(route('profile.edit'));
    }

    public function test_address_can_be_deleted()
    {
        $address = Address::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->delete(route('addresses.destroy', $address));

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
        $response->assertRedirect(route('profile.edit'));
    }

    public function test_guest_cannot_access_profile_edit()
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_profile_picture_can_be_updated()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('profile.jpg');

        $response = $this->actingAs($this->user)->patch(route('profile.update'), [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'profile_picture' => $file,
        ]);

        $storedPath = 'profile_pictures/' . $file->hashName();

        $this->assertDatabaseHas('users', ['profile_picture' => $storedPath]);
        Storage::disk('public')->assertExists($storedPath);

        $response->assertRedirect(route('profile.edit'));
    }

    public function test_profile_update_validates_required_fields()
    {
        $response = $this->actingAs($this->user)->patch(route('profile.update'), []);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_profile_update_redirects_correctly()
    {
        $newData = [
            'name' => 'テストユーザー',
            'email' => $this->user->email,
            'phone' => $this->user->phone,
        ];

        $response = $this->actingAs($this->user)->patch(route('profile.update'), $newData);

        $response->assertRedirect(route('profile.edit'));
    }
}
