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

            // Constraints únicos (lógica de negocio)
            $table->unique(['grupo_id', 'materia_id', 'docente_id']);
            $table->unique(['grupo_id', 'horario_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_materia');
    }
};