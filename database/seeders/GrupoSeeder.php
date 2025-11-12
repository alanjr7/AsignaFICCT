<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grupo;

class GrupoSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = [
            [
                'codigo_grupo' => 'GRP-001',
                'sigla_grupo' => 'INF101',
                'nombre_grupo' => 'Introducción a la Programación - A',
                'cupo_maximo' => 40,
                'cupo_minimo' => 10,
                'estado' => 'activo',
            ],
            [
                'codigo_grupo' => 'GRP-002',
                'sigla_grupo' => 'INF102',
                'nombre_grupo' => 'Estructura de Datos - A',
                'cupo_maximo' => 35,
                'cupo_minimo' => 12,
                'estado' => 'activo',
            ],
            [
                'codigo_grupo' => 'GRP-003',
                'sigla_grupo' => 'INF103',
                'nombre_grupo' => 'Bases de Datos - A',
                'cupo_maximo' => 30,
                'cupo_minimo' => 10,
                'estado' => 'inactivo',
            ],
        ];

        foreach ($grupos as $grupo) {
            Grupo::create($grupo);
        }
    }
}
