@extends('layouts.app')

@section('title', 'Dashboard de Asistencia - Admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">📊 Dashboard de Asistencia</h1>
        <p class="text-gray-600">Monitoreo de asistencias de docentes</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                <input type="date" name="fecha" value="{{ $fecha }}" 
                       class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                🔍 Filtrar
            </button>
            <a href="{{ route('asistencia.admin') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                🔄 Limpiar
            </a>
        </form>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="text-3xl text-blue-500 mr-4">👥</div>
                <div>
                    <p class="text-sm text-gray-600">Total Marcaciones</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $estadisticas['total'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="text-3xl text-green-500 mr-4">✅</div>
                <div>
                    <p class="text-sm text-gray-600">Entradas</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $estadisticas['entradas'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="text-3xl text-blue-500 mr-4">🚪</div>
                <div>
                    <p class="text-sm text-gray-600">Salidas</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $estadisticas['salidas'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="text-3xl text-purple-500 mr-4">👨‍🏫</div>
                <div>
                    <p class="text-sm text-gray-600">Docentes Activos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $estadisticas['docentes_activos'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mapa y Lista -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Mapa -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">📍 Mapa de Asistencias</h3>
            <div id="mapa" class="h-96 bg-gray-100 rounded-lg flex items-center justify-center">
                <p class="text-gray-500">Cargando mapa...</p>
            </div>
            <p class="text-sm text-gray-600 mt-2">Ubicaciones desde donde los docentes marcaron asistencia</p>
        </div>

        <!-- Lista de Asistencias -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">📋 Lista de Asistencias</h3>
            
            @if($asistencias->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Docente</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materia</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hora</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ubicación</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($asistencias as $asistencia)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $asistencia->docente->nombre }}</div>
                                <div class="text-sm text-gray-500">{{ $asistencia->docente->correo }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $asistencia->horario->grupoMateria->materia->sigla_materia }}</div>
                                <div class="text-sm text-gray-500">{{ $asistencia->horario->grupoMateria->grupo->nombre_grupo }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($asistencia->hora_marcado)->format('H:i') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $asistencia->tipo == 'entrada' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $asistencia->tipo == 'entrada' ? '✅ Entrada' : '🚪 Salida' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                @if($asistencia->direccion && $asistencia->direccion !== 'Ubicación no disponible')
                                    <span class="text-blue-600 cursor-help" title="{{ $asistencia->direccion }}">
                                        📍 {{ Str::limit($asistencia->direccion, 30) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">❌ No disponible</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <div class="text-gray-400 text-4xl mb-4">📊</div>
                <p class="text-gray-500">No hay asistencias registradas para esta fecha.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
let mapa = null;

// Inicializar mapa
function inicializarMapa() {
    mapa = L.map('mapa').setView([-16.5000, -68.1500], 13); // Coordenadas por defecto (La Paz, Bolivia)
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapa);
    
    // Cargar asistencias en el mapa
    cargarAsistenciasMapa();
}

function cargarAsistenciasMapa() {
    fetch(`/admin/asistencia/mapa?fecha={{ $fecha }}`)
        .then(response => response.json())
        .then(asistencias => {
            // Limpiar marcadores anteriores
            mapa.eachLayer(layer => {
                if (layer instanceof L.Marker) {
                    mapa.removeLayer(layer);
                }
            });
            
            if (asistencias.length === 0) {
                document.getElementById('mapa').innerHTML = '<p class="text-gray-500">No hay ubicaciones para mostrar</p>';
                return;
            }
            
            let bounds = [];
            
            asistencias.forEach(asistencia => {
                if (asistencia.latitud && asistencia.longitud) {
                    const marker = L.marker([asistencia.latitud, asistencia.longitud])
                        .addTo(mapa)
                        .bindPopup(`
                            <div class="p-2">
                                <strong>${asistencia.docente.nombre}</strong><br>
                                ${asistencia.horario.grupo_materia.materia.nombre_materia}<br>
                                ${asistencia.tipo === 'entrada' ? '✅ Entrada' : '🚪 Salida'}<br>
                                <small>${asistencia.hora_marcado}</small><br>
                                <small>${asistencia.direccion || 'Ubicación no disponible'}</small>
                            </div>
                        `);
                    
                    bounds.push([asistencia.latitud, asistencia.longitud]);
                }
            });
            
            // Ajustar vista para mostrar todos los marcadores
            if (bounds.length > 0) {
                mapa.fitBounds(bounds);
            }
        })
        .catch(error => {
            console.error('Error cargando asistencias:', error);
        });
}

// Inicializar cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    inicializarMapa();
});
</script>
@endsection