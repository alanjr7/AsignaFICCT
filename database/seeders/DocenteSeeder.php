<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Docente;
use Illuminate\Support\Facades\Hash;

class DocenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $docentes = [
            [
                'codigo_usuario' => 2020,
                'ci_docente' => 1010,
                'nombre' => 'ZUNA VILLAGOMEZ RICARDO',
            ],
            [
                'codigo_usuario' => 2021,
                'ci_docente' => 1011,
                'nombre' => 'MOLLO MAMANI ALBERTO',
            ],
            [
                'codigo_usuario' => 2022,
                'ci_docente' => 1012,
                'nombre' => 'FLORES FLORES MARCOS OSCAR',
            ],
            [
                'codigo_usuario' => 2023,
                'ci_docente' => 1013,
                'nombre' => 'LOPEZ WINNIPEG MARIO MILTON',
            ],
            [
                'codigo_usuario' => 2024,
                'ci_docente' => 1014,
                'nombre' => 'TERRAZAS SOTO RICARDO',
            ],
            [
                'codigo_usuario' => 2025,
                'ci_docente' => 1015,
                'nombre' => 'BARROSO VIRUEZ GINO',
            ],
            [
                'codigo_usuario' => 2026,
                'ci_docente' => 1016,
                'nombre' => 'VARGAS YAPURA EDWIN',
            ],
            [
                'codigo_usuario' => 2027,
                'ci_docente' => 1017,
                'nombre' => 'PEREZ FERREIRA UBALDO',
            ],
            [
                'codigo_usuario' => 2028,
                'ci_docente' => 1018,
                'nombre' => 'CABALLERO RUA MAURICIO CHRISTIAN',
            ],
        ];

        $contador = 6;

        foreach ($docentes as $docenteData) {
            // Crear el usuario primero
            $user = User::create([
                'ci' => $docenteData['ci_docente'],
                'nombre' => $docenteData['nombre'],
                'correo' => 'docente' . $contador . '@gmail.com',
                'password' => Hash::make('12345678'),
                'rol' => 'docente',
            ]);

            // Crear el registro del docente
            Docente::create([
                'user_id' => $user->id,
                'codigo_docente' => 'DOC-' . $docenteData['codigo_usuario'],
                'profesion' => 'Docente Universitario', // Puedes ajustar esta profesión
            ]);

            $this->command->info("Docente creado: {$docenteData['nombre']}");
            $this->command->info("Correo: docente{$contador}@gmail.com");
            $this->command->info("Password: 12345678");
            $this->command->info("---");

            $contador++;
        }

        $this->command->info('¡Todos los docentes han sido creados exitosamente!');
    }
}