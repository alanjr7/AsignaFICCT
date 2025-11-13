<?php

namespace App\Http\Controllers;

use App\Models\GrupoMateria;
use App\Models\Horario;
use App\Models\Aula;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HorarioDocenteController extends Controller
{
    public function create(GrupoMateria $grupoMateria)
    {
        if ($grupoMateria->docente_id !== auth()->id()) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        $aulas = Aula::where('estado', 'disponible')->get();
        $horariosExistentes = $grupoMateria->horarios()->with('aula')->get();

        return view('horario-docente.create', compact('grupoMateria', 'aulas', 'horariosExistentes'));
    }

    public function store(Request $request, GrupoMateria $grupoMateria)
    {
        if ($grupoMateria->docente_id !== auth()->id()) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $request->validate([
            'aula_id' => 'required|exists:aulas,id',
            'dia' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'modalidad' => 'required|in:presencial,virtual', // ✅ Nueva validación
        ]);

        if (!$grupoMateria->tieneHorasDisponibles()) {
            return back()->with('error', 'Esta materia ya tiene todas sus horas asignadas.');
        }

        $nuevoInicio = Carbon::parse($request->hora_inicio);
        $nuevoFin = Carbon::parse($request->hora_fin);
        $horasNuevo = $nuevoFin->diffInHours($nuevoInicio, true);

        if ($horasNuevo > $grupoMateria->horasPendientes()) {
            return back()->with('error', "No puedes agregar este horario. Horas disponibles: {$grupoMateria->horasPendientes()}, Horas solicitadas: {$horasNuevo}");
        }

        $conflictoAula = Horario::where('aula_id', $request->aula_id)
            ->where('dia', $request->dia)
            ->where(function($query) use ($request) {
                $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                      ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                      ->orWhere(function($q) use ($request) {
                          $q->where('hora_inicio', '<=', $request->hora_inicio)
                            ->where('hora_fin', '>=', $request->hora_fin);
                      });
            })->exists();

        if ($conflictoAula) {
            return back()->with('error', 'El aula ya está ocupada en ese horario.');
        }

        $conflictoDocente = Horario::whereHas('grupoMateria', function($query) {
                $query->where('docente_id', auth()->id());
            })
            ->where('dia', $request->dia)
            ->where(function($query) use ($request) {
                $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                      ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                      ->orWhere(function($q) use ($request) {
                          $q->where('hora_inicio', '<=', $request->hora_inicio)
                            ->where('hora_fin', '>=', $request->hora_fin);
                      });
            })->exists();

        if ($conflictoDocente) {
            return back()->with('error', 'Ya tienes una clase asignada en ese horario.');
        }

        $horario = Horario::create([
            'grupo_materia_id' => $grupoMateria->id,
            'aula_id' => $request->aula_id,
            'dia' => $request->dia,
            'hora_inicio' => $request->hora_inicio . ':00',
            'hora_fin' => $request->hora_fin . ':00',
            'modalidad' => $request->modalidad, // ✅ Agregado
        ]);

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Horario asignado: ' . $grupoMateria->materia->nombre_materia . ' - ' . $request->dia . ' ' . $request->hora_inicio,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('horario.index')->with('success', 'Horario asignado exitosamente.');
    }

    public function destroy(Horario $horario)
    {
        if ($horario->grupoMateria->docente_id !== auth()->id()) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $horarioInfo = $horario->grupoMateria->materia->nombre_materia . ' - ' . $horario->dia . ' ' . $horario->hora_inicio;
        $horario->delete();

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Horario eliminado: ' . $horarioInfo,
            'fecha_y_hora' => now(),
        ]);

        return back()->with('success', 'Horario eliminado exitosamente.');
    }
}