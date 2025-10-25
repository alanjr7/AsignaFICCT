<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Aula;
use App\Models\Horario;
use App\Models\Docente;
use App\Models\GrupoMateria;
use App\Models\Bitacora;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::with(['grupoMaterias.materia', 'grupoMaterias.docente.user', 'grupoMaterias.aula', 'grupoMaterias.horario'])->get();
        return view('grupos.index', compact('grupos'));
    }

    public function create()
    {
        $materias = Materia::all();
        $aulas = Aula::all();
        $horarios = Horario::all();
        $docentes = Docente::with('user')->get();
        return view('grupos.create', compact('materias', 'aulas', 'horarios', 'docentes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sigla_grupo' => 'required|string|unique:grupos',
            'cupo_maximo' => 'required|integer|min:1',
            'cupo_minimo' => 'required|integer|min:1|lte:cupo_maximo',
            'descripcion' => 'nullable|string|max:500',
            'materias' => 'required|array|min:1',
            'materias.*.materia_id' => 'required|exists:materias,sigla_materia',
            'materias.*.docente_id' => 'required|exists:docentes,id',
            'materias.*.aula_id' => 'required|exists:aulas,id',
            'materias.*.horario_id' => 'required|exists:horarios,id',
        ]);

        // Crear el grupo
        $grupo = Grupo::create([
            'sigla_grupo' => $request->sigla_grupo,
            'cupo_maximo' => $request->cupo_maximo,
            'cupo_minimo' => $request->cupo_minimo,
            'descripcion' => $request->descripcion,
        ]);

        // Crear las relaciones grupo-materia
        foreach ($request->materias as $materiaData) {
            GrupoMateria::create([
                'grupo_id' => $grupo->sigla_grupo,
                'materia_id' => $materiaData['materia_id'],
                'docente_id' => $materiaData['docente_id'],
                'aula_id' => $materiaData['aula_id'],
                'horario_id' => $materiaData['horario_id'],
            ]);
        }

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Grupo creado: ' . $grupo->sigla_grupo . ' con ' . count($request->materias) . ' materias',
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('grupos.index')->with('success', 'Grupo creado exitosamente con sus materias.');
    }

   public function show(Grupo $grupo)
{
    $grupo->load(['grupoMaterias.materia', 'grupoMaterias.docente.user', 'grupoMaterias.aula', 'grupoMaterias.horario']);
    
    // Pasar datos para el modal de agregar materia
    $materias = Materia::all();
    $aulas = Aula::all();
    $horarios = Horario::all();
    $docentes = Docente::with('user')->get();
    
    return view('grupos.show', compact('grupo', 'materias', 'aulas', 'horarios', 'docentes'));
}
   public function edit(Grupo $grupo)
{
    $grupo->load(['grupoMaterias.materia', 'grupoMaterias.docente.user', 'grupoMaterias.aula', 'grupoMaterias.horario']);
    
    return view('grupos.edit', compact('grupo'));
}

    public function update(Request $request, Grupo $grupo)
    {
        $request->validate([
            'sigla_grupo' => 'required|string|unique:grupos,sigla_grupo,' . $grupo->sigla_grupo . ',sigla_grupo',
            'cupo_maximo' => 'required|integer|min:1',
            'cupo_minimo' => 'required|integer|min:1|lte:cupo_maximo',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $grupo->update([
            'sigla_grupo' => $request->sigla_grupo,
            'cupo_maximo' => $request->cupo_maximo,
            'cupo_minimo' => $request->cupo_minimo,
            'descripcion' => $request->descripcion,
        ]);

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

    // Método para agregar materia a grupo existente
    public function agregarMateria(Request $request, Grupo $grupo)
    {
        $request->validate([
            'materia_id' => 'required|exists:materias,sigla_materia',
            'docente_id' => 'required|exists:docentes,id',
            'aula_id' => 'required|exists:aulas,id',
            'horario_id' => 'required|exists:horarios,id',
        ]);

        // Verificar si ya existe esta materia en el grupo con el mismo docente
        $existente = GrupoMateria::where('grupo_id', $grupo->sigla_grupo)
            ->where('materia_id', $request->materia_id)
            ->where('docente_id', $request->docente_id)
            ->exists();

        if ($existente) {
            return redirect()->back()->with('error', 'Esta materia ya está asignada a este docente en el grupo.');
        }

        // Verificar conflicto de horario en el mismo grupo
        $conflictoHorario = GrupoMateria::where('grupo_id', $grupo->sigla_grupo)
            ->where('horario_id', $request->horario_id)
            ->exists();

        if ($conflictoHorario) {
            return redirect()->back()->with('error', 'Ya existe una materia en este horario para el grupo.');
        }

        GrupoMateria::create([
            'grupo_id' => $grupo->sigla_grupo,
            'materia_id' => $request->materia_id,
            'docente_id' => $request->docente_id,
            'aula_id' => $request->aula_id,
            'horario_id' => $request->horario_id,
        ]);

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Materia agregada al grupo: ' . $grupo->sigla_grupo,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('grupos.show', $grupo)->with('success', 'Materia agregada al grupo exitosamente.');
    }

    // Método para eliminar materia del grupo
    public function eliminarMateria(Grupo $grupo, GrupoMateria $grupoMateria)
    {
        $grupoMateria->delete();

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Materia removida del grupo: ' . $grupo->sigla_grupo,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('grupos.show', $grupo)->with('success', 'Materia removida del grupo exitosamente.');
    }
}