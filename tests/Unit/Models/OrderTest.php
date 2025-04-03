<?php
namespace Tests\Unit\Models;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class OrderTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_belongs_to_a_user()
    {
        // Arrange
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        // Act & Assert
        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }
    public function test_it_has_many_order_items()
    {
        // Arrange
        $order = Order::factory()->create();
        $product1 = Product::factory()->create(['price' => 100]);
        $product2 = Product::factory()->create(['price' => 200]);
        
        // Act
        $order->items()->create([
            'product_id' => $product1->id,
            'quantity' => 2,
            'price' => $product1->price
        ]);
        
        $order->items()->create([
            'product_id' => $product2->id,
            'quantity' => 1,
            'price' => $product2->price
        ]);
        // Assert
        $this->assertCount(2, $order->items);
        $this->assertEquals($product1->id, $order->items[0]->product_id);
        $this->assertEquals($product2->id, $order->items[1]->product_id);
    }
    public function test_it_can_calculate_total()
    {
        // Arrange
        $order = Order::factory()->create();
        $product1 = Product::factory()->create(['price' => 100]);
        $product2 = Product::factory()->create(['price' => 200]);
        
        $order->items()->create([
            'product_id' => $product1->id,
            'quantity' => 2,
            'price' => $product1->price
        ]);
        
        $order->items()->create([
            'product_id' => $product2->id,
            'quantity' => 1,
            'price' => $product2->price
        ]);
        // Act
        $total = $order->calculateTotal();
        // Assert
        $this->assertEquals(400, $total); // (100*2 + 200*1)
    }
    public function test_it_has_status_attribute()
    {
        // Arrange
        $order = Order::factory()->create(['status' => 'pending']);
        // Act & Assert
        $this->assertEquals('pending', $order->status);
    }
    public function test_it_can_determine_if_is_paid()
    {
        // Arrange
        $paidOrder = Order::factory()->create(['status' => 'paid']);
        $pendingOrder = Order::factory()->create(['status' => 'pending']);
        
        // Act & Assert
        $this->assertTrue($paidOrder->isPaid());
        $this->assertFalse($pendingOrder->isPaid());
    }
    
    public function test_it_can_determine_if_is_completed()
    {
        // Arrange
        $completedOrder = Order::factory()->create(['status' => 'completed']);
        $pendingOrder = Order::factory()->create(['status' => 'pending']);
        
        // Act & Assert
        $this->assertTrue($completedOrder->isCompleted());
        $this->assertFalse($pendingOrder->isCompleted());
    }
    
    public function test_it_can_get_orders_by_status()
    {
        // Arrange
        $pendingOrder1 = Order::factory()->create(['status' => 'pending']);
        $pendingOrder2 = Order::factory()->create(['status' => 'pending']);
        $paidOrder = Order::factory()->create(['status' => 'paid']);
        
        // Act
        $pendingOrders = Order::byStatus('pending')->get();
        
        // Assert
        $this->assertCount(2, $pendingOrders);
        $this->assertTrue($pendingOrders->contains($pendingOrder1));
        $this->assertTrue($pendingOrders->contains($pendingOrder2));
        $this->assertFalse($pendingOrders->contains($paidOrder));
    }
}