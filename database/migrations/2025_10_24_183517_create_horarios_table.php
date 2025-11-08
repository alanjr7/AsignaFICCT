<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_materia_id')->constrained('grupo_materia')->onDelete('cascade');
            $table->foreignId('aula_id')->constrained()->onDelete('cascade');
            $table->enum('dia', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();

            // Evitar conflictos de aula en mismo horario
            $table->unique(['aula_id', 'dia', 'hora_inicio']);
            
            // Evitar que mismo docente tenga dos clases al mismo tiempo
            $table->unique(['grupo_materia_id', 'dia', 'hora_inicio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};