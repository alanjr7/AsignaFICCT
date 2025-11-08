@extends('layouts.app')

@section('title', 'Gestionar Mis Horarios')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Gestionar Mis Horarios</h2>
        <p class="text-gray-600">Asigna horarios a tus materias dentro del límite de horas asignadas</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
       <!-- Formulario para agregar horario -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Agregar Horario</h3>
                </div>
                
                <form action="{{ route('horario-docente.store') }}" method="POST" class="p-6" id="horario-form">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Grupo y Materia -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Materia y Grupo</label>
                            <select name="grupo_materia_id" id="grupo_materia_id"
                                    class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Seleccionar materia</option>
                                @foreach($grupoMaterias as $gm)
                                <option value="{{ $gm->id }}" 
                                        data-horas-disponibles="{{ $gm->horas_disponibles }}"
                                        data-horas-asignadas="{{ $gm->horas_asignadas }}"
                                        data-horas-utilizadas="{{ $gm->horas_utilizadas }}"
                                        {{ $gm->horas_disponibles <= 0 ? 'disabled' : '' }}>
                                    {{ $gm->materia->nombre_materia }} - {{ $gm->grupo->nombre_grupo }}
                                    ({{ number_format($gm->horas_utilizadas, 1) }}/{{ $gm->horas_asignadas }} hrs)
                                    {{ $gm->horas_disponibles <= 0 ? ' - COMPLETO' : '' }}
                                </option>
                                @endforeach
                            </select>
                            @error('grupo_materia_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Aula -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Aula</label>
                            <select name="aula_id" 
                                    class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Seleccionar aula</option>
                                @foreach($aulas as $aula)
                                <option value="{{ $aula->id }}">
                                    {{ $aula->nro_aula }} ({{ $aula->tipo }}) - Cap: {{ $aula->capacidad }}
                                </option>
                                @endforeach
                            </select>
                            @error('aula_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Día -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Día</label>
                            <select name="dia" 
                                    class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Seleccionar día</option>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                            </select>
                            @error('dia')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Horas -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hora Inicio</label>
                                <input type="time" name="hora_inicio" 
                                    class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                    required
                                    min="07:00" 
                                    max="20:00"
                                    step="1800"> <!-- 30 minutos -->
                                @error('hora_inicio')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hora Fin</label>
                                <input type="time" name="hora_fin" 
                                    class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                    required
                                    min="07:30" 
                                    max="20:30"
                                    step="1800"> <!-- 30 minutos -->
                                @error('hora_fin')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Información de horas disponibles -->
                        <div id="horas-info" class="bg-blue-50 border border-blue-200 rounded p-3 hidden">
                            <p class="text-sm text-blue-800">
                                <span class="font-medium" id="horas-disponibles-text"></span>
                            </p>
                        </div>

                        <!-- Advertencia si no hay horas disponibles -->
                        <div id="sin-horas-warning" class="bg-red-50 border border-red-200 rounded p-3 hidden">
                            <p class="text-sm text-red-800">
                                <span class="font-medium">⚠️ Esta materia ya tiene todas sus horas asignadas</span>
                            </p>
                        </div>

                        <!-- Mostrar errores de validación -->
                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded p-3">
                                <ul class="text-sm text-red-800 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Mostrar mensajes de éxito/error -->
                        @if(session('success'))
                            <div class="bg-green-50 border border-green-200 rounded p-3">
                                <p class="text-sm text-green-800">{{ session('success') }}</p>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="bg-red-50 border border-red-200 rounded p-3">
                                <p class="text-sm text-red-800">{{ session('error') }}</p>
                            </div>
                        @endif
                    </div>

                    <button type="submit" id="submit-button"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200 mt-6 disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Agregar Horario
                    </button>
                </form>
            </div>
        </div>
        <!-- Horario semanal -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Mi Horario Semanal</h3>
                    <p class="text-sm text-gray-600">Total de clases: {{ $horarios->count() }}</p>
                </div>
                
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 bg-gray-100 border border-gray-300 font-medium text-gray-700 text-sm">Hora</th>
                                    <th class="px-4 py-3 bg-gray-100 border border-gray-300 font-medium text-gray-700 text-sm">Lunes</th>
                                    <th class="px-4 py-3 bg-gray-100 border border-gray-300 font-medium text-gray-700 text-sm">Martes</th>
                                    <th class="px-4 py-3 bg-gray-100 border border-gray-300 font-medium text-gray-700 text-sm">Miércoles</th>
                                    <th class="px-4 py-3 bg-gray-100 border border-gray-300 font-medium text-gray-700 text-sm">Jueves</th>
                                    <th class="px-4 py-3 bg-gray-100 border border-gray-300 font-medium text-gray-700 text-sm">Viernes</th>
                                    <th class="px-4 py-3 bg-gray-100 border border-gray-300 font-medium text-gray-700 text-sm">Sábado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $horas = [
                                        '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', 
                                        '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
                                        '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
                                        '16:00', '16:30', '17:00', '17:30', '18:00', '18:30',
                                        '19:00', '19:30', '20:00'
                                    ];
                                    
                                    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                                    
                                    // Crear matriz para tracking de celdas ocupadas
                                    $celdasOcupadas = [];
                                    foreach ($dias as $dia) {
                                        foreach ($horas as $hora) {
                                            $celdasOcupadas[$dia][$hora] = false;
                                        }
                                    }
                                @endphp
                                
                                @for($i = 0; $i < count($horas); $i += 2)
                                <tr>
                                    <td class="px-4 py-3 border border-gray-300 bg-gray-50 font-medium text-sm whitespace-nowrap">
                                        {{ $horas[$i] }} - {{ $horas[$i+2] ?? '20:00' }}
                                    </td>
                                    @foreach($dias as $dia)
                                    <td class="px-4 py-3 border border-gray-300 min-w-48 h-20">
                                        @foreach($horarios->where('dia', $dia) as $horario)
                                            @php
                                                $horaInicio = \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i');
                                                $horaFin = \Carbon\Carbon::parse($horario->hora_fin)->format('H:i');
                                                
                                                // Verificar si este horario cae en este rango
                                                $enRango = ($horaInicio >= $horas[$i] && $horaInicio < ($horas[$i+2] ?? '20:30')) || 
                                                          ($horaFin > $horas[$i] && $horaFin <= ($horas[$i+2] ?? '20:30')) ||
                                                          ($horaInicio <= $horas[$i] && $horaFin >= ($horas[$i+2] ?? '20:30'));
                                            @endphp
                                            
                                            @if($enRango && !$celdasOcupadas[$dia][$horas[$i]])
                                                @php
                                                    // Marcar celdas como ocupadas
                                                    $horaActual = $horaInicio;
                                                    while ($horaActual < $horaFin) {
                                                        $celdasOcupadas[$dia][$horaActual] = true;
                                                        $horaActual = \Carbon\Carbon::parse($horaActual)->addMinutes(30)->format('H:i');
                                                    }
                                                @endphp
                                                
                                                <div class="bg-blue-50 border border-blue-200 rounded p-2 mb-2 hover:bg-blue-100 transition duration-200">
                                                    <div class="text-sm font-medium text-blue-800">
                                                        {{ $horario->grupoMateria->materia->sigla_materia }}
                                                    </div>
                                                    <div class="text-xs text-blue-600">
                                                        {{ $horario->grupoMateria->grupo->nombre_grupo }}
                                                    </div>
                                                    <div class="text-xs text-blue-500">
                                                        Aula: {{ $horario->aula->nro_aula ?? 'Sin asignar' }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $horaInicio }} - {{ $horaFin }}
                                                    </div>
                                                    <form action="{{ route('horario-docente.destroy', $horario) }}" 
                                                          method="POST" class="mt-1">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-xs text-red-600 hover:text-red-800"
                                                                onclick="return confirm('¿Eliminar este horario?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endforeach
                                    </td>
                                    @endforeach
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Materias asignadas con progreso de horas -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Mis Materias Asignadas</h3>
                </div>
                
                <div class="p-6">
                    @if($grupoMaterias->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($grupoMaterias as $gm)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-800">{{ $gm->materia->nombre_materia }}</h4>
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        {{ $gm->horas_utilizadas }}/{{ $gm->horas_asignadas }} hrs
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Grupo: {{ $gm->grupo->nombre_grupo }}</p>
                                
                                <!-- Barra de progreso -->
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                    @php
                                        $porcentaje = $gm->horas_asignadas > 0 ? ($gm->horas_utilizadas / $gm->horas_asignadas) * 100 : 0;
                                        $color = $porcentaje >= 100 ? 'bg-red-500' : ($porcentaje >= 80 ? 'bg-yellow-500' : 'bg-green-500');
                                    @endphp
                                    <div class="h-2 rounded-full {{ $color }}" style="width: {{ min($porcentaje, 100) }}%"></div>
                                </div>
                                
                                <p class="text-xs text-gray-500">
                                    @if($gm->horas_disponibles > 0)
                                        <span class="text-green-600">{{ $gm->horas_disponibles }} horas disponibles</span>
                                    @else
                                        <span class="text-red-600">Todas las horas asignadas</span>
                                    @endif
                                </p>
                                
                                <p class="text-sm text-gray-600 mt-2">Horarios asignados: {{ $gm->horarios->count() }}</p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No tienes materias asignadas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   // Controlar habilitación/deshabilitación del formulario según horas disponibles
document.getElementById('grupo_materia_id').addEventListener('change', function() {
    actualizarEstadoFormulario();
});

// Actualizar estado del formulario basado en la materia seleccionada
function actualizarEstadoFormulario() {
    const horasInfo = document.getElementById('horas-info');
    const sinHorasWarning = document.getElementById('sin-horas-warning');
    const submitButton = document.getElementById('submit-button');
    const horasDisponiblesText = document.getElementById('horas-disponibles-text');
    
    const selectedOption = this.options[this.selectedIndex];
    const horasDisponibles = parseFloat(selectedOption.getAttribute('data-horas-disponibles')) || 0;
    const horasAsignadas = parseFloat(selectedOption.getAttribute('data-horas-asignadas')) || 0;
    const horasUtilizadas = parseFloat(selectedOption.getAttribute('data-horas-utilizadas')) || 0;
    
    if (selectedOption.value && horasDisponibles > 0) {
        horasDisponiblesText.textContent = 
            horasDisponibles.toFixed(1) + ' horas disponibles de ' + 
            horasAsignadas + ' totales (' + horasUtilizadas.toFixed(1) + ' utilizadas)';
        horasInfo.classList.remove('hidden');
        sinHorasWarning.classList.add('hidden');
        submitButton.disabled = false;
    } else if (selectedOption.value && horasDisponibles <= 0) {
        horasInfo.classList.add('hidden');
        sinHorasWarning.classList.remove('hidden');
        submitButton.disabled = true;
    } else {
        horasInfo.classList.add('hidden');
        sinHorasWarning.classList.add('hidden');
        submitButton.disabled = false;
    }
}

// Validar que la hora fin sea después de la hora inicio
document.querySelectorAll('input[name="hora_inicio"], input[name="hora_fin"]').forEach(input => {
    input.addEventListener('change', function() {
        const horaInicio = document.querySelector('input[name="hora_inicio"]').value;
        const horaFin = document.querySelector('input[name="hora_fin"]').value;
        
        if (horaInicio && horaFin && horaInicio >= horaFin) {
            alert('La hora de fin debe ser después de la hora de inicio.');
            document.querySelector('input[name="hora_fin"]').value = '';
        }
    });
});

// Validar formulario antes de enviar
document.getElementById('horario-form').addEventListener('submit', function(e) {
    const materiaSelect = document.getElementById('grupo_materia_id');
    const selectedOption = materiaSelect.options[materiaSelect.selectedIndex];
    const horasDisponibles = parseFloat(selectedOption.getAttribute('data-horas-disponibles')) || 0;
    
    if (horasDisponibles <= 0) {
        e.preventDefault();
        alert('Esta materia no tiene horas disponibles. No puedes agregar más horarios.');
        return false;
    }

    // Validar que la hora fin sea después de la hora inicio
    const horaInicio = document.querySelector('input[name="hora_inicio"]').value;
    const horaFin = document.querySelector('input[name="hora_fin"]').value;
    
    if (horaInicio >= horaFin) {
        e.preventDefault();
        alert('La hora de fin debe ser después de la hora de inicio.');
        return false;
    }

    // Validar duración mínima (al menos 1 hora)
    const inicio = new Date('2000-01-01 ' + horaInicio);
    const fin = new Date('2000-01-01 ' + horaFin);
    const duracionHoras = (fin - inicio) / (1000 * 60 * 60);
    
    if (duracionHoras < 1) {
        e.preventDefault();
        alert('El horario debe tener una duración mínima de 1 hora.');
        return false;
    }
});

// Inicializar estado del formulario
document.addEventListener('DOMContentLoaded', function() {
    const materiaSelect = document.getElementById('grupo_materia_id');
    if (materiaSelect) {
        materiaSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection