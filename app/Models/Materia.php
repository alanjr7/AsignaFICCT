<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $primaryKey = 'sigla_materia';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sigla_materia',
        'nombre_materia',
        'nivel',
    ];

    protected $casts = [
        'nivel' => 'integer',
    ];
}