<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_profile()
    {
        // Arrange
        $user = User::factory()->create();
        
        // Act
        $response = $this->actingAs($user)->get(route('profile.show'));
        
        // Assert
        $response->assertStatus(200);
    }

    public function test_user_can_update_profile()
    {
        // Arrange
        $user = User::factory()->create();
        
        // Act
        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nombre Actualizado',
            'email' => $user->email // email stays the same
        ]);
        
        // Assert
        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nombre Actualizado'
        ]);
    }

    public function test_user_can_update_password()
    {
        // Arrange
        $user = User::factory()->create([
            'password' => bcrypt('original_password')
        ]);
        
        // Act
        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'original_password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password'
        ]);
        
        // Assert
        $response->assertRedirect();
        
        // Check if the user can login with the new password
        $this->post('/logout');
        $loginResponse = $this->post('/login', [
            'email' => $user->email,
            'password' => 'new_password'
        ]);
        
        $loginResponse->assertRedirect(route('dashboard'));
    }

    public function test_user_cannot_update_password_with_incorrect_current_password()
    {
        // Arrange
        $user = User::factory()->create([
            'password' => bcrypt('original_password')
        ]);
        
        // Act
        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'wrong_password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password'
        ]);
        
        // Assert
        $response->assertSessionHasErrors('current_password');
    }

    public function test_user_can_view_order_history()
    {
        // Arrange
        $user = User::factory()->create();
        $orders = Order::factory()->count(3)->create(['user_id' => $user->id]);
        
        // Act
        $response = $this->actingAs($user)->get(route('profile.orders'));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('orders');
        
        $viewOrders = $response->viewData('orders');
        $this->assertCount(3, $viewOrders);
    }
}