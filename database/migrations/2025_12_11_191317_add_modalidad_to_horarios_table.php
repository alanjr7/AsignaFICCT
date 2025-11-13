<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Primero hacer aula_id nullable
        Schema::table('horarios', function (Blueprint $table) {
            $table->foreignId('aula_id')->nullable()->change();
        });

        // Agregar columna modalidad si no existe
        if (!Schema::hasColumn('horarios', 'modalidad')) {
            Schema::table('horarios', function (Blueprint $table) {
                $table->enum('modalidad', ['presencial', 'virtual'])->default('presencial')->after('hora_fin');
            });
        }

        // Eliminar la restricción única existente
        Schema::table('horarios', function (Blueprint $table) {
            $table->dropUnique(['aula_id', 'dia', 'hora_inicio']);
        });

        // Crear índice único parcial solo para aulas no nulas (PostgreSQL)
        DB::statement('
            CREATE UNIQUE INDEX horarios_aula_dia_hora_unique 
            ON horarios (aula_id, dia, hora_inicio) 
            WHERE aula_id IS NOT NULL
        ');
    }

    public function down(): void
    {
        // Eliminar horarios virtuales antes de revertir
        DB::table('horarios')->whereNull('aula_id')->delete();

        // Eliminar índice parcial
        DB::statement('DROP INDEX IF EXISTS horarios_aula_dia_hora_unique');

        Schema::table('horarios', function (Blueprint $table) {
            // Volver a hacer aula_id not null
            $table->foreignId('aula_id')->nullable(false)->change();
            
            // Recrear restricción única normal
            $table->unique(['aula_id', 'dia', 'hora_inicio']);
            
            // Eliminar columna modalidad
            if (Schema::hasColumn('horarios', 'modalidad')) {
                $table->dropColumn('modalidad');
            }
        });
    }
};