<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $fillable = [
        'nro_aula',
        'tipo',
        'capacidad',
        'piso',
    ];

    /**
     * Relación con horarios (una aula puede tener muchos horarios)
     */
    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    /**
     * Scope para aulas disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->whereDoesntHave('horarios', function ($q) {
            $q->where('activo', true);
        });
    }

    /**
     * Obtener el nombre completo del aula
     */
    public function getNombreCompletoAttribute()
    {
        return "Aula {$this->nro_aula} - Piso {$this->piso}";
    }
}