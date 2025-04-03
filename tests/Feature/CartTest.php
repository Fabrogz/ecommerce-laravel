<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class CartTest extends TestCase
{
    /** @test */
    public function it_adds_product_to_cart()
    {
        $product = Product::factory()->create();
        
        $response = $this->post('/cart/add', [
            'product_id' => $product->id
        ]);
        
        $response->assertRedirect();
        $this->assertArrayHasKey($product->id, session('cart'));
    }
}