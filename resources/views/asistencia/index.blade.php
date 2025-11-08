@extends('layouts.app')

@section('title', 'Marcar Asistencia')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">📅 Control de Asistencia</h1>
        <p class="text-gray-600">Marca tu asistencia durante los horarios de clase</p>
        <div class="mt-2 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-blue-800 text-sm">
                <strong>💡 Importante:</strong> Solo puedes marcar asistencia 15 minutos antes o durante tu horario de clase. 
                Tu ubicación será registrada automáticamente.
            </p>
        </div>
    </div>

    <!-- Horarios de Hoy -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Horarios Disponibles -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Tus Clases de Hoy - {{ now()->format('d/m/Y') }}</h3>
            
            @if($horariosHoy->count() > 0)
            <div class="space-y-4">
                @foreach($horariosHoy as $horario)
                <div class="border border-gray-200 rounded-lg p-4 
                    {{ $horario->estado == 'en_horario' ? 'bg-green-50 border-green-200' : 
                       ($horario->estado == 'finalizado' ? 'bg-gray-50 border-gray-200' : 'bg-blue-50 border-blue-200') }}">
                    
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $horario->grupoMateria->materia->nombre_materia }}</h4>
                            <p class="text-sm text-gray-600">Grupo: {{ $horario->grupoMateria->grupo->nombre_grupo }}</p>
                            <p class="text-sm text-gray-600">Aula: {{ $horario->aula->nro_aula ?? 'Sin asignar' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2 py-1 text-xs rounded-full 
                                {{ $horario->estado == 'en_horario' ? 'bg-green-100 text-green-800' : 
                                   ($horario->estado == 'finalizado' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ $horario->estado == 'en_horario' ? '🟢 En Horario' : 
                                   ($horario->estado == 'finalizado' ? '🔴 Finalizado' : '⏰ Próximo') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-3">
                        <span class="text-lg font-bold text-gray-700">
                            {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                        </span>
                    </div>

                    <!-- Estados de Asistencia -->
                    <div class="flex justify-between items-center text-sm mb-3">
                        <span class="{{ $horario->ya_marco_entrada ? 'text-green-600' : 'text-gray-500' }}">
                            ✅ Entrada: {{ $horario->ya_marco_entrada ? 'Marcada' : 'Pendiente' }}
                        </span>
                        <span class="{{ $horario->ya_marco_salida ? 'text-green-600' : 'text-gray-500' }}">
                            🚪 Salida: {{ $horario->ya_marco_salida ? 'Marcada' : 'Pendiente' }}
                        </span>
                    </div>

                    <!-- Botón de Marcado -->
                    @if($horario->puede_marcar)
                        @if(!$horario->ya_marco_entrada)
                            <button onclick="marcarAsistencia({{ $horario->id }}, 'entrada')" 
                                    class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                                📍 Marcar Entrada
                            </button>
                        @elseif(!$horario->ya_marco_salida)
                            <button onclick="marcarAsistencia({{ $horario->id }}, 'salida')" 
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                                🚪 Marcar Salida
                            </button>
                        @else
                            <button disabled class="w-full bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed">
                                ✅ Asistencia Completa
                            </button>
                        @endif
                    @else
                        <button disabled class="w-full bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed">
                            {{ $horario->estado == 'finalizado' ? '⏰ Horario Finalizado' : '⏳ Fuera de Horario' }}
                        </button>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="text-gray-400 text-4xl mb-4">📚</div>
                <p class="text-gray-500">No tienes clases programadas para hoy.</p>
            </div>
            @endif
        </div>

        <!-- Historial de Hoy -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Tus Marcaciones de Hoy</h3>
            
            @if($asistenciasHoy->count() > 0)
            <div class="space-y-3">
                @foreach($asistenciasHoy as $asistencia)
                <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-semibold text-gray-800">
                                {{ $asistencia->horario->grupoMateria->materia->nombre_materia }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $asistencia->horario->grupoMateria->grupo->nombre_grupo }} | 
                                {{ \Carbon\Carbon::parse($asistencia->hora_marcado)->format('H:i') }}
                            </div>
                            <div class="text-sm {{ $asistencia->tipo == 'entrada' ? 'text-green-600' : 'text-blue-600' }}">
                                {{ $asistencia->tipo == 'entrada' ? '✅ Entrada' : '🚪 Salida' }}
                            </div>
                            @if($asistencia->direccion)
                            <div class="text-xs text-gray-500 mt-1">
                                📍 {{ Str::limit($asistencia->direccion, 50) }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="text-gray-400 text-4xl mb-4">⏰</div>
                <p class="text-gray-500">No has marcado asistencia hoy.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Geolocalización -->
<div id="geolocationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">📍 Obteniendo Ubicación</h3>
        <p class="text-gray-600 mb-4">Necesitamos tu ubicación para registrar la asistencia. Por favor permite el acceso a la ubicación.</p>
        <div class="flex justify-end space-x-3">
            <button onclick="cerrarModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancelar</button>
            <button onclick="intentarGeolocalizacion()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Reintentar</button>
        </div>
    </div>
</div>

<script>
let horarioActual = null;
let tipoMarcado = null;

function marcarAsistencia(horarioId, tipo) {
    horarioActual = horarioId;
    tipoMarcado = tipo;
    
    // Solicitar geolocalización
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                enviarAsistencia(position.coords.latitude, position.coords.longitude);
            },
            function(error) {
                // Si falla la geolocalización, mostrar modal
                document.getElementById('geolocationModal').classList.remove('hidden');
                console.error('Error obteniendo ubicación:', error);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        alert('Tu navegador no soporta geolocalización. No podrás marcar asistencia.');
    }
}

function intentarGeolocalizacion() {
    cerrarModal();
    if (horarioActual) {
        marcarAsistencia(horarioActual, tipoMarcado);
    }
}

function cerrarModal() {
    document.getElementById('geolocationModal').classList.add('hidden');
}

function enviarAsistencia(latitud, longitud) {
    // Obtener dirección aproximada
    obtenerDireccion(latitud, longitud).then(direccion => {
        // Enviar solicitud al servidor
        fetch(`/asistencia/marcar/${horarioActual}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                latitud: latitud,
                longitud: longitud,
                direccion: direccion
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarNotificacion('success', data.mensaje);
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                mostrarNotificacion('error', data.error || 'Error al marcar asistencia');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('error', 'Error de conexión');
        });
    });
}

function obtenerDireccion(latitud, longitud) {
    // Usar OpenStreetMap Nominatim para obtener dirección
    return fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitud}&lon=${longitud}&format=json`)
        .then(response => response.json())
        .then(data => {
            return data.display_name || 'Ubicación no disponible';
        })
        .catch(error => {
            console.error('Error obteniendo dirección:', error);
            return 'Ubicación no disponible';
        });
}

function mostrarNotificacion(tipo, mensaje) {
    // Crear notificación
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        tipo === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = mensaje;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>

<style>
.fixed {
    position: fixed;
}
.inset-0 {
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}
.hidden {
    display: none;
}
.z-50 {
    z-index: 50;
}
</style>
@endsection