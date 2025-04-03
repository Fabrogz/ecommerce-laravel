<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Act
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        
        // Assert
        $response->assertStatus(200);
    }

    public function test_admin_can_view_orders_list()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $orders = Order::factory()->count(3)->create();
        
        // Act
        $response = $this->actingAs($admin)->get(route('admin.orders.index'));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('orders');
        
        $viewOrders = $response->viewData('orders');
        $this->assertCount(3, $viewOrders);
    }

    public function test_admin_can_view_order_details()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->create();
        
        // Act
        $response = $this->actingAs($admin)->get(route('admin.orders.show', $order));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('order');
        
        $viewOrder = $response->viewData('order');
        $this->assertEquals($order->id, $viewOrder->id);
    }

    public function test_admin_can_update_order_status()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->create(['status' => 'pending']);
        
        // Act
        $response = $this->actingAs($admin)->patch(route('admin.orders.update', $order), [
            'status' => 'paid'
        ]);
        
        // Assert
        $response->assertRedirect(route('admin.orders.index'));
        $this->assertEquals('paid', $order->fresh()->status);
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
}