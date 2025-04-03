<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
// tests/Feature/Auth/PasswordResetTest.php

class PasswordResetTest extends TestCase
{
    use RefreshDatabase; // ¡Esto es crucial!

    public function test_reset_password_link_screen_can_be_rendered()
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'test'.uniqid().'@example.com' // Email único
        ]);

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo(
            $user,
            ResetPassword::class
        );
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'test'.uniqid().'@example.com' // Email único
        ]);

        $token = app('auth.password.broker')->createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertSessionHasNoErrors();
    }
}