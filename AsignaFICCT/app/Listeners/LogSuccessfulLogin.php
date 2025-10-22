<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
   public function handle(Login $event)
{
    Bitacora::create([
        'user_id' => $event->user->id,
        'accion_realizada' => 'Inicio de sesión',
        'fecha_y_hora' => now(),
    ]);
}
}
