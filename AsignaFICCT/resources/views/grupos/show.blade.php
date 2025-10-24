@extends('layouts.app')

@section('title', 'Detalles del Grupo')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Grupo: {{ $grupo->sigla_grupo }}</h2>
        <div class="space-x-2">
            <a href="{{ route('grupos.edit', $grupo) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                Editar Grupo
            </a>
            <a href="{{ route('grupos.asignar-docente.create', $grupo) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                Asignar Docente
            </a>
            <a href="{{ route('grupos.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                Volver a Lista
            </a>
        </div>
    </div>

    <!-- Información del Grupo -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Información del Grupo</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Sigla del Grupo</h4>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $grupo->sigla_grupo }}</p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Materia</h4>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $grupo->materia->nombre_materia }}</p>
                    <p class="text-sm text-gray-500">{{ $grupo->materia->sigla_materia }}</p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Aula</h4>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $grupo->aula->nro_aula }}</p>
                    <p class="text-sm text-gray-500">{{ $grupo->aula->tipo }} - Capacidad: {{ $grupo->aula->capacidad }}</p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Horario</h4>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $grupo->horario->dias_semana }}</p>
                    <p class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($grupo->horario->hora_inicio)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($grupo->horario->hora_fin)->format('H:i') }}
                    </p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Cupo Mínimo</h4>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $grupo->cupo_minimo }} estudiantes</p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Cupo Máximo</h4>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $grupo->cupo_maximo }} estudiantes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Docentes Asignados -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Docentes Asignados</h3>
            <a href="{{ route('grupos.asignar-docente.create', $grupo) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200 text-sm">
                + Asignar Docente
            </a>
        </div>
        
        <div class="p-6">
            @if($grupo->docentes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profesión</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($grupo->docentes as $docente)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $docente->codigo_docente }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $docente->user->nombre }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $docente->profesion }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $docente->user->correo }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form action="{{ route('grupos.asignar-docente.destroy', ['grupo' => $grupo, 'docente' => $docente]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition duration-200"
                                                onclick="return confirm('¿Remover a {{ $docente->user->nombre }} del grupo?')">
                                            Remover
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    <p class="text-gray-500 mb-4">No hay docentes asignados a este grupo.</p>
                    <a href="{{ route('grupos.asignar-docente.create', $grupo) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        Asignar Primer Docente
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection