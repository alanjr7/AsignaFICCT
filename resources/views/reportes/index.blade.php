@extends('layouts.app')

@section('title', 'Sistema de Reportes')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">📊 Sistema de Reportes</h1>
        <p class="text-gray-600">Genera reportes en diferentes formatos con filtros avanzados</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario de Reportes -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Generar Reporte Personalizado</h3>
                
                <form action="{{ route('reportes.generar') }}" method="POST" id="reporteForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Tipo de Reporte -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Reporte *</label>
                            <select name="tipo_reporte" id="tipo_reporte" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccionar tipo</option>
                                <option value="asistencias">Asistencias de Docentes</option>
                                <option value="horarios">Horarios Académicos</option>
                                <option value="docentes">Información de Docentes</option>
                                <option value="materias">Materias y Grupos</option>
                            </select>
                        </div>

                        <!-- Formato -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Formato *</label>
                            <select name="formato" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccionar formato</option>
                                <option value="excel">Excel (.xlsx)</option>
                                <option value="pdf">PDF (.pdf)</option>
                                <option value="html">Vista HTML</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filtros Dinámicos -->
                    <div id="filtrosContainer" class="space-y-4 mb-6">
                        <!-- Fechas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="filtroFechas" style="display: none;">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                                <input type="date" name="fecha_inicio"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                                <input type="date" name="fecha_fin"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Docente -->
                        <div id="filtroDocente" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Docente</label>
                            <select name="docente_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos los docentes</option>
                                @foreach(\App\Models\User::where('rol', 'docente')->get() as $docente)
                                    <option value="{{ $docente->id }}">{{ $docente->nombre }} ({{ $docente->ci }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Materia -->
                        <div id="filtroMateria" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Materia</label>
                            <select name="materia_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Todas las materias</option>
                                @foreach(\App\Models\Materia::all() as $materia)
                                    <option value="{{ $materia->id }}">{{ $materia->nombre_materia }} ({{ $materia->sigla_materia }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center justify-center">
                        🚀 Generar Reporte
                    </button>
                </form>
            </div>
        </div>

        <!-- Reportes Rápidos -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">📈 Reportes Rápidos</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('reportes.rapido', 'asistencias_hoy') }}" 
                       class="block bg-green-50 border border-green-200 rounded-lg p-4 hover:bg-green-100 transition duration-200">
                        <div class="flex items-center">
                            <div class="text-2xl text-green-600 mr-3">📅</div>
                            <div>
                                <h4 class="font-semibold text-green-800">Asistencias de Hoy</h4>
                                <p class="text-sm text-green-600">Registros del día actual</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('reportes.rapido', 'horarios_activos') }}" 
                       class="block bg-blue-50 border border-blue-200 rounded-lg p-4 hover:bg-blue-100 transition duration-200">
                        <div class="flex items-center">
                            <div class="text-2xl text-blue-600 mr-3">⏰</div>
                            <div>
                                <h4 class="font-semibold text-blue-800">Horarios Activos</h4>
                                <p class="text-sm text-blue-600">Todos los horarios programados</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('reportes.rapido', 'docentes_activos') }}" 
                       class="block bg-purple-50 border border-purple-200 rounded-lg p-4 hover:bg-purple-100 transition duration-200">
                        <div class="flex items-center">
                            <div class="text-2xl text-purple-600 mr-3">👨‍🏫</div>
                            <div>
                                <h4 class="font-semibold text-purple-800">Docentes Activos</h4>
                                <p class="text-sm text-purple-600">Información de todos los docentes</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">📊 Estadísticas</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Docentes:</span>
                        <span class="font-semibold text-blue-600">{{ \App\Models\User::where('rol', 'docente')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Asistencias Hoy:</span>
                        <span class="font-semibold text-green-600">{{ \App\Models\Asistencia::whereDate('fecha', today())->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Horarios Activos:</span>
                        <span class="font-semibold text-purple-600">{{ \App\Models\Horario::count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Materias:</span>
                        <span class="font-semibold text-orange-600">{{ \App\Models\Materia::count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('tipo_reporte').addEventListener('change', function() {
    const tipo = this.value;
    
    // Ocultar todos los filtros primero
    document.getElementById('filtroFechas').style.display = 'none';
    document.getElementById('filtroDocente').style.display = 'none';
    document.getElementById('filtroMateria').style.display = 'none';
    
    // Mostrar filtros según el tipo de reporte
    switch(tipo) {
        case 'asistencias':
            document.getElementById('filtroFechas').style.display = 'block';
            document.getElementById('filtroDocente').style.display = 'block';
            break;
        case 'horarios':
            document.getElementById('filtroDocente').style.display = 'block';
            document.getElementById('filtroMateria').style.display = 'block';
            break;
        case 'docentes':
            // Sin filtros adicionales
            break;
        case 'materias':
            // Sin filtros adicionales
            break;
    }
});

// Establecer fecha fin por defecto como hoy
document.addEventListener('DOMContentLoaded', function() {
    const hoy = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="fecha_fin"]').value = hoy;
});
</script>
@endsection