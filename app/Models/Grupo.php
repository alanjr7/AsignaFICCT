<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $primaryKey = 'sigla_grupo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sigla_grupo',
        'cupo_maximo',
        'cupo_minimo',
        'descripcion',
    ];

    // Relación muchos a muchos con Materias a través de grupo_materia
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'grupo_materia', 'grupo_id', 'materia_id')
                    ->withPivot('id', 'docente_id', 'aula_id', 'horario_id')
                    ->withTimestamps();
    }

    // Relación directa con grupo_materia
    public function grupoMaterias()
    {
        return $this->hasMany(GrupoMateria::class, 'grupo_id', 'sigla_grupo');
    }

    // Obtener docentes a través de las materias asignadas
    public function docentes()
    {
        return $this->hasManyThrough(Docente::class, GrupoMateria::class, 'grupo_id', 'id', 'sigla_grupo', 'docente_id');
    }

    // Obtener horarios del grupo
    public function horarios()
    {
        return $this->hasManyThrough(Horario::class, GrupoMateria::class, 'grupo_id', 'id', 'sigla_grupo', 'horario_id');
    }
}