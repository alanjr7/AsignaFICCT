<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Materia;

class MateriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materias = [
            [
                'sigla_materia' => 'INF110',
                'nombre_materia' => 'INTRODUCCION A LA INFORMATICA',
                'nivel' => 1,
            ],
            [
                'sigla_materia' => 'INF120',
                'nombre_materia' => 'PROGRAMACION I',
                'nivel' => 2,
            ],
            [
                'sigla_materia' => 'ADM1D0',
                'nombre_materia' => 'ADMINISTRACION',
                'nivel' => 3,
            ],
            [
                'sigla_materia' => 'INF220',
                'nombre_materia' => 'ESTRUCTURA DE DATOS I',
                'nivel' => 4,
            ],
            [
                'sigla_materia' => 'ECO300',
                'nombre_materia' => 'ECONOMIA PARA LA GESTION',
                'nivel' => 5,
            ],
            [
                'sigla_materia' => 'INF323',
                'nombre_materia' => 'SISTEMAS OPERATIVOS I',
                'nivel' => 6,
            ],
            [
                'sigla_materia' => 'INF418',
                'nombre_materia' => 'INTELIGENCIA ARTIFICIAL',
                'nivel' => 7,
            ],
            [
                'sigla_materia' => 'INF442',
                'nombre_materia' => 'SISTEMAS DE INFORM.GEOGRAFICA',
                'nivel' => 8,
            ],
            [
                'sigla_materia' => 'RDS512',
                'nombre_materia' => 'REDES INALAMBRICAS Y COMUNICACIONES MOVILES',
                'nivel' => 9,
            ],
        ];

        foreach ($materias as $materia) {
            Materia::create($materia);
        }
    }
}