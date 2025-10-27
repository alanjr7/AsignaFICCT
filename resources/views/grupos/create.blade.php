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
                <button type="submit" id="submitBtn"
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
                <div class="error-materia mt-1 text-sm text-red-600 hidden"></div>
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
                <div class="error-docente mt-1 text-sm text-red-600 hidden"></div>
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
                <div class="error-aula mt-1 text-sm text-red-600 hidden"></div>
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
                <div class="error-horario mt-1 text-sm text-red-600 hidden"></div>
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
    const submitBtn = document.getElementById('submitBtn');
    
    // Estructuras para rastrear conflictos
    const horariosSeleccionados = new Set();
    const materiasEnGrupo = new Set();
    const docenteHorarios = new Map(); // docente_id -> Set(horario_id)
    const aulaHorarios = new Map();    // aula_id -> Set(horario_id)

    // Agregar primera materia por defecto
    agregarMateria();

    agregarMateriaBtn.addEventListener('click', agregarMateria);

    function agregarMateria() {
        const materiaClone = materiaTemplate.content.cloneNode(true);
        const materiaItem = materiaClone.querySelector('.materia-item');
        
        // Actualizar números y nombres
        const inputs = materiaItem.querySelectorAll('select');
        inputs.forEach(input => {
            const name = input.getAttribute('name').replace('[0]', `[${materiaCount}]`);
            input.setAttribute('name', name);
        });

        // Actualizar número de materia
        materiaItem.querySelector('.materia-number').textContent = materiaCount + 1;

        // Agregar event listeners para validaciones
        const materiaSelect = materiaItem.querySelector('.materia-select');
        const docenteSelect = materiaItem.querySelector('.docente-select');
        const aulaSelect = materiaItem.querySelector('.aula-select');
        const horarioSelect = materiaItem.querySelector('.horario-select');

        materiaSelect.addEventListener('change', function() {
            validarMateriaUnica(this);
            validarConflictoCompleto(materiaItem);
        });

        docenteSelect.addEventListener('change', function() {
            validarConflictoCompleto(materiaItem);
        });

        aulaSelect.addEventListener('change', function() {
            validarConflictoCompleto(materiaItem);
        });

        horarioSelect.addEventListener('change', function() {
            validarHorarioUnico(this);
            validarConflictoCompleto(materiaItem);
        });

        // Agregar funcionalidad de remover
        materiaItem.querySelector('.remover-materia').addEventListener('click', function() {
            if (document.querySelectorAll('.materia-item').length > 1) {
                // Limpiar registros al remover
                limpiarRegistrosMateria(materiaItem);
                materiaItem.remove();
                actualizarNumeros();
                validarTodoElFormulario();
            } else {
                alert('El grupo debe tener al menos una materia.');
            }
        });

        materiasContainer.appendChild(materiaItem);
        materiaCount++;
    }

    function limpiarRegistrosMateria(materiaItem) {
        const materiaId = materiaItem.querySelector('.materia-select').value;
        const horarioId = materiaItem.querySelector('.horario-select').value;
        const docenteId = materiaItem.querySelector('.docente-select').value;
        const aulaId = materiaItem.querySelector('.aula-select').value;

        if (materiaId) materiasEnGrupo.delete(materiaId);
        if (horarioId) horariosSeleccionados.delete(horarioId);
        
        if (docenteId && horarioId) {
            if (docenteHorarios.has(docenteId)) {
                docenteHorarios.get(docenteId).delete(horarioId);
            }
        }
        
        if (aulaId && horarioId) {
            if (aulaHorarios.has(aulaId)) {
                aulaHorarios.get(aulaId).delete(horarioId);
            }
        }
    }

    function validarMateriaUnica(selectElement) {
        const materiaId = selectElement.value;
        const errorDiv = selectElement.parentNode.querySelector('.error-materia');
        
        if (materiaId) {
            if (materiasEnGrupo.has(materiaId)) {
                mostrarError(errorDiv, 'Esta materia ya está asignada en este grupo.');
                selectElement.style.borderColor = 'red';
                return false;
            } else {
                // Remover materia anterior si existe
                const materiaItem = selectElement.closest('.materia-item');
                const oldMateriaId = materiaItem.dataset.materiaId;
                if (oldMateriaId) {
                    materiasEnGrupo.delete(oldMateriaId);
                }
                
                materiasEnGrupo.add(materiaId);
                materiaItem.dataset.materiaId = materiaId;
                ocultarError(errorDiv);
                selectElement.style.borderColor = '';
                return true;
            }
        }
        ocultarError(errorDiv);
        return true;
    }

    function validarHorarioUnico(selectElement) {
        const horarioId = selectElement.value;
        const errorDiv = selectElement.parentNode.querySelector('.error-horario');
        
        if (horarioId) {
            if (horariosSeleccionados.has(horarioId)) {
                mostrarError(errorDiv, 'Este horario ya está asignado a otra materia en este grupo.');
                selectElement.style.borderColor = 'red';
                return false;
            } else {
                // Remover horario anterior si existe
                const materiaItem = selectElement.closest('.materia-item');
                const oldHorarioId = materiaItem.dataset.horarioId;
                if (oldHorarioId) {
                    horariosSeleccionados.delete(oldHorarioId);
                }
                
                horariosSeleccionados.add(horarioId);
                materiaItem.dataset.horarioId = horarioId;
                ocultarError(errorDiv);
                selectElement.style.borderColor = '';
                return true;
            }
        }
        ocultarError(errorDiv);
        return true;
    }

    function validarConflictoCompleto(materiaItem) {
        const docenteId = materiaItem.querySelector('.docente-select').value;
        const aulaId = materiaItem.querySelector('.aula-select').value;
        const horarioId = materiaItem.querySelector('.horario-select').value;
        
        let tieneConflictos = false;

        // Validar docente en misma hora
        if (docenteId && horarioId) {
            const errorDocente = materiaItem.querySelector('.error-docente');
            if (docenteHorarios.has(docenteId) && docenteHorarios.get(docenteId).has(horarioId)) {
                mostrarError(errorDocente, 'Este docente ya está asignado en otro aula a esta misma hora.');
                materiaItem.querySelector('.docente-select').style.borderColor = 'red';
                tieneConflictos = true;
            } else {
                ocultarError(errorDocente);
                materiaItem.querySelector('.docente-select').style.borderColor = '';
            }
        }

        // Validar aula en misma hora
        if (aulaId && horarioId) {
            const errorAula = materiaItem.querySelector('.error-aula');
            if (aulaHorarios.has(aulaId) && aulaHorarios.get(aulaId).has(horarioId)) {
                mostrarError(errorAula, 'Esta aula ya está ocupada por otro docente a esta misma hora.');
                materiaItem.querySelector('.aula-select').style.borderColor = 'red';
                tieneConflictos = true;
            } else {
                ocultarError(errorAula);
                materiaItem.querySelector('.aula-select').style.borderColor = '';
            }
        }

        return !tieneConflictos;
    }

    function actualizarRegistrosGlobales(materiaItem) {
        const docenteId = materiaItem.querySelector('.docente-select').value;
        const aulaId = materiaItem.querySelector('.aula-select').value;
        const horarioId = materiaItem.querySelector('.horario-select').value;

        // Limpiar registros anteriores
        const oldDocenteId = materiaItem.dataset.docenteId;
        const oldAulaId = materiaItem.dataset.aulaId;
        const oldHorarioId = materiaItem.dataset.horarioId;

        if (oldDocenteId && oldHorarioId) {
            if (docenteHorarios.has(oldDocenteId)) {
                docenteHorarios.get(oldDocenteId).delete(oldHorarioId);
            }
        }

        if (oldAulaId && oldHorarioId) {
            if (aulaHorarios.has(oldAulaId)) {
                aulaHorarios.get(oldAulaId).delete(oldHorarioId);
            }
        }

        // Actualizar con nuevos valores
        if (docenteId && horarioId) {
            if (!docenteHorarios.has(docenteId)) {
                docenteHorarios.set(docenteId, new Set());
            }
            docenteHorarios.get(docenteId).add(horarioId);
            materiaItem.dataset.docenteId = docenteId;
        }

        if (aulaId && horarioId) {
            if (!aulaHorarios.has(aulaId)) {
                aulaHorarios.set(aulaId, new Set());
            }
            aulaHorarios.get(aulaId).add(horarioId);
            materiaItem.dataset.aulaId = aulaId;
        }

        if (horarioId) {
            materiaItem.dataset.horarioId = horarioId;
        }
    }

    function validarTodoElFormulario() {
        let formularioValido = true;
        const materiaItems = document.querySelectorAll('.materia-item');

        // Reset global maps
        docenteHorarios.clear();
        aulaHorarios.clear();
        horariosSeleccionados.clear();
        materiasEnGrupo.clear();

        materiaItems.forEach((item, index) => {
            // Validar materia única
            const materiaSelect = item.querySelector('.materia-select');
            if (!validarMateriaUnica(materiaSelect)) formularioValido = false;

            // Validar horario único
            const horarioSelect = item.querySelector('.horario-select');
            if (!validarHorarioUnico(horarioSelect)) formularioValido = false;

            // Actualizar registros globales para próximas validaciones
            actualizarRegistrosGlobales(item);
        });

        // Segunda pasada para validar conflictos cruzados
        materiaItems.forEach(item => {
            if (!validarConflictoCompleto(item)) formularioValido = false;
        });

        submitBtn.disabled = !formularioValido;
        return formularioValido;
    }

    function mostrarError(errorDiv, mensaje) {
        errorDiv.textContent = mensaje;
        errorDiv.classList.remove('hidden');
    }

    function ocultarError(errorDiv) {
        errorDiv.textContent = '';
        errorDiv.classList.add('hidden');
    }

    function actualizarNumeros() {
        const items = document.querySelectorAll('.materia-item');
        items.forEach((item, index) => {
            item.querySelector('.materia-number').textContent = index + 1;
        });
    }

    // Validar en tiempo real
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('materia-select') || 
            e.target.classList.contains('docente-select') || 
            e.target.classList.contains('aula-select') || 
            e.target.classList.contains('horario-select')) {
            
            const materiaItem = e.target.closest('.materia-item');
            if (materiaItem) {
                actualizarRegistrosGlobales(materiaItem);
                validarTodoElFormulario();
            }
        }
    });

    // Validar formulario antes de enviar
    document.getElementById('grupoForm').addEventListener('submit', function(e) {
        if (!validarTodoElFormulario()) {
            e.preventDefault();
            alert('Por favor, corrige los conflictos en el horario antes de enviar el formulario.');
        }
    });

    // Validación inicial
    validarTodoElFormulario();
});
</script>
@endsection