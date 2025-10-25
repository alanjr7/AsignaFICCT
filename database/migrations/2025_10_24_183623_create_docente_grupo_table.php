<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docente_grupo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->string('grupo_id');
            $table->foreign('grupo_id')->references('sigla_grupo')->on('grupos')->onDelete('cascade');
            $table->timestamps();

            // Evitar duplicados
            $table->unique(['docente_id', 'grupo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docente_grupo');
    }
};