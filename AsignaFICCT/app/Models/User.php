<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'ci',
        'nombre',
        'correo',  // Cambiamos email por correo
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ✅ IMPORTANTE: Sobrescribir el método para usar 'correo' en lugar de 'email'
    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    // ✅ Para notificaciones
    public function routeNotificationFor($driver)
    {
        if (method_exists($this, $method = 'routeNotificationFor'.Str::studly($driver))) {
            return $this->{$method}();
        }

        switch ($driver) {
            case 'database':
                return $this->notifications();
            case 'mail':
                return $this->correo;  // Usar correo en lugar de email
            default:
                return $this->correo;  // Usar correo en lugar de email
        }
    }

    public function docente()
    {
        return $this->hasOne(Docente::class);
    }

    public function bitacoras()
    {
        return $this->hasMany(Bitacora::class);
    }

    public function isAdmin()
    {
        return $this->rol === 'admin';
    }

    public function isDocente()
    {
        return $this->rol === 'docente';
    }
}