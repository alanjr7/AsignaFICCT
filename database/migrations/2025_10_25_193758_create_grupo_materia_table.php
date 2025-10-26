<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verificar si la tabla ya existe
        if (!Schema::hasTable('grupo_materia')) {
            Schema::create('grupo_materia', function (Blueprint $table) {
                $table->id();
                $table->string('grupo_id');
                $table->string('materia_id');
                $table->foreignId('docente_id')->nullable()->constrained('docentes')->onDelete('cascade');
                $table->foreignId('aula_id')->nullable()->constrained('aulas')->onDelete('cascade');
                $table->foreignId('horario_id')->nullable()->constrained('horarios')->onDelete('cascade');
                $table->timestamps();

                // Constraints únicos (lógica de negocio)
                $table->unique(['grupo_id', 'materia_id', 'docente_id']);
                $table->unique(['grupo_id', 'horario_id']);
                
                // NOTA: Foreign keys para grupo_id y materia_id 
                // se agregarán después con migración separada
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_materia');
    }
};