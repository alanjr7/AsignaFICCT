<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_grupo',
        'sigla_grupo',
        'nombre_grupo',
        'cupo_maximo',
        'cupo_minimo',
        'estado',
    ];

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'grupo_materia')
                    ->withPivot('docente_id', 'horas_asignadas')
                    ->withTimestamps();
    }

    public function grupoMaterias()
    {
        return $this->hasMany(GrupoMateria::class);
    }

    public function horarios()
    {
        return $this->hasManyThrough(Horario::class, GrupoMateria::class);
    }
}