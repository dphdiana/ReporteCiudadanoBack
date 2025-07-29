<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario administrador
        User::create([
            'nombre' => 'Admin',
            'correo' => 'admin@demo.com',
            'password' => Hash::make('admin123'),
            'tipo_usuario' => 'admin'
        ]);

        // Usuario ciudadano
        User::create([
            'nombre' => 'Usuario',
            'correo' => 'usuario@demo.com',
            'password' => Hash::make('usuario123'),
            'tipo_usuario' => 'ciudadano'
        ]);
    }
}
