@extends('layouts.app')

@section('title', 'Editar Aula')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Editar Aula: {{ $aula->nro_aula }}</h2>
        </div>
        
        <form action="{{ route('aulas.update', $aula) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Número de Aula -->
                <div class="md:col-span-2">
                    <label for="nro_aula" class="block text-sm font-medium text-gray-700">Número de Aula *</label>
                    <input type="text" name="nro_aula" id="nro_aula" value="{{ old('nro_aula', $aula->nro_aula) }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ej: A-101, LAB-201"
                           required>
                    @error('nro_aula')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo de Aula -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de Aula *</label>
                    <select name="tipo" id="tipo" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Seleccionar tipo</option>
                        @foreach($tiposAula as $key => $value)
                            <option value="{{ $key }}" {{ old('tipo', $aula->tipo) == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('tipo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacidad -->
                <div>
                    <label for="capacidad" class="block text-sm font-medium text-gray-700">Capacidad *</label>
                    <input type="number" name="capacidad" id="capacidad" value="{{ old('capacidad', $aula->capacidad) }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           min="1" max="500"
                           required>
                    @error('capacidad')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Piso -->
                <div class="md:col-span-2">
                    <label for="piso" class="block text-sm font-medium text-gray-700">Piso *</label>
                    <input type="number" name="piso" id="piso" value="{{ old('piso', $aula->piso) }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           min="0" max="20"
                           required>
                    @error('piso')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('aulas.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Actualizar Aula
                </button>
            </div>
        </form>
    </div>
</div>
@endsection