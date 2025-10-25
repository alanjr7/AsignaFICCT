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

    // Relación con grupo_materia (materias que imparte)
    public function grupoMaterias()
    {
        return $this->hasMany(GrupoMateria::class);
    }

    // Obtener grupos donde imparte clase
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_materia', 'docente_id', 'grupo_id')
                    ->withPivot('materia_id', 'aula_id', 'horario_id');
    }

    // Obtener materias que imparte
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'grupo_materia', 'docente_id', 'materia_id')
                    ->withPivot('grupo_id', 'aula_id', 'horario_id');
    }

    // Obtener horario completo del docente
    public function getHorarioCompletoAttribute()
    {
        return $this->grupoMaterias()->with(['grupo', 'materia', 'aula', 'horario'])->get();
    }
}