<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nombre' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Ejecutar los seeders en el orden correcto
        $this->call([
            HorarioSeeder::class,
            AulaSeeder::class,
            MateriaSeeder::class,
            DocenteSeeder::class,
            GrupoSeeder::class,
            
        ]);
    }
}