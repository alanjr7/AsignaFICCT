@extends('layouts.app')

@section('title', 'Mi Horario')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Mi Horario Semanal</h2>
        <p class="text-gray-600">Bienvenido, {{ auth()->user()->nombre }}</p>
    </div>

    <!-- Información del docente -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Información del Docente</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nombre:</p>
                    <p class="font-medium">{{ auth()->user()->nombre }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Código:</p>
                    <p class="font-medium">{{ auth()->user()->docente->codigo_docente ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Profesión:</p>
                    <p class="font-medium">{{ auth()->user()->docente->profesion ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Materias:</p>
                    <p class="font-medium">{{ $materiasAsignadas->flatten()->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Horario semanal -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Horario Semanal</h3>
            <p class="text-sm text-gray-600">Período: {{ now()->format('F Y') }}</p>
        </div>
        
        <div class="p-6">
            @if($materiasAsignadas->flatten()->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Hora / Día
                                </th>
                                @foreach($diasSemana as $dia)
                                <th class="py-3 px-4 border-b text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $dia }}
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Generar filas para cada bloque horario -->
                            @php
                                $bloquesHorarios = [
                                    '07:00 - 08:30', '08:30 - 10:00', '10:00 - 11:30', 
                                    '11:30 - 13:00', '14:00 - 15:30', '15:30 - 17:00',
                                    '17:00 - 18:30', '18:30 - 20:00'
                                ];
                            @endphp
                            
                            @foreach($bloquesHorarios as $bloque)
                            <tr>
                                <td class="py-3 px-4 border-b text-sm font-medium text-gray-900 bg-gray-50">
                                    {{ $bloque }}
                                </td>
                                
                                @foreach($diasSemana as $dia)
                                <td class="py-3 px-4 border-b text-center align-top" style="min-height: 80px;">
                                    @php
                                        $clasesDelDia = $materiasAsignadas->get($dia, collect())->filter(function($materia) use ($bloque) {
                                            $horaInicio = \Carbon\Carbon::parse($materia->horario->hora_inicio)->format('H:i');
                                            $horaFin = \Carbon\Carbon::parse($materia->horario->hora_fin)->format('H:i');
                                            $bloqueHoras = explode(' - ', $bloque);
                                            return $horaInicio === $bloqueHoras[0] && $horaFin === $bloqueHoras[1];
                                        });
                                    @endphp
                                    
                                    @foreach($clasesDelDia as $clase)
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-2 mb-2 text-left">
                                        <div class="font-medium text-blue-800 text-sm">
                                            {{ $clase->materia->nombre_materia }}
                                        </div>
                                        <div class="text-xs text-blue-600 mt-1">
                                            <strong>Grupo:</strong> {{ $clase->grupo->sigla_grupo }}
                                        </div>
                                        <div class="text-xs text-blue-600">
                                            <strong>Aula:</strong> {{ $clase->aula->nro_aula }}
                                        </div>
                                        <div class="text-xs text-blue-500 mt-1">
                                            {{ $clase->horario->hora_inicio }} - {{ $clase->horario->hora_fin }}
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    @if($clasesDelDia->count() === 0)
                                    <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Resumen de materias -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-medium text-gray-700 mb-4">Resumen de Materias Asignadas</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($materiasAsignadas->flatten()->unique('materia_id') as $materiaAsignada)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="font-medium text-gray-900">{{ $materiaAsignada->materia->nombre_materia }}</h5>
                                    <p class="text-sm text-gray-600">{{ $materiaAsignada->materia->sigla_materia }}</p>
                                </div>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    {{ $materiasAsignadas->flatten()->where('materia_id', $materiaAsignada->materia_id)->count() }} grupo(s)
                                </span>
                            </div>
                            
                            <div class="mt-3 space-y-2">
                                @foreach($materiasAsignadas->flatten()->where('materia_id', $materiaAsignada->materia_id) as $grupoMateria)
                                <div class="text-xs text-gray-600 border-l-2 border-blue-400 pl-2">
                                    <strong>Grupo {{ $grupoMateria->grupo->sigla_grupo }}</strong> | 
                                    {{ $grupoMateria->horario->dias_semana }} 
                                    ({{ $grupoMateria->horario->hora_inicio }} - {{ $grupoMateria->horario->hora_fin }}) |
                                    Aula: {{ $grupoMateria->aula->nro_aula }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h4 class="text-lg font-medium text-gray-700 mb-2">No tienes materias asignadas</h4>
                    <p class="text-gray-500 max-w-md mx-auto">
                        Actualmente no tienes materias asignadas en tu horario. 
                        Contacta con la administración para obtener tu carga horaria.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Vista de lista simple -->
    <div class="bg-white shadow rounded-lg mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Lista de Clases</h3>
        </div>
        <div class="p-6">
            @if($materiasAsignadas->flatten()->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grupo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Día</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aula</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($materiasAsignadas->flatten()->sortBy('horario.hora_inicio') as $materiaAsignada)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $materiaAsignada->materia->nombre_materia }}</div>
                                    <div class="text-sm text-gray-500">{{ $materiaAsignada->materia->sigla_materia }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $materiaAsignada->grupo->sigla_grupo }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $materiaAsignada->horario->dias_semana }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $materiaAsignada->horario->hora_inicio }} - {{ $materiaAsignada->horario->hora_fin }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $materiaAsignada->aula->nro_aula }} ({{ $materiaAsignada->aula->tipo }})
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection