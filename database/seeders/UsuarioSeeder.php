<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Usuario::create([
                'nombre' => fake()->firstName(),
                'apellido' => fake()->lastName(),
                'dpi' => fake()->unique()->numerify('#############'), // 13 dígitos
                'correo_electronico' => fake()->unique()->safeEmail(),
                'password' => '12345678', // se hashea solo por el cast
            ]);
        }
    }
}