@extends('layouts.app')

@section('title', 'Crear Grupo')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Crear Nuevo Grupo</h2>
        <p class="text-gray-600">Complete la información del grupo</p>
    </div>

    <form action="{{ route('grupos.store') }}" method="POST">
        @csrf
        
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Información del Grupo</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Código del Grupo -->
                    <div>
                        <label for="codigo_grupo" class="block text-sm font-medium text-gray-700">Código del Grupo *</label>
                        <input type="text" name="codigo_grupo" id="codigo_grupo" value="{{ old('codigo_grupo') }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('codigo_grupo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sigla del Grupo -->
                    <div>
                        <label for="sigla_grupo" class="block text-sm font-medium text-gray-700">Sigla del Grupo *</label>
                        <input type="text" name="sigla_grupo" id="sigla_grupo" value="{{ old('sigla_grupo') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('sigla_grupo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre del Grupo -->
                    <div class="md:col-span-2">
                        <label for="nombre_grupo" class="block text-sm font-medium text-gray-700">Nombre del Grupo *</label>
                        <input type="text" name="nombre_grupo" id="nombre_grupo" value="{{ old('nombre_grupo') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('nombre_grupo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cupo Mínimo -->
                    <div>
                        <label for="cupo_minimo" class="block text-sm font-medium text-gray-700">Cupo Mínimo *</label>
                        <input type="number" name="cupo_minimo" id="cupo_minimo" value="{{ old('cupo_minimo', 10) }}" 
                               min="1" max="100"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('cupo_minimo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cupo Máximo -->
                    <div>
                        <label for="cupo_maximo" class="block text-sm font-medium text-gray-700">Cupo Máximo *</label>
                        <input type="number" name="cupo_maximo" id="cupo_maximo" value="{{ old('cupo_maximo', 40) }}" 
                               min="1" max="100"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('cupo_maximo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- NOTA: La sección de materias se maneja en una vista separada después de crear el grupo -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        Asignación de Materias
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>
                            Primero debe crear el grupo. Luego podrá asignar las materias y docentes 
                            desde la página de edición del grupo.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('grupos.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Crear Grupo
            </button>
        </div>
    </form>
</div>
@endsection