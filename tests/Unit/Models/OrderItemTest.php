<?php
namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_an_order()
    {
        // Arrange
        $order = Order::factory()->create();
        $orderItem = OrderItem::factory()->create(['order_id' => $order->id]);
        
        // Act & Assert
        $this->assertInstanceOf(Order::class, $orderItem->order);
        $this->assertEquals($order->id, $orderItem->order->id);
    }

    public function test_it_belongs_to_a_product()
    {
        // Arrange
        $product = Product::factory()->create();
        $orderItem = OrderItem::factory()->create(['product_id' => $product->id]);
        
        // Act & Assert
        $this->assertInstanceOf(Product::class, $orderItem->product);
        $this->assertEquals($product->id, $orderItem->product->id);
    }

    public function test_it_can_calculate_subtotal()
    {
        // Arrange
        $orderItem = OrderItem::factory()->create([
            'price' => 75,
            'quantity' => 2
        ]);
        
        // Act
        $subtotal = $orderItem->getSubtotal();
        
        // Assert
        $this->assertEquals(150, $subtotal); // 75 * 2
    }
}