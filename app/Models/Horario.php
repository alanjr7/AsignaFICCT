<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $fillable = [
        'grupo_materia_id',
        'aula_id', 
        'dia',
        'hora_inicio',
        'hora_fin'
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    // Relación con GrupoMateria
    public function grupoMateria()
    {
        return $this->belongsTo(GrupoMateria::class);
    }

    // Relación con Aula
    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    // Accesor para obtener el docente
    public function getDocenteAttribute()
    {
        return $this->grupoMateria->docente;
    }

    // Scope para horarios de un docente
    public function scopePorDocente($query, $docenteId)
    {
        return $query->whereHas('grupoMateria', function($q) use ($docenteId) {
            $q->where('docente_id', $docenteId);
        });
    }

    // Scope para horarios por día
    public function scopePorDia($query, $dia)
    {
        return $query->where('dia', $dia);
    }

    // Verificar conflicto de horario
    public static function tieneConflicto($aulaId, $dia, $horaInicio, $horaFin, $excluirId = null)
    {
        $query = self::where('aula_id', $aulaId)
            ->where('dia', $dia)
            ->where(function($q) use ($horaInicio, $horaFin) {
                $q->whereBetween('hora_inicio', [$horaInicio, $horaFin])
                  ->orWhereBetween('hora_fin', [$horaInicio, $horaFin])
                  ->orWhere(function($q2) use ($horaInicio, $horaFin) {
                      $q2->where('hora_inicio', '<=', $horaInicio)
                         ->where('hora_fin', '>=', $horaFin);
                  });
            });

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->exists();
    }
}