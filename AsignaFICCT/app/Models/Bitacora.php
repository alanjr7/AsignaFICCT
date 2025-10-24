<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'accion_realizada',
        'fecha_y_hora',
    ];

    protected $casts = [
        'fecha_y_hora' => 'datetime',
    ];

    // Accesor para mostrar fecha en formato boliviano
    public function getFechaLegibleAttribute()
    {
        return $this->fecha_y_hora->format('d/m/Y H:i:s');
    }

    // Accesor para mostrar hora en formato 12h boliviano
    public function getHoraLegibleAttribute()
    {
        return $this->fecha_y_hora->format('h:i A');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}