<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;  // Asegúrate de importar la clase Str

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electrónica' => 'smartphone-xyz',
            'Ropa' => 'camiseta-cool',
        ];

        foreach ($categories as $categoryName => $productName) {
            // Crea la categoría si no existe
            $category = Category::firstOrCreate(['name' => $categoryName]);

            // Crea el producto asociado a la categoría
            Product::create([
                'name' => $productName,
                'description' => 'Descripción del producto',
                'price' => 100,
                'stock' => 50,
                'category_id' => $category->id,
                'slug' => Str::slug($productName),  // Usando Str::slug() para generar el slug
            ]);
        }
    }
}
