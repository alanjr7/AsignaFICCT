<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\DocenteGrupoController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Rutas protegidas por rol admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
      Route::resource('aulas', AulaController::class);

      // Nuevas rutas para grupos
    Route::resource('grupos', GrupoController::class);
    Route::get('/grupos/{grupo}/asignar-docente', [DocenteGrupoController::class, 'create'])->name('grupos.asignar-docente.create');
    Route::post('/grupos/{grupo}/asignar-docente', [DocenteGrupoController::class, 'store'])->name('grupos.asignar-docente.store');
    Route::delete('/grupos/{grupo}/docente/{docente}', [DocenteGrupoController::class, 'destroy'])->name('grupos.asignar-docente.destroy');

});
//ruta para materias
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('materias', MateriaController::class);
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
});
// Rutas para docentes
Route::middleware(['auth', 'role:docente'])->group(function () {
    Route::get('/asistencia', [AsistenciaController::class, 'index'])->name('asistencia.index');
    Route::post('/asistencia', [AsistenciaController::class, 'store'])->name('asistencia.store');
    Route::get('/mi-horario', [HorarioController::class, 'index'])->name('horario.index');
});


require __DIR__.'/auth.php';