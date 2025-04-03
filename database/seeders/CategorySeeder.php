<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Crear las categorías si no existen
        Category::firstOrCreate([
            'name' => 'Electrónica',
        ], [
            'slug' => 'electronica',
        ]);

        Category::firstOrCreate([
            'name' => 'Ropa',
        ], [
            'slug' => 'ropa',
        ]);

        // Agrega más categorías según sea necesario
    }
}
