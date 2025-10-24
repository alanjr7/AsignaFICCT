<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->string('sigla_grupo')->primary(); // Clave primaria
            $table->string('sigla_materia');
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade');
            $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');
            $table->integer('cupo_maximo');
            $table->integer('cupo_minimo');
            $table->timestamps();

            // Clave foránea hacia materias
            $table->foreign('sigla_materia')->references('sigla_materia')->on('materias')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};