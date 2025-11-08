<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\GrupoMateria;
use App\Models\Aula;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HorarioController extends Controller
{
    public function index()
    {
        $docenteId = auth()->id();
        
        $materiasAsignadas = GrupoMateria::with(['grupo', 'materia', 'horarios.aula'])
            ->where('docente_id', $docenteId)
            ->get();

        return view('horario.index', compact('materiasAsignadas'));
    }
}