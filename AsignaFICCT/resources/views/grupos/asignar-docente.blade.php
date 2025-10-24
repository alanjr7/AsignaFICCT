@extends('layouts.app')

@section('title', 'Asignar Docente al Grupo')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Asignar Docente al Grupo: {{ $grupo->sigla_grupo }}</h2>
            <p class="text-sm text-gray-600">{{ $grupo->materia->nombre_materia }}</p>
        </div>
        
        <form action="{{ route('grupos.asignar-docente.store', $grupo) }}" method="POST" class="p-6">
            @csrf
            
            <!-- Información del grupo -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="text-lg font-medium text-blue-800 mb-2">Información del Grupo</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><span class="font-medium">Materia:</span> {{ $grupo->materia->nombre_materia }}</div>
                    <div><span class="font-medium">Aula:</span> {{ $grupo->aula->nro_aula }}</div>
                    <div><span class="font-medium">Horario:</span> {{ $grupo->horario->dias_semana }}</div>
                    <div>
                        <span class="font-medium">Horas:</span> 
                        {{ \Carbon\Carbon::parse($grupo->horario->hora_inicio)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($grupo->horario->hora_fin)->format('H:i') }}
                    </div>
                </div>
            </div>

            <!-- Selección de docente -->
            <div class="mb-6">
                <label for="docente_id" class="block text-sm font-medium text-gray-700">Seleccionar Docente *</label>
                <select name="docente_id" id="docente_id" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Seleccionar docente</option>
                    @foreach($docentes as $docente)
                        <option value="{{ $docente->id }}">
                            {{ $docente->codigo_docente }} - {{ $docente->user->nombre }} ({{ $docente->profesion }})
                        </option>
                    @endforeach
                </select>
                @error('docente_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Docentes ya asignados -->
            @if($grupo->docentes->count() > 0)
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Docentes Actualmente Asignados:</h4>
                <div class="space-y-2">
                    @foreach($grupo->docentes as $docente)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <span class="font-medium">{{ $docente->user->nombre }}</span>
                            <span class="text-sm text-gray-500 ml-2">({{ $docente->codigo_docente }})</span>
                        </div>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Asignado</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Botones -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('grupos.show', $grupo) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Asignar Docente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection