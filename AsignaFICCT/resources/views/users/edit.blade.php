@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Editar Usuario: {{ $user->nombre }}</h2>
        </div>
        
        <form action="{{ route('users.update', $user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- CI -->
        <div>
            <label for="ci" class="block text-sm font-medium text-gray-700">Cédula de Identidad *</label>
            <input type="text" name="ci" id="ci" value="{{ old('ci', $user->ci) }}" 
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('ci')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nombre -->
        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre Completo *</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $user->nombre) }}"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('nombre')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
            <input type="email" name="correo" id="correo" value="{{ old('correo', $user->correo) }}"
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
                <option value="admin" {{ old('rol', $user->rol) == 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="docente" {{ old('rol', $user->rol) == 'docente' ? 'selected' : '' }}>Docente</option>
            </select>
            @error('rol')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Campo Profesión (solo para docentes) -->
        <div id="profesion-field" class="{{ (old('rol', $user->rol) === 'docente') ? '' : 'hidden' }}">
            <label for="profesion" class="block text-sm font-medium text-gray-700">
                Profesión <span class="text-red-500">{{ (old('rol', $user->rol) === 'docente') ? '*' : '' }}</span>
            </label>
            <input type="text" name="profesion" id="profesion" 
                   value="{{ old('profesion', $user->docente->profesion ?? '') }}"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   {{ (old('rol', $user->rol) === 'docente') ? 'required' : '' }}>
            @error('profesion')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

            <!-- Campo de contraseña opcional -->
            <div class="mt-6">
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="change_password" name="change_password" class="mr-2">
                    <label for="change_password" class="text-sm font-medium text-gray-700">Cambiar contraseña</label>
                </div>
                
                <div id="password_fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 hidden">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                        <input type="password" name="password" id="password"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
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
                    Actualizar Usuario
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
        const profesionLabel = profesionField.querySelector('label');
        
        if (rol === 'docente') {
            profesionField.classList.remove('hidden');
            profesionInput.required = true;
            profesionLabel.innerHTML = 'Profesión *';
        } else {
            profesionField.classList.add('hidden');
            profesionInput.required = false;
            profesionInput.value = '';
            profesionLabel.innerHTML = 'Profesión';
        }
    }

    document.getElementById('change_password').addEventListener('change', function() {
        const passwordFields = document.getElementById('password_fields');
        if (this.checked) {
            passwordFields.classList.remove('hidden');
            document.getElementById('password').required = true;
            document.getElementById('password_confirmation').required = true;
        } else {
            passwordFields.classList.add('hidden');
            document.getElementById('password').required = false;
            document.getElementById('password_confirmation').required = false;
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
        }
    });

    // Ejecutar al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        toggleProfesionField();
    });
</script>
@endsection