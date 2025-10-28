<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grupos = [
            [
                'sigla_grupo' => 'G-INF110-1',
                'descripcion' => 'Grupo de Introducción a la Informática - Turno Mañana',
                'cupo_minimo' => 15,
                'cupo_maximo' => 35,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sigla_grupo' => 'G-INF110-2',
                'descripcion' => 'Grupo de Introducción a la Informática - Turno Tarde',
                'cupo_minimo' => 15,
                'cupo_maximo' => 35,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sigla_grupo' => 'G-INF120-1',
                'descripcion' => 'Grupo de Programación I - Turno Mañana',
                'cupo_minimo' => 12,
                'cupo_maximo' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sigla_grupo' => 'G-INF120-2',
                'descripcion' => 'Grupo de Programación I - Turno Tarde',
                'cupo_minimo' => 12,
                'cupo_maximo' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sigla_grupo' => 'G-ADM1D0-1',
                'descripcion' => 'Grupo de Administración - Turno Mañana',
                'cupo_minimo' => 20,
                'cupo_maximo' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sigla_grupo' => 'G-INF220-1',
                'descripcion' => 'Grupo de Estructura de Datos I - Turno Mañana',
                'cupo_minimo' => 10,
                'cupo_maximo' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sigla_grupo' => 'G-ECO300-1',
                'descripcion' => 'Grupo de Economía para la Gestión - Turno Mañana',
                'cupo_minimo' => 18,
                'cupo_maximo' => 38,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sigla_grupo' => 'G-INF323-1',
                'descripcion' => 'Grupo de Sistemas Operativos I - Laboratorio',
                'cupo_minimo' => 8,
                'cupo_maximo' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sigla_grupo' => 'G-INF418-1',
                'descripcion' => 'Grupo de Inteligencia Artificial - Turno Tarde',
                'cupo_minimo' => 10,
                'cupo_maximo' => 22,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sigla_grupo' => 'G-INF442-1',
                'descripcion' => 'Grupo de Sistemas de Información Geográfica - Laboratorio',
                'cupo_minimo' => 8,
                'cupo_maximo' => 18,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sigla_grupo' => 'G-RDS512-1',
                'descripcion' => 'Grupo de Redes Inalámbricas - Sábados',
                'cupo_minimo' => 12,
                'cupo_maximo' => 28,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('grupos')->insert($grupos);

        $this->command->info('Seeder de grupos ejecutado correctamente. Se crearon ' . count($grupos) . ' grupos.');
    }
}