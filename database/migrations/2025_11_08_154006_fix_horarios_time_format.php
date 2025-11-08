<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Si ya existe data, convertirla
        if (Schema::hasTable('horarios')) {
            $horarios = DB::table('horarios')->get();
            
            foreach ($horarios as $horario) {
                // Convertir datetime a time si es necesario
                if (strlen($horario->hora_inicio) > 8) {
                    DB::table('horarios')
                        ->where('id', $horario->id)
                        ->update([
                            'hora_inicio' => \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i:s'),
                            'hora_fin' => \Carbon\Carbon::parse($horario->hora_fin)->format('H:i:s')
                        ]);
                }
            }
        }
    }

    public function down(): void
    {
        // No hay rollback necesario
    }
};