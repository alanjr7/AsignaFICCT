<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrupoMateriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan datos en las tablas relacionadas
        $grupoCount = DB::table('grupos')->count();
        $materiaCount = DB::table('materias')->count();
        $docenteCount = DB::table('docentes')->count();
        $aulaCount = DB::table('aulas')->count();
        $horarioCount = DB::table('horarios')->count();

        if ($grupoCount === 0 || $materiaCount === 0 || $docenteCount === 0 || $aulaCount === 0 || $horarioCount === 0) {
            $this->command->error('Primero ejecuta los seeders de Grupos, Materias, Docentes, Aulas y Horarios');
            return;
        }

        // Obtener IDs existentes
        $grupos = DB::table('grupos')->pluck('sigla_grupo');
        $docentes = DB::table('docentes')->pluck('id');
        $aulas = DB::table('aulas')->pluck('id');
        $horarios = DB::table('horarios')->pluck('id');

        $asignaciones = [];

        // Mapeo de grupos a materias basado en el nombre del grupo
        $grupoMateriaMap = [
            'G-INF110-1' => 'INF110',
            'G-INF110-2' => 'INF110',
            'G-INF120-1' => 'INF120',
            'G-INF120-2' => 'INF120',
            'G-ADM1D0-1' => 'ADM1D0',
            'G-INF220-1' => 'INF220',
            'G-ECO300-1' => 'ECO300',
            'G-INF323-1' => 'INF323',
            'G-INF418-1' => 'INF418',
            'G-INF442-1' => 'INF442',
            'G-RDS512-1' => 'RDS512',
        ];

        foreach ($grupoMateriaMap as $grupoId => $materiaId) {
            // Verificar que el grupo existe
            if (!$grupos->contains($grupoId)) {
                $this->command->warn("Grupo {$grupoId} no encontrado, saltando...");
                continue;
            }

            // Verificar que la materia existe
            $materiaExiste = DB::table('materias')->where('sigla_materia', $materiaId)->exists();
            if (!$materiaExiste) {
                $this->command->warn("Materia {$materiaId} no encontrada, saltando...");
                continue;
            }

            $asignaciones[] = [
                'grupo_id' => $grupoId,
                'materia_id' => $materiaId,
                'docente_id' => $docentes->random(),
                'aula_id' => $aulas->random(),
                'horario_id' => $horarios->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $this->command->info("Asignación creada: {$grupoId} - {$materiaId}");
        }

        if (empty($asignaciones)) {
            $this->command->error('No se pudo crear ninguna asignación. Verifica los datos.');
            return;
        }

        DB::table('grupo_materia')->insert($asignaciones);

        $this->command->info('Seeder de GrupoMateria ejecutado correctamente.');
        $this->command->info('Total: ' . count($asignaciones) . ' asignaciones creadas.');
    }
}