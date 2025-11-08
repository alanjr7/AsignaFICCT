<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->string('nro_aula')->unique();
            $table->string('tipo'); // Laboratorio, Teórica, Mixta
            $table->integer('capacidad');
            $table->integer('piso');
            $table->enum('estado', ['disponible', 'mantenimiento'])->default('disponible');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};