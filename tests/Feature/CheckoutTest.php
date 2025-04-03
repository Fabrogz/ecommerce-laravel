<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_proceed_to_checkout()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100, 'stock' => 5]);
        
        // Add to cart
        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2
        ]);
        
        // Act
        $response = $this->actingAs($user)->get(route('checkout.show'));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('cartItems');
        $response->assertViewHas('total');
    }

    public function test_guest_is_redirected_to_login_when_checkout()
    {
        // Act
        $response = $this->get(route('checkout.show'));
        
        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_complete_order()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100, 'stock' => 5]);
        
        // Add to cart
        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2
        ]);
        
        // Act
        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'name' => 'Cliente Prueba',
            'email' => 'cliente@ejemplo.com',
            'address' => 'Calle de Prueba 123',
            'payment_method' => 'credit_card',
            'payment_simulation' => 'success' // Simulación de pago exitoso
        ]);
        
        // Assert
        $response->assertRedirect(route('checkout.success'));
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => 200 // 100 * 2
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100
        ]);
        
        // Verify product stock was reduced
        $this->assertEquals(3, $product->fresh()->stock);
        
        // Verify cart was emptied
        $this->assertCount(0, $user->cartItems);
    }

    public function test_cannot_checkout_with_insufficient_stock()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100, 'stock' => 1]);
        
        // Add to cart with more quantity than available
        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2
        ]);
        
        // Act
        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'name' => 'Cliente Prueba',
            'email' => 'cliente@ejemplo.com',
            'address' => 'Calle de Prueba 123',
            'payment_method' => 'credit_card'
        ]);
        
        // Assert
        $response->assertSessionHasErrors(['stock']);
        $this->assertDatabaseMissing('orders', ['user_id' => $user->id]);
    }

    public function test_order_creates_log_entry()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100, 'stock' => 5]);
        
        // Add to cart
        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2
        ]);
        
        // Act
        $this->actingAs($user)->post(route('checkout.process'), [
            'name' => 'Cliente Prueba',
            'email' => 'cliente@ejemplo.com',
            'address' => 'Calle de Prueba 123',
            'payment_method' => 'credit_card',
            'payment_simulation' => 'success'
        ]);
        
        $order = Order::latest()->first();
        
        // Assert
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'created',
            'loggable_type' => Order::class,
            'loggable_id' => $order->id
        ]);
    }
}