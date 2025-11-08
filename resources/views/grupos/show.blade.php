@extends('layouts.app')

@section('title', 'Detalles del Grupo')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detalles del Grupo</h2>
            <p class="text-gray-600">{{ $grupo->nombre_grupo }}</p>
        </div>
        <div class="space-x-2">
            <a href="{{ route('grupos.asignar-materias', $grupo) }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                Asignar Materias
            </a>
            <a href="{{ route('grupos.edit', $grupo) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                Editar Grupo
            </a>
            <a href="{{ route('grupos.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                Volver
            </a>
        </div>
    </div>

    <!-- Información del Grupo -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Información del Grupo</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Código</p>
                    <p class="text-lg font-semibold">{{ $grupo->codigo_grupo }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Sigla</p>
                    <p class="text-lg font-semibold">{{ $grupo->sigla_grupo }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Estado</p>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $grupo->estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $grupo->estado }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Cupo Mínimo</p>
                    <p class="text-lg">{{ $grupo->cupo_minimo }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Cupo Máximo</p>
                    <p class="text-lg">{{ $grupo->cupo_maximo }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Materias Asignadas</p>
                    <p class="text-lg">{{ $grupo->grupoMaterias->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Materias Asignadas -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Materias Asignadas</h3>
            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                {{ $grupo->grupoMaterias->count() }} materias
            </span>
        </div>
        
        <div class="p-6">
            @if($grupo->grupoMaterias->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horas/Semana</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horarios</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($grupo->grupoMaterias as $grupoMateria)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $grupoMateria->materia->sigla_materia }}</div>
                                    <div class="text-sm text-gray-500">{{ $grupoMateria->materia->nombre_materia }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($grupoMateria->docente)
                                        <!-- ✅ CORREGIDO: Acceso directo a User -->
                                        <div class="text-sm text-gray-900">{{ $grupoMateria->docente->nombre }}</div>
                                        <div class="text-sm text-gray-500">{{ $grupoMateria->docente->email }}</div>
                                    @else
                                        <span class="text-sm text-red-500">Sin docente asignado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $grupoMateria->horas_asignadas }} horas
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($grupoMateria->horarios->count() > 0)
                                        <div class="text-sm">
                                            @foreach($grupoMateria->horarios as $horario)
                                                <div class="text-gray-600">
                                                    {{ $horario->dia }} {{ $horario->hora_inicio->format('H:i') }}-{{ $horario->hora_fin->format('H:i') }}
                                                    @if($horario->aula)
                                                        ({{ $horario->aula->nro_aula }})
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-sm text-red-500">Sin horarios</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h4 class="text-lg font-medium text-gray-700 mb-2">No hay materias asignadas</h4>
                    <p class="text-gray-500 mb-4">Este grupo no tiene materias asignadas aún.</p>
                    <a href="{{ route('grupos.asignar-materias', $grupo) }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        Asignar Materias
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Horarios del Grupo -->
    @if($grupo->horarios->count() > 0)
    <div class="bg-white shadow rounded-lg mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Horarios del Grupo</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Día</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Docente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aula</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($grupo->horarios as $horario)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $horario->dia }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $horario->hora_inicio->format('H:i') }} - {{ $horario->hora_fin->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $horario->grupoMateria->materia->sigla_materia }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $horario->grupoMateria->docente->nombre ?? 'Sin docente' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $horario->aula->nro_aula ?? 'Sin aula' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection