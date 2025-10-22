<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asistenciasHoy = Asistencia::where('user_id', auth()->id())
            ->whereDate('fecha', today())
            ->get();

        return view('asistencia.index', compact('asistenciasHoy'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'metodo' => 'required|in:formulario,qr',
        ]);

        // Verificar si ya registró asistencia hoy
        $asistenciaExistente = Asistencia::where('user_id', auth()->id())
            ->whereDate('fecha', today())
            ->first();

        if ($asistenciaExistente) {
            return redirect()->route('asistencia.index')
                ->with('error', 'Ya registraste tu asistencia hoy.');
        }

        // Crear registro de asistencia
        $asistencia = Asistencia::create([
            'user_id' => auth()->id(),
            'fecha' => today(),
            'hora' => now()->format('H:i:s'),
            'metodo' => $request->metodo,
            'estado' => 'presente',
        ]);

        // Registrar en bitácora
        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Asistencia registrada - Método: ' . $request->metodo,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('asistencia.index')
            ->with('success', 'Asistencia registrada exitosamente.');
    }
}