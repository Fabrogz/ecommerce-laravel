<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        // Arrange
        $userData = [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@ejemplo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        
        // Act
        $response = $this->post(route('register'), $userData);
        
        // Assert
        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@ejemplo.com',
        ]);
        $this->assertDatabaseHas('users', [
            'role' => 'customer' // Default role for new users
        ]);
    }

    public function test_user_can_login()
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'usuario@ejemplo.com',
            'password' => bcrypt('password123')
        ]);
        
        // Act
        $response = $this->post(route('login'), [
            'email' => 'usuario@ejemplo.com',
            'password' => 'password123'
        ]);
        
        // Assert
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout()
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Act
        $response = $this->post(route('logout'));
        
        // Assert
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_admin_can_access_admin_dashboard()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Act
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        
        // Assert
        $response->assertStatus(200);
    }

    public function test_customer_cannot_access_admin_dashboard()
    {
        // Arrange
        $customer = User::factory()->create(['role' => 'customer']);
        
        // Act
        $response = $this->actingAs($customer)->get(route('admin.dashboard'));
        
        // Assert
        $response->assertForbidden();
    }

    public function test_user_with_invalid_credentials_cannot_login()
    {
        // Arrange
        User::factory()->create([
            'email' => 'usuario@ejemplo.com',
            'password' => bcrypt('password123')
        ]);
        
        // Act
        $response = $this->post(route('login'), [
            'email' => 'usuario@ejemplo.com',
            'password' => 'wrong_password'
        ]);
        
        // Assert
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}