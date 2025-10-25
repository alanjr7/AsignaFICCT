@extends('layouts.app')

@section('title', 'Editar Grupo')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Editar Grupo: {{ $grupo->sigla_grupo }}</h2>
        </div>
        
        <form action="{{ route('grupos.update', $grupo) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Sigla Grupo -->
                <div>
                    <label for="sigla_grupo" class="block text-sm font-medium text-gray-700">Sigla del Grupo *</label>
                    <input type="text" name="sigla_grupo" id="sigla_grupo" value="{{ old('sigla_grupo', $grupo->sigla_grupo) }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ej: INF-101-A" required>
                    @error('sigla_grupo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cupo Mínimo -->
                <div>
                    <label for="cupo_minimo" class="block text-sm font-medium text-gray-700">Cupo Mínimo *</label>
                    <input type="number" name="cupo_minimo" id="cupo_minimo" value="{{ old('cupo_minimo', $grupo->cupo_minimo) }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           min="1" required>
                    @error('cupo_minimo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cupo Máximo -->
                <div>
                    <label for="cupo_maximo" class="block text-sm font-medium text-gray-700">Cupo Máximo *</label>
                    <input type="number" name="cupo_maximo" id="cupo_maximo" value="{{ old('cupo_maximo', $grupo->cupo_maximo) }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           min="1" required>
                    @error('cupo_maximo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="md:col-span-3">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Descripción opcional del grupo">{{ old('descripcion', $grupo->descripcion) }}</textarea>
                </div>
            </div>

            <!-- Información actual del grupo -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-medium text-gray-700 mb-3">Materias Actuales del Grupo</h3>
                
                @if($grupo->grupoMaterias->count() > 0)
                    <div class="space-y-3">
                        @foreach($grupo->grupoMaterias as $grupoMateria)
                        <div class="border border-gray-200 rounded-lg p-3 bg-white">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="font-medium">Materia:</span> 
                                    {{ $grupoMateria->materia->nombre_materia }} ({{ $grupoMateria->materia->sigla_materia }})
                                </div>
                                <div>
                                    <span class="font-medium">Docente:</span> 
                                    {{ $grupoMateria->docente->user->nombre ?? 'Sin docente' }}
                                </div>
                                <div>
                                    <span class="font-medium">Aula:</span> 
                                    {{ $grupoMateria->aula->nro_aula ?? 'Sin aula' }}
                                </div>
                                <div>
                                    <span class="font-medium">Horario:</span> 
                                    {{ $grupoMateria->horario->dias_semana ?? 'Sin horario' }} - 
                                    @if($grupoMateria->horario)
                                        {{ \Carbon\Carbon::parse($grupoMateria->horario->hora_inicio)->format('H:i') }} a 
                                        {{ \Carbon\Carbon::parse($grupoMateria->horario->hora_fin)->format('H:i') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No hay materias asignadas a este grupo.</p>
                @endif
            </div>

            <!-- Botones -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('grupos.show', $grupo) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Actualizar Grupo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection