<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'codigo_docente',
        'profesion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación muchos a muchos con Grupos
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'docente_grupo', 'docente_id', 'grupo_id');
    }

    // Obtener horario del docente
    public function getHorarioAttribute()
    {
        return $this->grupos()->with(['horario', 'materia', 'aula'])->get();
    }
}