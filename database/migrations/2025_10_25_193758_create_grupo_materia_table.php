<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupo_materia', function (Blueprint $table) {
            $table->id();
            $table->string('grupo_id');
            $table->string('materia_id');
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade');
            $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');
            $table->timestamps();

            // Constraints únicos (mantienen la lógica de negocio)
            $table->unique(['grupo_id', 'materia_id', 'docente_id']);
            $table->unique(['grupo_id', 'horario_id']);
        });

        // Agregar foreign keys después de crear la tabla (evita conflictos de orden)
        Schema::table('grupo_materia', function (Blueprint $table) {
            // Verificar que las tablas existan antes de crear las FKs
            if (Schema::hasTable('grupos') && Schema::hasTable('materias')) {
                $table->foreign('grupo_id')
                      ->references('sigla_grupo')
                      ->on('grupos')
                      ->onDelete('cascade');
                
                $table->foreign('materia_id')
                      ->references('sigla_materia')
                      ->on('materias')
                      ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        // Eliminar foreign keys primero
        Schema::table('grupo_materia', function (Blueprint $table) {
            $table->dropForeign(['grupo_id']);
            $table->dropForeign(['materia_id']);
        });

        Schema::dropIfExists('grupo_materia');
    }
};