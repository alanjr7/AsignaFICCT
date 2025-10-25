<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Horario;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        $horarios = [
            // Turno Mañana
            [
                'dias_semana' => 'Lunes,Miércoles,Viernes',
                'hora_inicio' => '07:00:00',
                'hora_fin' => '08:30:00',
            ],
            [
                'dias_semana' => 'Lunes,Miércoles,Viernes',
                'hora_inicio' => '08:30:00',
                'hora_fin' => '10:00:00',
            ],
            [
                'dias_semana' => 'Lunes,Miércoles,Viernes',
                'hora_inicio' => '10:00:00',
                'hora_fin' => '11:30:00',
            ],
            [
                'dias_semana' => 'Lunes,Miércoles,Viernes',
                'hora_inicio' => '11:30:00',
                'hora_fin' => '13:00:00',
            ],
            
            // Turno Tarde
            [
                'dias_semana' => 'Lunes,Miércoles,Viernes',
                'hora_inicio' => '14:00:00',
                'hora_fin' => '15:30:00',
            ],
            [
                'dias_semana' => 'Lunes,Miércoles,Viernes',
                'hora_inicio' => '15:30:00',
                'hora_fin' => '17:00:00',
            ],
            [
                'dias_semana' => 'Lunes,Miércoles,Viernes',
                'hora_inicio' => '17:00:00',
                'hora_fin' => '18:30:00',
            ],
            [
                'dias_semana' => 'Lunes,Miércoles,Viernes',
                'hora_inicio' => '18:30:00',
                'hora_fin' => '20:00:00',
            ],
            
            // Martes y Jueves
            [
                'dias_semana' => 'Martes,Jueves',
                'hora_inicio' => '07:00:00',
                'hora_fin' => '09:00:00',
            ],
            [
                'dias_semana' => 'Martes,Jueves',
                'hora_inicio' => '09:00:00',
                'hora_fin' => '11:00:00',
            ],
            [
                'dias_semana' => 'Martes,Jueves',
                'hora_inicio' => '11:00:00',
                'hora_fin' => '13:00:00',
            ],
            [
                'dias_semana' => 'Martes,Jueves',
                'hora_inicio' => '14:00:00',
                'hora_fin' => '16:00:00',
            ],
            [
                'dias_semana' => 'Martes,Jueves',
                'hora_inicio' => '16:00:00',
                'hora_fin' => '18:00:00',
            ],
            [
                'dias_semana' => 'Martes,Jueves',
                'hora_inicio' => '18:00:00',
                'hora_fin' => '20:00:00',
            ],
            
            // Sábados
            [
                'dias_semana' => 'Sábado',
                'hora_inicio' => '07:00:00',
                'hora_fin' => '11:00:00',
            ],
            [
                'dias_semana' => 'Sábado',
                'hora_inicio' => '11:00:00',
                'hora_fin' => '15:00:00',
            ],
            [
                'dias_semana' => 'Sábado',
                'hora_inicio' => '15:00:00',
                'hora_fin' => '19:00:00',
            ],
        ];

        foreach ($horarios as $horario) {
            Horario::create($horario);
        }
    }
}