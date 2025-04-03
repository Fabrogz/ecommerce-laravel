<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table.
     */
    public function run(): void
    {
        // Eliminar usuarios existentes con los correos electrónicos duplicados
        User::where('email', 'admin@example.com')->delete();
        User::where('email', 'cliente@example.com')->delete();

        // Insertar nuevos usuarios
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        
        User::create([
            'name' => 'Cliente',
            'email' => 'cliente@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);
    }
}
