<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_a_name_attribute()
    {
        // Arrange
        $category = Category::factory()->create(['name' => 'Electronics']);

        // Act & Assert
        $this->assertEquals('Electronics', $category->name);
    }

    public function test_it_has_many_products()
    {
        // Arrange
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->id]);
        $product2 = Product::factory()->create(['category_id' => $category->id]);

        // Act & Assert
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $category->products);
        $this->assertCount(2, $category->products);
        $this->assertTrue($category->products->contains($product1));
        $this->assertTrue($category->products->contains($product2));
    }

    public function test_it_can_have_a_parent_category()
    {
        // Arrange
        $parentCategory = Category::factory()->create(['name' => 'Electronics']);
        $childCategory = Category::factory()->create([
            'name' => 'Smartphones',
            'parent_id' => $parentCategory->id
        ]);

        // Act & Assert
        $this->assertInstanceOf(Category::class, $childCategory->parent);
        $this->assertEquals($parentCategory->id, $childCategory->parent->id);
    }

    public function test_it_can_have_child_categories()
    {
        // Arrange
        $parentCategory = Category::factory()->create(['name' => 'Electronics']);
        $childCategory1 = Category::factory()->create([
            'name' => 'Smartphones',
            'parent_id' => $parentCategory->id
        ]);
        $childCategory2 = Category::factory()->create([
            'name' => 'Laptops',
            'parent_id' => $parentCategory->id
        ]);

        // Act & Assert
        $this->assertCount(2, $parentCategory->children);
        $this->assertTrue($parentCategory->children->contains($childCategory1));
        $this->assertTrue($parentCategory->children->contains($childCategory2));
    }
}