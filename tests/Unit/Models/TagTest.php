<?php
namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_name_attribute()
    {
        // Arrange
        $tag = Tag::factory()->create(['name' => 'Oferta']);
        
        // Act & Assert
        $this->assertEquals('Oferta', $tag->name);
    }

    public function test_it_has_many_products()
    {
        // Arrange
        $tag = Tag::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        
        // Act
        $tag->products()->attach([$product1->id, $product2->id]);
        
        // Assert
        $this->assertCount(2, $tag->products);
        $this->assertTrue($tag->products->contains($product1));
        $this->assertTrue($tag->products->contains($product2));
    }

    public function test_it_can_find_by_name()
    {
        // Arrange
        Tag::factory()->create(['name' => 'Oferta']);
        Tag::factory()->create(['name' => 'Nuevo']);
        
        // Act
        $tag = Tag::findByName('Oferta');
        
        // Assert
        $this->assertNotNull($tag);
        $this->assertEquals('Oferta', $tag->name);
    }
}