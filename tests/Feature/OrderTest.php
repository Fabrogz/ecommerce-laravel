<?php
namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_order()
    {
        $user = User::factory()->create();
        $products = Product::factory()->count(3)->create();
        
        $this->actingAs($user)
             ->post('/checkout', [
                 'products' => $products->pluck('id')->toArray()
             ]);
        
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => $products->sum('price')
        ]);
    }
}