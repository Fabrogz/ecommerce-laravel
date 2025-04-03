<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_required_attributes()
    {
        // Arrange
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'customer'
        ]);

        // Act & Assert
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('customer', $user->role);
    }

    public function test_it_can_check_if_is_admin()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        // Act & Assert
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($customer->isAdmin());
    }

    public function test_it_has_many_orders()
    {
        // Arrange
        $user = User::factory()->create();
        $order1 = Order::factory()->create(['user_id' => $user->id]);
        $order2 = Order::factory()->create(['user_id' => $user->id]);

        // Act & Assert
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->orders);
        $this->assertCount(2, $user->orders);
        $this->assertTrue($user->orders->contains($order1));
        $this->assertTrue($user->orders->contains($order2));
    }

    public function test_it_has_cart_items()
    {
        // Arrange
        $user = User::factory()->create();
        
        // Act - This assumes you have a method to add items to cart
        $user->addToCart(['product_id' => 1, 'quantity' => 2]);
        
        // Assert
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->cartItems);
        $this->assertCount(1, $user->cartItems);
        $this->assertEquals(1, $user->cartItems->first()->product_id);
        $this->assertEquals(2, $user->cartItems->first()->quantity);
    }
}