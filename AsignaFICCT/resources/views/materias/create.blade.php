@extends('layouts.app')

@section('title', 'Crear Materia')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Crear Nueva Materia</h2>
        </div>
        
        <form action="{{ route('materias.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Sigla Materia -->
                <div>
                    <label for="sigla_materia" class="block text-sm font-medium text-gray-700">Sigla de la Materia *</label>
                    <input type="text" name="sigla_materia" id="sigla_materia" value="{{ old('sigla_materia') }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 uppercase"
                           placeholder="Ej: MAT101"
                           maxlength="10"
                           required>
                    @error('sigla_materia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Código único identificador de la materia (máx. 10 caracteres)</p>
                </div>

                <!-- Nombre Materia -->
                <div>
                    <label for="nombre_materia" class="block text-sm font-medium text-gray-700">Nombre de la Materia *</label>
                    <input type="text" name="nombre_materia" id="nombre_materia" value="{{ old('nombre_materia') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ej: Matemáticas Básicas"
                           required>
                    @error('nombre_materia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nivel -->
                <div>
                    <label for="nivel" class="block text-sm font-medium text-gray-700">Nivel *</label>
                    <select name="nivel" id="nivel" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Seleccionar nivel</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('nivel') == $i ? 'selected' : '' }}>Nivel {{ $i }}</option>
                        @endfor
                    </select>
                    @error('nivel')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Nivel académico de la materia (1-10)</p>
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('materias.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Crear Materia
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Convertir sigla a mayúsculas automáticamente
    document.getElementById('sigla_materia').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>
@endsection