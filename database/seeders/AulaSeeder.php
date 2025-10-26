<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aulas = [];

        // Primer piso: aulas 11-16
        for ($i = 11; $i <= 16; $i++) {
            $aulas[] = [
                'nro_aula' => (string)$i,
                'tipo' => 'Teórica',
                'capacidad' => 40,
                'piso' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Segundo piso: aulas 21-26
        for ($i = 21; $i <= 26; $i++) {
            $aulas[] = [
                'nro_aula' => (string)$i,
                'tipo' => 'Teórica',
                'capacidad' => 40,
                'piso' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Tercer piso: aulas 31-36
        for ($i = 31; $i <= 36; $i++) {
            $aulas[] = [
                'nro_aula' => (string)$i,
                'tipo' => 'Teórica',
                'capacidad' => 40,
                'piso' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Cuarto piso: aulas 41-46
        for ($i = 41; $i <= 46; $i++) {
           
            
            $aulas[] = [
                'nro_aula' => (string)$i,
                'tipo' => 'Laboratorio',
                'capacidad' => 40,
                'piso' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertar todas las aulas en la base de datos
        DB::table('aulas')->insert($aulas);

        $this->command->info('Seeder de aulas ejecutado correctamente. Se crearon ' . count($aulas) . ' aulas.');
    }
}