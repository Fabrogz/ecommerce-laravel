<?php
namespace Tests\Unit\Models;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_a_user()
    {
        // Arrange
        $user = User::factory()->create();
        $cartItem = CartItem::factory()->create(['user_id' => $user->id]);
        
        // Act & Assert
        $this->assertInstanceOf(User::class, $cartItem->user);
        $this->assertEquals($user->id, $cartItem->user->id);
    }

    public function test_it_belongs_to_a_product()
    {
        // Arrange
        $product = Product::factory()->create();
        $cartItem = CartItem::factory()->create(['product_id' => $product->id]);
        
        // Act & Assert
        $this->assertInstanceOf(Product::class, $cartItem->product);
        $this->assertEquals($product->id, $cartItem->product->id);
    }

    public function test_it_can_calculate_subtotal()
    {
        // Arrange
        $product = Product::factory()->create(['price' => 50]);
        $cartItem = CartItem::factory()->create([
            'product_id' => $product->id,
            'quantity' => 3
        ]);
        
        // Act
        $subtotal = $cartItem->getSubtotal();
        
        // Assert
        $this->assertEquals(150, $subtotal); // 50 * 3
    }
}