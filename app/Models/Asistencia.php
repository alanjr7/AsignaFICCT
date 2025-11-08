<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'docente_id',
        'horario_id',
        'fecha',
        'hora_marcado',
        'tipo',
        'latitud',
        'longitud',
        'direccion',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_marcado' => 'datetime:H:i',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
    ];

    // Relación con el docente (User)
    public function docente()
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    // Relación con el horario
    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    // Scope para asistencias de hoy
    public function scopeHoy($query)
    {
        return $query->where('fecha', today());
    }

    // Scope para un docente específico
    public function scopePorDocente($query, $docenteId)
    {
        return $query->where('docente_id', $docenteId);
    }

    // Verificar si ya marcó asistencia hoy en un horario
    public static function yaMarcoHoy($docenteId, $horarioId, $tipo = 'entrada')
    {
        return self::where('docente_id', $docenteId)
            ->where('horario_id', $horarioId)
            ->where('fecha', today())
            ->where('tipo', $tipo)
            ->exists();
    }

    // Obtener asistencias por fecha
    public function scopePorFecha($query, $fecha)
    {
        return $query->where('fecha', $fecha);
    }

    // Obtener asistencias con coordenadas
    public function scopeConGeolocalizacion($query)
    {
        return $query->whereNotNull('latitud')->whereNotNull('longitud');
    }
}