<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Por ahora retornamos una vista vacía
        // Más adelante integraremos la lógica real de horarios
        return view('horario.index');
    }
}