<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GrupoMateria extends Model
{
    use HasFactory;

    protected $table = 'grupo_materia';

    protected $fillable = [
        'grupo_id',
        'materia_id',
        'docente_id',
        'horas_asignadas',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function docente()
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    /**
     * Calcular horas asignadas (suma de todos los horarios)
     */
    public function horasAsignadas()
    {
        $total = 0;
        foreach ($this->horarios as $horario) {
            $inicio = Carbon::parse($horario->hora_inicio);
            $fin = Carbon::parse($horario->hora_fin);
            $total += $fin->diffInHours($inicio, true);
        }
        return round($total, 1);
    }

    /**
     * Calcular horas pendientes
     */
    public function horasPendientes()
    {
        $asignadas = $this->horasAsignadas();
        $pendientes = $this->horas_asignadas - $asignadas;
        return max(0, $pendientes);
    }

    /**
     * Verificar si tiene horas disponibles
     */
    public function tieneHorasDisponibles()
    {
        return $this->horasPendientes() > 0;
    }
}