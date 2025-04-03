<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_a_name_attribute()
    {
        // Arrange
        $product = Product::factory()->create(['name' => 'Test Product']);
        
        // Act & Assert
        $this->assertEquals('Test Product', $product->name);
    }

    public function test_it_belongs_to_a_category()
    {
        // Arrange
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        
        // Act & Assert
        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    public function test_it_can_have_tags()
    {
        // Arrange
        $product = Product::factory()->create();
        $tag = Tag::factory()->create();
        
        // Act
        $product->tags()->attach($tag);
        
        // Assert
        $this->assertCount(1, $product->tags);
        $this->assertEquals($tag->id, $product->tags->first()->id);
    }
}
