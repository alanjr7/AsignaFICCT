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
        'sigla_materia',
        'aula_id',
        'horario_id',
        'cupo_maximo',
        'cupo_minimo',
    ];

    // Relación con Materia
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'sigla_materia', 'sigla_materia');
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

    // Relación muchos a muchos con Docentes
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'docente_grupo', 'grupo_id', 'docente_id');
    }
}