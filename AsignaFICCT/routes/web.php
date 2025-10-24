<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\AulaController;
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