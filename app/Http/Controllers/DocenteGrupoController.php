<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Docente;
use App\Models\Bitacora;
use Illuminate\Http\Request;

class DocenteGrupoController extends Controller
{
    public function create(Grupo $grupo)
    {
        $docentes = Docente::with('user')->get();
        $grupo->load(['materia', 'docentes.user']);
        return view('grupos.asignar-docente', compact('grupo', 'docentes'));
    }

    public function store(Request $request, Grupo $grupo)
    {
        $request->validate([
            'docente_id' => 'required|exists:docentes,id'
        ]);

        // Verificar si ya está asignado
        if ($grupo->docentes()->where('docente_id', $request->docente_id)->exists()) {
            return redirect()->back()->with('error', 'Este docente ya está asignado al grupo.');
        }

        $grupo->docentes()->attach($request->docente_id);

        $docente = Docente::find($request->docente_id);

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Docente ' . $docente->user->nombre . ' asignado al grupo ' . $grupo->sigla_grupo,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('grupos.show', $grupo)->with('success', 'Docente asignado exitosamente.');
    }

    public function destroy(Grupo $grupo, Docente $docente)
    {
        $grupo->docentes()->detach($docente->id);

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Docente ' . $docente->user->nombre . ' removido del grupo ' . $grupo->sigla_grupo,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('grupos.show', $grupo)->with('success', 'Docente removido exitosamente.');
    }
}