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
          //  $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade');
            $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('grupo_id')->references('sigla_grupo')->on('grupos')->onDelete('cascade');
            $table->foreign('materia_id')->references('sigla_materia')->on('materias')->onDelete('cascade');
            
            // Un docente solo puede tener una materia específica en un grupo específico
            $table->unique(['grupo_id', 'materia_id', 'docente_id']);
            
            // Evitar conflictos de horario en el mismo grupo
            $table->unique(['grupo_id', 'horario_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_materia');
    }
};