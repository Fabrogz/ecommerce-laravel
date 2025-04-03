<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product()
    {
        // Arrange
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $image = UploadedFile::fake()->image('product.jpg');

        // Act
        $response = $this->actingAs($admin)->post(route('products.store'), [
            'name' => 'Nuevo Producto',
            'description' => 'Descripción del producto',
            'price' => 99.99,
            'stock' => 10,
            'category_id' => $category->id,
            'image' => $image
        ]);

        // Assert
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Nuevo Producto',
            'price' => 99.99
        ]);
        Storage::disk('public')->assertExists('products/' . $image->hashName());
    }

    public function test_non_admin_cannot_create_product()
    {
        // Arrange
        $user = User::factory()->create(['role' => 'customer']);
        $category = Category::factory()->create();

        // Act
        $response = $this->actingAs($user)->post(route('products.store'), [
            'name' => 'Nuevo Producto',
            'description' => 'Descripción del producto',
            'price' => 99.99,
            'stock' => 10,
            'category_id' => $category->id,
        ]);

        // Assert
        $response->assertForbidden();
        $this->assertDatabaseMissing('products', ['name' => 'Nuevo Producto']);
    }

    public function test_admin_can_update_product()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['name' => 'Producto Original']);

        // Act
        $response = $this->actingAs($admin)->put(route('products.update', $product), [
            'name' => 'Producto Actualizado',
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'category_id' => $product->category_id,
        ]);

        // Assert
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Producto Actualizado'
        ]);
    }

    public function test_admin_can_delete_product()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();
        
        // Act
        $response = $this->actingAs($admin)->delete(route('products.destroy', $product));
        
        // Assert
        $response->assertRedirect(route('products.index'));
        $this->assertSoftDeleted($product);
    }
}