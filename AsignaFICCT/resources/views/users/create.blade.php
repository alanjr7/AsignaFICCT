@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Crear Nuevo Usuario</h2>
        </div>
        
        <form action="{{ route('users.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- CI -->
                <div>
                    <label for="ci" class="block text-sm font-medium text-gray-700">Cédula de Identidad *</label>
                    <input type="text" name="ci" id="ci" value="{{ old('ci') }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('ci')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre Completo *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
                    <input type="email" name="correo" id="correo" value="{{ old('correo') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('correo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rol -->
                <div>
                    <label for="rol" class="block text-sm font-medium text-gray-700">Rol *</label>
                    <select name="rol" id="rol" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required onchange="toggleProfesionField()">
                        <option value="">Seleccionar rol</option>
                        <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="docente" {{ old('rol') == 'docente' ? 'selected' : '' }}>Docente</option>
                    </select>
                    @error('rol')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Profesión (solo para docentes) -->
                <div id="profesion-field" class="hidden">
                    <label for="profesion" class="block text-sm font-medium text-gray-700">Profesión *</label>
                    <input type="text" name="profesion" id="profesion" value="{{ old('profesion') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('profesion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña *</label>
                    <input type="password" name="password" id="password"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('users.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleProfesionField() {
        const rol = document.getElementById('rol').value;
        const profesionField = document.getElementById('profesion-field');
        const profesionInput = document.getElementById('profesion');
        
        if (rol === 'docente') {
            profesionField.classList.remove('hidden');
            profesionInput.required = true;
        } else {
            profesionField.classList.add('hidden');
            profesionInput.required = false;
            profesionInput.value = '';
        }
    }

    // Ejecutar al cargar la página para setear estado inicial
    document.addEventListener('DOMContentLoaded', function() {
        toggleProfesionField();
    });
</script>
@endsection