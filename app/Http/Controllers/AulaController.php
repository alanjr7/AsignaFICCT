<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AulaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aulas = Aula::latest()->paginate(10);
        return view('aulas.index', compact('aulas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposAula = [
            'Teórica' => 'Teórica',
            'Laboratorio' => 'Laboratorio',
            'Mixta' => 'Mixta',
            'Computación' => 'Computación',
            'Taller' => 'Taller'
        ];

        return view('aulas.create', compact('tiposAula'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nro_aula' => 'required|string|max:20|unique:aulas',
            'tipo' => 'required|string|max:50',
            'capacidad' => 'required|integer|min:1|max:500',
            'piso' => 'required|integer|min:0|max:20',
        ]);

        $aula = Aula::create($request->all());

        // Registrar en bitácora
        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Aula creada: ' . $aula->nro_aula,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('aulas.index')
            ->with('success', 'Aula creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aula $aula)
    {
        return view('aulas.show', compact('aula'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aula $aula)
    {
        $tiposAula = [
            'Teórica' => 'Teórica',
            'Laboratorio' => 'Laboratorio',
            'Mixta' => 'Mixta',
            'Computación' => 'Computación',
            'Taller' => 'Taller'
        ];

        return view('aulas.edit', compact('aula', 'tiposAula'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aula $aula)
    {
        $request->validate([
            'nro_aula' => [
                'required',
                'string',
                'max:20',
                Rule::unique('aulas')->ignore($aula->id)
            ],
            'tipo' => 'required|string|max:50',
            'capacidad' => 'required|integer|min:1|max:500',
            'piso' => 'required|integer|min:0|max:20',
        ]);

        $aula->update($request->all());

        // Registrar en bitácora
        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Aula actualizada: ' . $aula->nro_aula,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('aulas.index')
            ->with('success', 'Aula actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aula $aula)
    {
        $nombreAula = $aula->nro_aula;
        $aula->delete();

        // Registrar en bitácora
        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Aula eliminada: ' . $nombreAula,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('aulas.index')
            ->with('success', 'Aula eliminada exitosamente.');
    }
}