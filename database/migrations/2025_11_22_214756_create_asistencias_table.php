<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Eliminar tabla existente si existe
        Schema::dropIfExists('asistencias');
        
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora_marcado');
            $table->enum('tipo', ['entrada', 'salida'])->default('entrada');
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 10, 8)->nullable();
            $table->string('direccion')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['docente_id', 'horario_id', 'fecha', 'tipo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('asistencias');
    }
};