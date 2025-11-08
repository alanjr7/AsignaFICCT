<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Materia;
use App\Models\User;
use App\Models\Bitacora;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
     public function index()
    {
        $grupos = Grupo::withCount('materias')->latest()->paginate(10);
        return view('grupos.index', compact('grupos'));
    }

   public function create()
    {
        // ✅ Pasar materias y docentes a la vista
        $materias = Materia::where('estado', 'activa')->get();
        $docentes = User::where('rol', 'docente')->get();
        
        return view('grupos.create', compact('materias', 'docentes'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'codigo_grupo' => 'required|string|max:20|unique:grupos',
            'sigla_grupo' => 'required|string|max:10',
            'nombre_grupo' => 'required|string|max:100',
            'cupo_maximo' => 'required|integer|min:1',
            'cupo_minimo' => 'required|integer|min:1|lte:cupo_maximo',
        ]);

        $grupo = Grupo::create($request->all());

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Grupo creado: ' . $grupo->nombre_grupo,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('grupos.index')->with('success', 'Grupo creado exitosamente.');
    }

        public function show(Grupo $grupo)
        {
            // ✅ Cargar todas las relaciones necesarias
            $grupo->load([
                'grupoMaterias.materia',
                'grupoMaterias.docente', // Relación directa con User
                'grupoMaterias.horarios.aula',
                'horarios.grupoMateria.materia',
                'horarios.grupoMateria.docente',
                'horarios.aula'
            ]);
            
            return view('grupos.show', compact('grupo'));
        }

    public function edit(Grupo $grupo)
    {
        return view('grupos.edit', compact('grupo'));
    }

    public function update(Request $request, Grupo $grupo)
    {
        $request->validate([
            'codigo_grupo' => 'required|string|max:20|unique:grupos,codigo_grupo,' . $grupo->id,
            'sigla_grupo' => 'required|string|max:10',
            'nombre_grupo' => 'required|string|max:100',
            'cupo_maximo' => 'required|integer|min:1',
            'cupo_minimo' => 'required|integer|min:1|lte:cupo_maximo',
        ]);

        $grupo->update($request->all());

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Grupo actualizado: ' . $grupo->nombre_grupo,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('grupos.index')->with('success', 'Grupo actualizado exitosamente.');
    }

    public function destroy(Grupo $grupo)
    {
        $nombreGrupo = $grupo->nombre_grupo;
        $grupo->delete();

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Grupo eliminado: ' . $nombreGrupo,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('grupos.index')->with('success', 'Grupo eliminado exitosamente.');
    }

    public function asignarMaterias(Grupo $grupo)
    {
        $materias = Materia::where('estado', 'activa')->get();
        $docentes = User::where('rol', 'docente')->get();
        $grupo->load('grupoMaterias.materia', 'grupoMaterias.docente');

        return view('grupos.asignar-materias', compact('grupo', 'materias', 'docentes'));
    }

    public function storeMaterias(Request $request, Grupo $grupo)
    {
        $request->validate([
            'materias' => 'required|array|max:6',
            'materias.*.materia_id' => 'required|exists:materias,id',
            'materias.*.docente_id' => 'required|exists:users,id',
            'materias.*.horas_asignadas' => 'required|integer|min:1|max:20',
        ]);

        // Eliminar asignaciones anteriores
        $grupo->materias()->detach();

        // Crear nuevas asignaciones
        foreach ($request->materias as $materiaData) {
            $grupo->materias()->attach($materiaData['materia_id'], [
                'docente_id' => $materiaData['docente_id'],
                'horas_asignadas' => $materiaData['horas_asignadas'],
            ]);
        }

        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Materias asignadas al grupo: ' . $grupo->nombre_grupo,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('grupos.show', $grupo)->with('success', 'Materias asignadas exitosamente.');
    }
}