<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo_reporte',
        'filtros',
        'formato',
        'ruta_archivo',
        'estado'
    ];

    protected $casts = [
        'filtros' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope para reportes por tipo
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_reporte', $tipo);
    }

    // Scope para reportes recientes
    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}