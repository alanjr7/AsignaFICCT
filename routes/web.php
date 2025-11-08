<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\HorarioDocenteController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Rutas protegidas por rol admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('grupos', GrupoController::class);
    Route::resource('materias', MateriaController::class);
    Route::resource('aulas', AulaController::class);
    
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
    
    Route::get('/admin/asistencia', [AsistenciaController::class, 'dashboardAdmin'])->name('asistencia.admin');
    Route::get('/admin/asistencia/mapa', [AsistenciaController::class, 'mapaAsistencias'])->name('asistencia.mapa');

    

    // Rutas específicas para grupos
    Route::get('/grupos/{grupo}/asignar-materias', [GrupoController::class, 'asignarMaterias'])->name('grupos.asignar-materias');
    Route::post('/grupos/{grupo}/materias', [GrupoController::class, 'storeMaterias'])->name('grupos.store-materias');
});
//ruta para materias
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('materias', MateriaController::class);
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
});
// Rutas para docentes-------------------------
// Rutas para docentes - Horarios
Route::middleware(['auth', 'role:docente'])->group(function () {
    Route::get('/mi-horario', [HorarioController::class, 'index'])->name('horario.index');
    
    Route::prefix('mis-horarios')->group(function () {
        Route::get('/{grupoMateria}/crear', [HorarioDocenteController::class, 'create'])->name('horario-docente.create');
        Route::post('/{grupoMateria}', [HorarioDocenteController::class, 'store'])->name('horario-docente.store');
        Route::delete('/{horario}', [HorarioDocenteController::class, 'destroy'])->name('horario-docente.destroy');
    });
    
Route::get('/asistencia', [AsistenciaController::class, 'index'])->name('asistencia.index');
    Route::post('/asistencia/marcar/{horario}', [AsistenciaController::class, 'marcarAsistencia'])->name('asistencia.marcar');
});

require __DIR__.'/auth.php';