<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $fillable = [
        'sigla_materia',
        'nombre_materia',
        'nivel',
        'horas_semana',
        'estado',
    ];

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_materia')
                    ->withPivot('docente_id', 'horas_asignadas')
                    ->withTimestamps();
    }

    public function grupoMaterias()
    {
        return $this->hasMany(GrupoMateria::class);
    }
}