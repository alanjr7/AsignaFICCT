<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Aula;
use App\Models\Horario;
use App\Models\Bitacora;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::with(['materia', 'aula', 'horario', 'docentes.user'])->get();
        return view('grupos.index', compact('grupos'));
    }

    public function create()
    {
        $materias = Materia::all();
        $aulas = Aula::all();
        $horarios = Horario::all();
        return view('grupos.create', compact('materias', 'aulas', 'horarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sigla_grupo' => 'required|string|unique:grupos',
            'sigla_materia' => 'required|exists:materias,sigla_materia',
            'aula_id' => 'required|exists:aulas,id',
            'horario_id' => 'required|exists:horarios,id',
            'cupo_maximo' => 'required|integer|min:1',
            'cupo_minimo' => 'required|integer|min:1|lte:cupo_maximo',
        ]);

        $grupo = Grupo::create($request->all());

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Grupo creado: ' . $grupo->sigla_grupo,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('grupos.index')->with('success', 'Grupo creado exitosamente.');
    }

    public function show(Grupo $grupo)
    {
        $grupo->load(['materia', 'aula', 'horario', 'docentes.user']);
        return view('grupos.show', compact('grupo'));
    }

    public function edit(Grupo $grupo)
    {
        $materias = Materia::all();
        $aulas = Aula::all();
        $horarios = Horario::all();
        return view('grupos.edit', compact('grupo', 'materias', 'aulas', 'horarios'));
    }

    public function update(Request $request, Grupo $grupo)
    {
        $request->validate([
            'sigla_grupo' => 'required|string|unique:grupos,sigla_grupo,' . $grupo->sigla_grupo . ',sigla_grupo',
            'sigla_materia' => 'required|exists:materias,sigla_materia',
            'aula_id' => 'required|exists:aulas,id',
            'horario_id' => 'required|exists:horarios,id',
            'cupo_maximo' => 'required|integer|min:1',
            'cupo_minimo' => 'required|integer|min:1|lte:cupo_maximo',
        ]);

        $grupo->update($request->all());

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Grupo actualizado: ' . $grupo->sigla_grupo,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('grupos.index')->with('success', 'Grupo actualizado exitosamente.');
    }

    public function destroy(Grupo $grupo)
    {
        $sigla = $grupo->sigla_grupo;
        $grupo->delete();

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Grupo eliminado: ' . $sigla,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('grupos.index')->with('success', 'Grupo eliminado exitosamente.');
    }
}