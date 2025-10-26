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
    // Obtener materias asignadas con toda la información
    public function getMateriasAsignadasAttribute()
    {
        return $this->grupoMaterias()
            ->with(['grupo', 'materia', 'aula', 'horario'])
            ->get()
            ->groupBy('horario.dias_semana');
    }
// Obtener horario semanal organizado
    public function getHorarioSemanalAttribute()
    {
        $materias = $this->grupoMaterias()
            ->with(['grupo', 'materia', 'aula', 'horario'])
            ->get();

        $horario = [];
        foreach ($materias as $materia) {
            $dia = $materia->horario->dias_semana;
            $horario[$dia][] = [
                'materia' => $materia->materia->nombre_materia,
                'sigla_materia' => $materia->materia->sigla_materia,
                'grupo' => $materia->grupo->sigla_grupo,
                'aula' => $materia->aula->nro_aula,
                'tipo_aula' => $materia->aula->tipo,
                'hora_inicio' => $materia->horario->hora_inicio,
                'hora_fin' => $materia->horario->hora_fin,
            ];
        }

        return $horario;
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