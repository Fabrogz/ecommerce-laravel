<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), // Cambia esto
            'role' => 'admin' // Asegúrate de que tu modelo User tenga este campo
        ]);
        
        // Opcional: Usuario normal de prueba
        User::create([
            'name' => 'Cliente Ejemplo',
            'email' => 'cliente@example.com',
            'password' => Hash::make('cliente123'),
            'role' => 'customer'
        ]);
    }
}