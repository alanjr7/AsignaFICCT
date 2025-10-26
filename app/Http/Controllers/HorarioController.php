<?php

namespace App\Http\Controllers;

use App\Models\GrupoMateria;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener el docente autenticado
        $docente = auth()->user()->docente;
        
        if (!$docente) {
            return redirect()->route('dashboard')->with('error', 'No se encontró información de docente.');
        }

        // Obtener todas las materias asignadas al docente con sus grupos, aulas y horarios
        $materiasAsignadas = GrupoMateria::with(['grupo', 'materia', 'aula', 'horario'])
            ->where('docente_id', $docente->id)
            ->get()
            ->groupBy('horario.dias_semana'); // Agrupar por día de la semana

        // Días de la semana ordenados
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        
        // Horarios disponibles
        $horarios = \App\Models\Horario::all()->groupBy('dias_semana');

        return view('horario.index', compact('materiasAsignadas', 'diasSemana', 'horarios'));
    }
}