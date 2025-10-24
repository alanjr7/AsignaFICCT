<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Models\Docente;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
        {
            $users = User::latest()->paginate(10);
            return view('users.index', compact('users'));
        }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'ci' => 'required|string|max:20|unique:users',
        'nombre' => 'required|string|max:255',
        'correo' => 'required|string|email|max:255|unique:users',
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'rol' => 'required|in:admin,docente',
        'profesion' => $request->rol === 'docente' ? 'required|string|max:255' : 'nullable',
    ]);

    $user = User::create([
        'ci' => $request->ci,
        'nombre' => $request->nombre,
        'correo' => $request->correo,
        'password' => bcrypt($request->password),
        'rol' => $request->rol,
    ]);

    // Si es docente, crear registro en tabla docentes
    if ($request->rol === 'docente') {
        Docente::create([
            'user_id' => $user->id,
            'codigo_docente' => 'DOC-' . $user->ci,
            'profesion' => $request->profesion,
        ]);
    }

    // Registrar en bitácora
    Bitacora::create([
        'user_id' => auth()->id(),
        'accion_realizada' => 'Usuario creado: ' . $user->nombre,
        'fecha_y_hora' => now(),
    ]);

    return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
 public function update(Request $request, User $user)
{
    // Validación mejorada
    $request->validate([
        'ci' => 'required|string|max:20|unique:users,ci,' . $user->id,
        'nombre' => 'required|string|max:255',
        'correo' => 'required|string|email|max:255|unique:users,correo,' . $user->id,
        'rol' => 'required|in:admin,docente',
        'profesion' => $request->rol === 'docente' ? 'required|string|max:255' : 'nullable',
    ]);

    // Iniciar transacción para asegurar consistencia
    \DB::transaction(function () use ($request, $user) {
        // Guardar el rol anterior para comparar
        $rolAnterior = $user->rol;
        
        // Actualizar datos del usuario
        $user->update([
            'ci' => $request->ci,
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'rol' => $request->rol,
        ]);

        // Actualizar contraseña si se proporciona
        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);
        }

        // Manejar lógica de docente
        if ($request->rol === 'docente') {
            if ($user->docente) {
                // Actualizar docente existente
                $user->docente->update([
                    'profesion' => $request->profesion
                ]);
            } else {
                // Crear nuevo registro de docente
                \App\Models\Docente::create([
                    'user_id' => $user->id,
                    'codigo_docente' => 'DOC-' . $user->ci,
                    'profesion' => $request->profesion,
                ]);
            }
        } else {
            // Si cambia de docente a admin, eliminar registro de docente
            if ($user->docente) {
                $user->docente->delete();
            }
        }
    });

    // Registrar en bitácora
    Bitacora::create([
        'user_id' => auth()->id(),
        'accion_realizada' => 'Usuario actualizado: ' . $user->nombre,
        'fecha_y_hora' => now(),
    ]);

    return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $userName = $user->nombre;
        $user->delete();

        // Registrar en bitácora
        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => 'Usuario eliminado: ' . $userName,
            'fecha_y_hora' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}