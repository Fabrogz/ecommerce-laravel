<?php
namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_categories()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $categories = Category::factory()->count(3)->create();
        
        // Act
        $response = $this->actingAs($admin)->get(route('admin.categories.index'));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('categories');
    }

    public function test_admin_can_create_category()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Act
        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Nueva Categoría',
            'description' => 'Descripción de la categoría'
        ]);
        
        // Assert
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Nueva Categoría',
            'description' => 'Descripción de la categoría'
        ]);
    }

    public function test_admin_can_update_category()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['name' => 'Categoría Original']);
        
        // Act
        $response = $this->actingAs($admin)->put(route('admin.categories.update', $category), [
            'name' => 'Categoría Actualizada',
            'description' => $category->description
        ]);
        
        // Assert
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Categoría Actualizada'
        ]);
    }

    public function test_admin_can_delete_category()
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        
        // Act
        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category));
        
        // Assert
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_customer_cannot_manage_categories()
    {
        // Arrange
        $customer = User::factory()->create(['role' => 'customer']);
        
        // Act
        $response = $this->actingAs($customer)->get(route('admin.categories.index'));
        
        // Assert
        $response->assertForbidden();
    }
}