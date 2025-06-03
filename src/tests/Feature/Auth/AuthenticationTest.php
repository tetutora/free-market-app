<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receives_verification_email()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'SecurePass123',
            'password_confirmation' => 'SecurePass123',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);

        Notification::assertSentTo($user, VerifyEmail::class);

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_user_can_verify_email()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect('/profile?verified=1');
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_unverified_user_cannot_access_mypage()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_verified_user_can_login_and_access_mypage()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'password' => Hash::make('SecurePass123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'SecurePass123',
        ]);

        $response->assertRedirect(route('mypage'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_register_validation_errors_are_displayed()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors([
            'name',
            'email',
            'password',
        ]);

        $response = $this->get('/register');

        $response->assertSee('名前を入力してください。');
        $response->assertSee('メールアドレスを入力してください。');
        $response->assertSee('パスワードを入力してください。');
    }

    public function test_login_validation_errors_are_displayed()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email', 'password']);

        $response = $this->get('/login');
        $response->assertSee('メールアドレスを入力してください。');
        $response->assertSee('パスワードを入力してください。');
    }

    public function test_register_password_min_length_validation_message()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'User',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/register');
        $response->assertSee('パスワードは8文字以上で入力してください。');
    }

    public function test_register_password_confirmation_mismatch_message()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'User',
            'email' => 'test@example.com',
            'password' => 'ValidPassword123',
            'password_confirmation' => 'DifferentPassword123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/register');
        $response->assertSee('パスワード確認が一致しません。');
    }

    public function test_login_validation_messages()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'not-an-email',
            'password' => '',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email', 'password']);

        $response = $this->get('/login');

        $response->assertSee('有効なメールアドレスを入力してください。');
        $response->assertSee('パスワードを入力してください。');
    }

    public function test_register_validation_max_length_and_unique_email()
    {
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        $response = $this->from('/register')->post('/register', [
            'name' => str_repeat('あ', 256),
            'email' => 'duplicate@example.com',
            'password' => 'ValidPassword123',
            'password_confirmation' => 'ValidPassword123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['name', 'email']);

        $response = $this->get('/register');

        $response->assertSee('名前は255文字以内で入力してください。');
        $response->assertSee('このメールアドレスはすでに登録されています。');
    }

}
