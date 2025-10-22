<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar si el usuario ya existe
        $existingUser = User::where('correo', 'admin@ficct.edu.bo')->first();
        
        if (!$existingUser) {
            $user = User::create([
                'ci' => '1234567',
                'nombre' => 'Administrador',
                'correo' => 'admin@ficct.edu.bo',
                'password' => Hash::make('admin123'),
                'rol' => 'admin',
            ]);

            // Crear también el registro en docentes
            \App\Models\Docente::create([
                'user_id' => $user->id,
                'codigo_docente' => 'ADMIN001',
                'profesion' => 'Administrador del Sistema',
            ]);

            $this->command->info('Usuario administrador creado exitosamente!');
            $this->command->info('Correo: admin@ficct.edu.bo');
            $this->command->info('Password: admin123');
        } else {
            $this->command->info('El usuario administrador ya existe.');
        }
    }
}