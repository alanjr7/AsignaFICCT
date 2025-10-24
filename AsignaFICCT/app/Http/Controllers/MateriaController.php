<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Bitacora;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materias = Materia::latest()->paginate(10);
        return view('materias.index', compact('materias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('materias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sigla_materia' => 'required|string|max:10|unique:materias',
            'nombre_materia' => 'required|string|max:255',
            'nivel' => 'required|integer|min:1|max:10',
        ]);

        $materia = Materia::create([
            'sigla_materia' => strtoupper($request->sigla_materia),
            'nombre_materia' => $request->nombre_materia,
            'nivel' => $request->nivel,
        ]);

        // Registrar en bitácora
        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Materia creada: ' . $materia->sigla_materia . ' - ' . $materia->nombre_materia,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('materias.index')->with('success', 'Materia creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Materia $materia)
    {
        return view('materias.show', compact('materia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materia $materia)
    {
        return view('materias.edit', compact('materia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materia $materia)
    {
        $request->validate([
            'sigla_materia' => 'required|string|max:10|unique:materias,sigla_materia,' . $materia->sigla_materia . ',sigla_materia',
            'nombre_materia' => 'required|string|max:255',
            'nivel' => 'required|integer|min:1|max:10',
        ]);

        $materia->update([
            'sigla_materia' => strtoupper($request->sigla_materia),
            'nombre_materia' => $request->nombre_materia,
            'nivel' => $request->nivel,
        ]);

        // Registrar en bitácora
        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Materia actualizada: ' . $materia->sigla_materia . ' - ' . $materia->nombre_materia,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('materias.index')->with('success', 'Materia actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materia $materia)
    {
        $materiaInfo = $materia->sigla_materia . ' - ' . $materia->nombre_materia;
        $materia->delete();

        // Registrar en bitácora
        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Materia eliminada: ' . $materiaInfo,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('materias.index')->with('success', 'Materia eliminada exitosamente.');
    }
}