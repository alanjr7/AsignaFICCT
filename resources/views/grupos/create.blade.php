@extends('layouts.app')

@section('title', 'Crear Grupo')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Crear Nuevo Grupo</h2>
        </div>
        
        <form action="{{ route('grupos.store') }}" method="POST" class="p-6" id="grupoForm">
            @csrf
            
            <!-- Información básica del grupo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Sigla Grupo -->
                <div>
                    <label for="sigla_grupo" class="block text-sm font-medium text-gray-700">Sigla del Grupo *</label>
                    <input type="text" name="sigla_grupo" id="sigla_grupo" value="{{ old('sigla_grupo') }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ej: INF-101-A" required>
                    @error('sigla_grupo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cupo Mínimo -->
                <div>
                    <label for="cupo_minimo" class="block text-sm font-medium text-gray-700">Cupo Mínimo *</label>
                    <input type="number" name="cupo_minimo" id="cupo_minimo" value="{{ old('cupo_minimo', 1) }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           min="1" required>
                    @error('cupo_minimo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cupo Máximo -->
                <div>
                    <label for="cupo_maximo" class="block text-sm font-medium text-gray-700">Cupo Máximo *</label>
                    <input type="number" name="cupo_maximo" id="cupo_maximo" value="{{ old('cupo_maximo', 30) }}" 
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
                              placeholder="Descripción opcional del grupo">{{ old('descripcion') }}</textarea>
                </div>
            </div>

            <!-- Sección de materias -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Materias del Grupo</h3>
                    <button type="button" id="agregarMateria" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                        + Agregar Materia
                    </button>
                </div>

                <div id="materias-container">
                    <!-- Las materias se agregarán aquí dinámicamente -->
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('grupos.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Crear Grupo
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Template para materia -->
<template id="materia-template">
    <div class="materia-item border border-gray-300 rounded-lg p-4 mb-4 bg-gray-50">
        <div class="flex justify-between items-center mb-3">
            <h4 class="text-md font-medium text-gray-700">Materia #<span class="materia-number">1</span></h4>
            <button type="button" class="remover-materia text-red-600 hover:text-red-800">
                ✕ Remover
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Materia -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Materia *</label>
                <select name="materias[0][materia_id]" class="materia-select mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Seleccionar materia</option>
                    @foreach($materias as $materia)
                        <option value="{{ $materia->sigla_materia }}">{{ $materia->sigla_materia }} - {{ $materia->nombre_materia }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Docente -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Docente *</label>
                <select name="materias[0][docente_id]" class="docente-select mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Seleccionar docente</option>
                    @foreach($docentes as $docente)
                        <option value="{{ $docente->id }}">{{ $docente->user->nombre }} ({{ $docente->codigo_docente }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Aula -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Aula *</label>
                <select name="materias[0][aula_id]" class="aula-select mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Seleccionar aula</option>
                    @foreach($aulas as $aula)
                        <option value="{{ $aula->id }}">{{ $aula->nro_aula }} - {{ $aula->tipo }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Horario -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Horario *</label>
                <select name="materias[0][horario_id]" class="horario-select mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Seleccionar horario</option>
                    @foreach($horarios as $horario)
                        <option value="{{ $horario->id }}">{{ $horario->dias_semana }} - {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}/{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let materiaCount = 0;
    const materiasContainer = document.getElementById('materias-container');
    const materiaTemplate = document.getElementById('materia-template');
    const agregarMateriaBtn = document.getElementById('agregarMateria');
    const horariosSeleccionados = new Set();

    // Agregar primera materia por defecto
    agregarMateria();

    agregarMateriaBtn.addEventListener('click', agregarMateria);

    function agregarMateria() {
        const materiaClone = materiaTemplate.content.cloneNode(true);
        const materiaItem = materiaClone.querySelector('.materia-item');
        const horarioSelect = materiaItem.querySelector('.horario-select');
        
        // Actualizar números y nombres
        const inputs = materiaItem.querySelectorAll('select');
        inputs.forEach(input => {
            const name = input.getAttribute('name').replace('[0]', `[${materiaCount}]`);
            input.setAttribute('name', name);
        });

        // Actualizar número de materia
        materiaItem.querySelector('.materia-number').textContent = materiaCount + 1;

        // Validar horario al cambiar
        horarioSelect.addEventListener('change', function() {
            validarHorarioUnico(this);
        });

        // Agregar funcionalidad de remover
        materiaItem.querySelector('.remover-materia').addEventListener('click', function() {
            if (document.querySelectorAll('.materia-item').length > 1) {
                // Remover horario del conjunto
                const horarioId = materiaItem.querySelector('.horario-select').value;
                if (horarioId) {
                    horariosSeleccionados.delete(horarioId);
                }
                materiaItem.remove();
                actualizarNumeros();
            } else {
                alert('El grupo debe tener al menos una materia.');
            }
        });

        materiasContainer.appendChild(materiaItem);
        materiaCount++;
    }

    function validarHorarioUnico(selectElement) {
        const horarioId = selectElement.value;
        const materiaItem = selectElement.closest('.materia-item');
        
        if (horarioId) {
            if (horariosSeleccionados.has(horarioId)) {
                // Horario duplicado encontrado
                selectElement.style.borderColor = 'red';
                mostrarError(selectElement, 'Este horario ya está asignado a otra materia en este grupo.');
                
                // Deshabilitar el botón de enviar
                document.querySelector('button[type="submit"]').disabled = true;
            } else {
                // Horario válido
                selectElement.style.borderColor = '';
                removerError(selectElement);
                
                // Actualizar conjunto
                const oldHorario = materiaItem.dataset.horarioId;
                if (oldHorario) {
                    horariosSeleccionados.delete(oldHorario);
                }
                horariosSeleccionados.add(horarioId);
                materiaItem.dataset.horarioId = horarioId;
                
                // Habilitar el botón de enviar si no hay errores
                document.querySelector('button[type="submit"]').disabled = false;
            }
        }
    }

    function mostrarError(elemento, mensaje) {
        // Remover error anterior si existe
        removerError(elemento);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'mt-1 text-sm text-red-600 error-message';
        errorDiv.textContent = mensaje;
        elemento.parentNode.appendChild(errorDiv);
    }

    function removerError(elemento) {
        const errorExistente = elemento.parentNode.querySelector('.error-message');
        if (errorExistente) {
            errorExistente.remove();
        }
    }

    function actualizarNumeros() {
        const items = document.querySelectorAll('.materia-item');
        items.forEach((item, index) => {
            item.querySelector('.materia-number').textContent = index + 1;
        });
    }

    // Validar formulario antes de enviar
    document.getElementById('grupoForm').addEventListener('submit', function(e) {
        const horariosUnicos = new Set();
        let hayDuplicados = false;
        
        document.querySelectorAll('.horario-select').forEach(select => {
            if (select.value) {
                if (horariosUnicos.has(select.value)) {
                    hayDuplicados = true;
                    select.style.borderColor = 'red';
                    mostrarError(select, 'Horario duplicado detectado.');
                } else {
                    horariosUnicos.add(select.value);
                }
            }
        });
        
        if (hayDuplicados) {
            e.preventDefault();
            alert('Error: No puedes asignar el mismo horario a múltiples materias en el mismo grupo.');
        }
    });
});
</script>
@endsection