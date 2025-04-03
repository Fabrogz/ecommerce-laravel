<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_search_products_by_name()
    {
        // Arrange
        $productA = Product::factory()->create(['name' => 'Smartphone Samsung']);
        $productB = Product::factory()->create(['name' => 'Laptop Dell']);
        
        // Act
        $response = $this->get(route('products.index', ['search' => 'Samsung']));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('products', function($products) use ($productA, $productB) {
            return $products->contains($productA) && !$products->contains($productB);
        });
    }

    public function test_user_can_filter_products_by_category()
    {
        // Arrange
        $categoryA = Category::factory()->create(['name' => 'Electrónicos']);
        $categoryB = Category::factory()->create(['name' => 'Ropa']);
        
        $productA = Product::factory()->create(['category_id' => $categoryA->id]);
        $productB = Product::factory()->create(['category_id' => $categoryB->id]);
        
        // Act
        $response = $this->get(route('products.index', ['category' => $categoryA->id]));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('products', function($products) use ($productA, $productB) {
            return $products->contains($productA) && !$products->contains($productB);
        });
    }

    public function test_user_can_filter_products_by_price_range()
    {
        // Arrange
        $productA = Product::factory()->create(['price' => 50]);
        $productB = Product::factory()->create(['price' => 150]);
        $productC = Product::factory()->create(['price' => 300]);
        
        // Act
        $response = $this->get(route('products.index', ['min_price' => 100, 'max_price' => 200]));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('products', function($products) use ($productA, $productB, $productC) {
            return !$products->contains($productA) && 
                   $products->contains($productB) && 
                   !$products->contains($productC);
        });
    }

    public function test_user_can_filter_products_by_tag()
    {
        // Arrange
        $tag = Tag::factory()->create(['name' => 'Oferta']);
        
        $productA = Product::factory()->create();
        $productB = Product::factory()->create();
        
        $productA->tags()->attach($tag);
        
        // Act
        $response = $this->get(route('products.index', ['tag' => $tag->id]));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('products', function($products) use ($productA, $productB) {
            return $products->contains($productA) && !$products->contains($productB);
        });
    }

    public function test_search_returns_empty_results_for_nonexistent_products()
    {
        // Arrange
        Product::factory()->create(['name' => 'Smartphone Samsung']);
        
        // Act
        $response = $this->get(route('products.index', ['search' => 'Producto Inexistente']));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('products', function($products) {
            return $products->isEmpty();
        });
    }
}