<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_product_to_cart()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 50, 'stock' => 5]);
        
        // Act
        $response = $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2
        ]);
        
        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);
    }

    public function test_user_cannot_add_more_than_available_stock()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 3]);
        
        // Act
        $response = $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 5
        ]);
        
        // Assert
        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);
    }

    public function test_user_can_update_cart_quantity()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);
        
        // Add product to cart first
        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1
        ]);
        
        $cartItem = $user->cartItems()->first();
        
        // Act - Update quantity
        $response = $this->actingAs($user)->patch(route('cart.update', $cartItem), [
            'quantity' => 3
        ]);
        
        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 3
        ]);
    }

    public function test_user_can_remove_product_from_cart()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create();
        
        // Add product to cart first
        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1
        ]);
        
        $cartItem = $user->cartItems()->first();
        
        // Act
        $response = $this->actingAs($user)->delete(route('cart.remove', $cartItem));
        
        // Assert
        $response->assertRedirect();
        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id
        ]);
    }

    public function test_cart_calculates_correct_totals()
    {
        // Arrange
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['price' => 50]);
        $product2 = Product::factory()->create(['price' => 75]);
        
        // Add products to cart
        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product1->id,
            'quantity' => 2
        ]);
        
        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product2->id,
            'quantity' => 1
        ]);
        
        // Act
        $response = $this->actingAs($user)->get(route('cart.show'));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('subtotal', 175); // (50*2 + 75*1)
        $response->assertViewHas('tax', 16.625); // Assuming 9.5% tax rate
        $response->assertViewHas('total', 191.625); // 175 + 16.625
    }
}