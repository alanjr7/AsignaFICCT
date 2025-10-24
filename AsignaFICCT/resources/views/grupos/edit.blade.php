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
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sigla Grupo -->
                <div class="md:col-span-2">
                    <label for="sigla_grupo" class="block text-sm font-medium text-gray-700">Sigla del Grupo *</label>
                    <input type="text" name="sigla_grupo" id="sigla_grupo" value="{{ old('sigla_grupo', $grupo->sigla_grupo) }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ej: MAT101-G1" required>
                    @error('sigla_grupo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Materia -->
                <div>
                    <label for="sigla_materia" class="block text-sm font-medium text-gray-700">Materia *</label>
                    <select name="sigla_materia" id="sigla_materia" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Seleccionar materia</option>
                        @foreach($materias as $materia)
                            <option value="{{ $materia->sigla_materia }}" {{ old('sigla_materia', $grupo->sigla_materia) == $materia->sigla_materia ? 'selected' : '' }}>
                                {{ $materia->sigla_materia }} - {{ $materia->nombre_materia }}
                            </option>
                        @endforeach
                    </select>
                    @error('sigla_materia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Aula -->
                <div>
                    <label for="aula_id" class="block text-sm font-medium text-gray-700">Aula *</label>
                    <select name="aula_id" id="aula_id" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Seleccionar aula</option>
                        @foreach($aulas as $aula)
                            <option value="{{ $aula->id }}" {{ old('aula_id', $grupo->aula_id) == $aula->id ? 'selected' : '' }}>
                                {{ $aula->nro_aula }} - {{ $aula->tipo }} (Cap: {{ $aula->capacidad }})
                            </option>
                        @endforeach
                    </select>
                    @error('aula_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Horario -->
                <div class="md:col-span-2">
                    <label for="horario_id" class="block text-sm font-medium text-gray-700">Horario *</label>
                    <select name="horario_id" id="horario_id" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Seleccionar horario</option>
                        @foreach($horarios as $horario)
                            <option value="{{ $horario->id }}" {{ old('horario_id', $grupo->horario_id) == $horario->id ? 'selected' : '' }}>
                                {{ $horario->dias_semana }} - 
                                {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} a 
                                {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                            </option>
                        @endforeach
                    </select>
                    @error('horario_id')
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
            </div>

            <!-- Información actual del grupo -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-medium text-gray-700 mb-3">Información Actual del Grupo</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium">Materia:</span> {{ $grupo->materia->nombre_materia }} ({{ $grupo->materia->sigla_materia }})
                    </div>
                    <div>
                        <span class="font-medium">Aula:</span> {{ $grupo->aula->nro_aula }}
                    </div>
                    <div>
                        <span class="font-medium">Horario:</span> {{ $grupo->horario->dias_semana }} - 
                        {{ \Carbon\Carbon::parse($grupo->horario->hora_inicio)->format('H:i') }} a 
                        {{ \Carbon\Carbon::parse($grupo->horario->hora_fin)->format('H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">Cupos:</span> Min: {{ $grupo->cupo_minimo }} / Max: {{ $grupo->cupo_maximo }}
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('grupos.index') }}" 
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