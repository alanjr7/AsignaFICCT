<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoMateria extends Model
{
    use HasFactory;

    protected $table = 'grupo_materia';

    protected $fillable = [
        'grupo_id',
        'materia_id',
        'docente_id',
        'aula_id',
        'horario_id',
    ];

    // Relación con Grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id', 'sigla_grupo');
    }

    // Relación con Materia
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id', 'sigla_materia');
    }

    // Relación con Docente
    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    // Relación con Aula
    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    // Relación con Horario
    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }
}