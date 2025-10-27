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
                           placeholder="Ej: INF-101-A" required maxlength="20">
                    @error('sigla_grupo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cupo Mínimo -->
                <div>
                    <label for="cupo_minimo" class="block text-sm font-medium text-gray-700">Cupo Mínimo *</label>
                    <input type="number" name="cupo_minimo" id="cupo_minimo" value="{{ old('cupo_minimo', 1) }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           min="1" max="999" required>
                    @error('cupo_minimo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cupo Máximo -->
                <div>
                    <label for="cupo_maximo" class="block text-sm font-medium text-gray-700">Cupo Máximo *</label>
                    <input type="number" name="cupo_maximo" id="cupo_maximo" value="{{ old('cupo_maximo', 30) }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           min="1" max="999" required>
                    @error('cupo_maximo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Validación cupos -->
                <div class="md:col-span-3">
                    <div id="cupo-error" class="hidden text-sm text-red-600 mb-2">
                        El cupo mínimo no puede ser mayor al cupo máximo
                    </div>
                </div>

                <!-- Descripción -->
                <div class="md:col-span-3">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" maxlength="500"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Descripción opcional del grupo">{{ old('descripcion') }}</textarea>
                    <div class="text-xs text-gray-500 mt-1">
                        <span id="descripcion-contador">0</span>/500 caracteres
                    </div>
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

                <!-- Mensajes de error generales -->
                <div id="materias-error" class="hidden text-sm text-red-600 mt-2">
                    Debe agregar al menos una materia completa al grupo
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('grupos.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    Cancelar
                </a>
                <button type="submit" id="submit-btn"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    Crear Grupo
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Template para materia -->
<template id="materia-template">
    <div class="materia-item border border-gray-300 rounded-lg p-4 mb-4 bg-gray-50" data-materia-index="">
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
                <div class="materia-error hidden text-xs text-red-600 mt-1"></div>
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
                <div class="docente-error hidden text-xs text-red-600 mt-1"></div>
            </div>

            <!-- Aula -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Aula *</label>
                <select name="materias[0][aula_id]" class="aula-select mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Seleccionar aula</option>
                    @foreach($aulas as $aula)
                        <option value="{{ $aula->id }}" data-capacidad="{{ $aula->capacidad }}">{{ $aula->nro_aula }} - {{ $aula->tipo }} (Cap: {{ $aula->capacidad }})</option>
                    @endforeach
                </select>
                <div class="aula-error hidden text-xs text-red-600 mt-1"></div>
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
                <div class="horario-error hidden text-xs text-red-600 mt-1"></div>
            </div>
        </div>

        <!-- Combinación única materia-docente -->
        <div class="combinacion-error hidden text-xs text-red-600 mt-2"></div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let materiaCount = 0;
    const materiasContainer = document.getElementById('materias-container');
    const materiaTemplate = document.getElementById('materia-template');
    const agregarMateriaBtn = document.getElementById('agregarMateria');
    const submitBtn = document.getElementById('submit-btn');
    
    // Conjuntos para controlar restricciones únicas
    const horariosSeleccionados = new Map(); // horario_id -> materia_index
    const combinacionesMateriaDocente = new Map(); // "materia_id-docente_id" -> materia_index
    const materiasSeleccionadas = new Map(); // materia_id -> materia_index

    // Agregar primera materia por defecto
    agregarMateria();

    // Event Listeners para validaciones básicas
    document.getElementById('cupo_minimo').addEventListener('input', validarCupos);
    document.getElementById('cupo_maximo').addEventListener('input', validarCupos);
    document.getElementById('descripcion').addEventListener('input', actualizarContadorDescripcion);

    agregarMateriaBtn.addEventListener('click', function() {
        if (materiaCount < 10) { // Límite razonable de materias por grupo
            agregarMateria();
        } else {
            alert('Máximo 10 materias por grupo permitidas.');
        }
    });

    function agregarMateria() {
        const materiaClone = materiaTemplate.content.cloneNode(true);
        const materiaItem = materiaClone.querySelector('.materia-item');
        const index = materiaCount;
        
        // Actualizar números y nombres
        materiaItem.setAttribute('data-materia-index', index);
        materiaItem.querySelector('.materia-number').textContent = index + 1;
        
        const inputs = materiaItem.querySelectorAll('select');
        inputs.forEach(input => {
            const name = input.getAttribute('name').replace('[0]', `[${index}]`);
            input.setAttribute('name', name);
            input.setAttribute('data-index', index);
        });

        // Configurar event listeners para esta materia
        configurarEventListenersMateria(materiaItem, index);

        materiasContainer.appendChild(materiaItem);
        materiaCount++;
        actualizarEstadoBotonEnviar();
    }

    function configurarEventListenersMateria(materiaItem, index) {
        const materiaSelect = materiaItem.querySelector('.materia-select');
        const docenteSelect = materiaItem.querySelector('.docente-select');
        const aulaSelect = materiaItem.querySelector('.aula-select');
        const horarioSelect = materiaItem.querySelector('.horario-select');
        const removerBtn = materiaItem.querySelector('.remover-materia');

        // Event listeners para cambios
        materiaSelect.addEventListener('change', () => {
            validarMateriaUnica(materiaItem, index);
            validarCombinacionMateriaDocente(materiaItem, index);
            actualizarEstadoBotonEnviar();
        });
        
        docenteSelect.addEventListener('change', () => {
            validarCombinacionMateriaDocente(materiaItem, index);
            actualizarEstadoBotonEnviar();
        });
        
        aulaSelect.addEventListener('change', () => {
            validarCapacidadAula(materiaItem, index);
            actualizarEstadoBotonEnviar();
        });
        
        horarioSelect.addEventListener('change', () => {
            validarHorarioUnico(materiaItem, index);
            actualizarEstadoBotonEnviar();
        });

        // Remover materia
        removerBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.materia-item').length > 1) {
                removerMateria(materiaItem, index);
            } else {
                alert('El grupo debe tener al menos una materia.');
            }
        });

        // Validar en tiempo real
        ['change', 'blur'].forEach(event => {
            materiaSelect.addEventListener(event, () => validarCampo(materiaSelect));
            docenteSelect.addEventListener(event, () => validarCampo(docenteSelect));
            aulaSelect.addEventListener(event, () => validarCampo(aulaSelect));
            horarioSelect.addEventListener(event, () => validarCampo(horarioSelect));
        });
    }

    function removerMateria(materiaItem, index) {
        // Remover de los conjuntos de control
        const horarioSelect = materiaItem.querySelector('.horario-select');
        const materiaSelect = materiaItem.querySelector('.materia-select');
        const docenteSelect = materiaItem.querySelector('.docente-select');
        
        const horarioId = horarioSelect ? horarioSelect.value : null;
        const materiaId = materiaSelect ? materiaSelect.value : null;
        const docenteId = docenteSelect ? docenteSelect.value : null;

        if (horarioId) horariosSeleccionados.delete(horarioId);
        
        if (materiaId && docenteId) {
            const clave = `${materiaId}-${docenteId}`;
            combinacionesMateriaDocente.delete(clave);
        }
        
        if (materiaId) {
            materiasSeleccionadas.forEach((value, key) => {
                if (value === index) {
                    materiasSeleccionadas.delete(key);
                }
            });
        }

        materiaItem.remove();
        materiaCount--;
        actualizarNumerosMaterias();
        actualizarEstadoBotonEnviar();
    }

    function validarMateriaUnica(materiaItem, index) {
        const materiaSelect = materiaItem.querySelector('.materia-select');
        const materiaId = materiaSelect.value;
        const errorDiv = materiaItem.querySelector('.materia-error');

        if (!materiaId) {
            limpiarError(errorDiv);
            // Remover del mapa si estaba presente
            materiasSeleccionadas.forEach((value, key) => {
                if (value === index) {
                    materiasSeleccionadas.delete(key);
                }
            });
            return true;
        }

        // Verificar si la materia ya está en otra posición
        let materiaDuplicada = false;
        let materiaIndexExistente = null;

        materiasSeleccionadas.forEach((existingIndex, existingMateriaId) => {
            if (existingMateriaId === materiaId && existingIndex !== index) {
                materiaDuplicada = true;
                materiaIndexExistente = existingIndex;
            }
        });

        if (materiaDuplicada) {
            mostrarError(errorDiv, `Esta materia ya está asignada a la materia #${materiaIndexExistente + 1}.`);
            return false;
        } else {
            // Remover cualquier entrada previa para este índice
            materiasSeleccionadas.forEach((value, key) => {
                if (value === index) {
                    materiasSeleccionadas.delete(key);
                }
            });
            // Agregar la nueva materia
            materiasSeleccionadas.set(materiaId, index);
            limpiarError(errorDiv);
            return true;
        }
    }

    function validarCombinacionMateriaDocente(materiaItem, index) {
        const materiaSelect = materiaItem.querySelector('.materia-select');
        const docenteSelect = materiaItem.querySelector('.docente-select');
        const materiaId = materiaSelect.value;
        const docenteId = docenteSelect.value;
        const errorDiv = materiaItem.querySelector('.combinacion-error');

        if (!materiaId || !docenteId) {
            limpiarError(errorDiv);
            return true;
        }

        const clave = `${materiaId}-${docenteId}`;

        // Verificar combinación única (restricción de base de datos)
        let combinacionDuplicada = false;
        let combinacionIndexExistente = null;

        combinacionesMateriaDocente.forEach((existingIndex, existingClave) => {
            if (existingClave === clave && existingIndex !== index) {
                combinacionDuplicada = true;
                combinacionIndexExistente = existingIndex;
            }
        });

        if (combinacionDuplicada) {
            mostrarError(errorDiv, `Esta combinación ya existe en la materia #${combinacionIndexExistente + 1}.`);
            return false;
        } else {
            // Remover cualquier entrada previa para este índice
            combinacionesMateriaDocente.forEach((value, key) => {
                if (value === index) {
                    combinacionesMateriaDocente.delete(key);
                }
            });
            // Agregar la nueva combinación
            combinacionesMateriaDocente.set(clave, index);
            limpiarError(errorDiv);
            return true;
        }
    }

    function validarHorarioUnico(materiaItem, index) {
        const horarioSelect = materiaItem.querySelector('.horario-select');
        const horarioId = horarioSelect.value;
        const errorDiv = materiaItem.querySelector('.horario-error');

        if (!horarioId) {
            limpiarError(errorDiv);
            return true;
        }

        // Verificar horario único por grupo (restricción de base de datos)
        let horarioDuplicado = false;
        let horarioIndexExistente = null;

        horariosSeleccionados.forEach((existingIndex, existingHorarioId) => {
            if (existingHorarioId === horarioId && existingIndex !== index) {
                horarioDuplicado = true;
                horarioIndexExistente = existingIndex;
            }
        });

        if (horarioDuplicado) {
            mostrarError(errorDiv, `Este horario ya está asignado a la materia #${horarioIndexExistente + 1}.`);
            return false;
        } else {
            // Remover cualquier entrada previa para este índice
            horariosSeleccionados.forEach((value, key) => {
                if (value === index) {
                    horariosSeleccionados.delete(key);
                }
            });
            // Agregar el nuevo horario
            horariosSeleccionados.set(horarioId, index);
            limpiarError(errorDiv);
            return true;
        }
    }

    function validarCapacidadAula(materiaItem, index) {
        const aulaSelect = materiaItem.querySelector('.aula-select');
        const cupoMaximo = parseInt(document.getElementById('cupo_maximo').value) || 0;
        const aulaId = aulaSelect.value;
        const errorDiv = materiaItem.querySelector('.aula-error');

        if (!aulaId) {
            limpiarError(errorDiv);
            return true;
        }

        const capacidad = parseInt(aulaSelect.selectedOptions[0].getAttribute('data-capacidad')) || 0;

        if (capacidad > 0 && cupoMaximo > capacidad) {
            mostrarError(errorDiv, `El cupo máximo (${cupoMaximo}) supera la capacidad del aula (${capacidad}).`);
            return false;
        } else {
            limpiarError(errorDiv);
            return true;
        }
    }

    function validarCupos() {
        const cupoMinimo = parseInt(document.getElementById('cupo_minimo').value) || 0;
        const cupoMaximo = parseInt(document.getElementById('cupo_maximo').value) || 0;
        const errorDiv = document.getElementById('cupo-error');

        if (cupoMinimo > cupoMaximo) {
            mostrarError(errorDiv, 'El cupo mínimo no puede ser mayor al cupo máximo');
            return false;
        } else {
            limpiarError(errorDiv);
            
            // Revalidar capacidades de aulas si hay cambio en cupo máximo
            document.querySelectorAll('.materia-item').forEach(item => {
                const index = item.getAttribute('data-materia-index');
                validarCapacidadAula(item, index);
            });
            
            return true;
        }
    }

    function validarCampo(campo) {
        const value = campo.value.trim();
        const parent = campo.closest('.materia-item');
        
        if (!value) {
            campo.style.borderColor = '#f56565';
            return false;
        } else {
            campo.style.borderColor = '#d1d5db';
            return true;
        }
    }

    function actualizarContadorDescripcion() {
        const descripcion = document.getElementById('descripcion');
        const contador = document.getElementById('descripcion-contador');
        contador.textContent = descripcion.value.length;
    }

    function actualizarNumerosMaterias() {
        const materiasItems = document.querySelectorAll('.materia-item');
        
        materiasItems.forEach((item, index) => {
            item.querySelector('.materia-number').textContent = index + 1;
            const oldIndex = item.getAttribute('data-materia-index');
            item.setAttribute('data-materia-index', index);
            
            // Actualizar índices en los names
            const inputs = item.querySelectorAll('select');
            inputs.forEach(input => {
                const currentName = input.getAttribute('name');
                const newName = currentName.replace(/materias\[\d+\]/, `materias[${index}]`);
                input.setAttribute('name', newName);
                input.setAttribute('data-index', index);
            });
            
            // Actualizar índices en los mapas
            actualizarIndicesEnMapas(parseInt(oldIndex), index);
        });
    }

    function actualizarIndicesEnMapas(oldIndex, newIndex) {
        // Actualizar materiasSeleccionadas
        const nuevasMaterias = new Map();
        materiasSeleccionadas.forEach((index, materiaId) => {
            if (index === oldIndex) {
                nuevasMaterias.set(materiaId, newIndex);
            } else {
                nuevasMaterias.set(materiaId, index);
            }
        });
        materiasSeleccionadas.clear();
        nuevasMaterias.forEach((index, materiaId) => {
            materiasSeleccionadas.set(materiaId, index);
        });
        
        // Actualizar combinacionesMateriaDocente
        const nuevasCombinaciones = new Map();
        combinacionesMateriaDocente.forEach((index, combinacion) => {
            if (index === oldIndex) {
                nuevasCombinaciones.set(combinacion, newIndex);
            } else {
                nuevasCombinaciones.set(combinacion, index);
            }
        });
        combinacionesMateriaDocente.clear();
        nuevasCombinaciones.forEach((index, combinacion) => {
            combinacionesMateriaDocente.set(combinacion, index);
        });
        
        // Actualizar horariosSeleccionados
        const nuevosHorarios = new Map();
        horariosSeleccionados.forEach((index, horarioId) => {
            if (index === oldIndex) {
                nuevosHorarios.set(horarioId, newIndex);
            } else {
                nuevosHorarios.set(horarioId, index);
            }
        });
        horariosSeleccionados.clear();
        nuevosHorarios.forEach((index, horarioId) => {
            horariosSeleccionados.set(horarioId, index);
        });
    }

    function actualizarEstadoBotonEnviar() {
        const hayMaterias = materiaCount > 0;
        const cuposValidos = validarCupos();
        
        // Solo validar materias si hay al menos una
        const materiasValidas = hayMaterias ? validarTodasLasMaterias() : false;
        
        submitBtn.disabled = !(hayMaterias && cuposValidos && materiasValidas);
    }

    function validarTodasLasMaterias() {
        let todasValidas = true;
        let hayMateriasConDatos = false;
        
        document.querySelectorAll('.materia-item').forEach(item => {
            const index = item.getAttribute('data-materia-index');
            
            // Validar que todos los campos estén llenos
            const selects = item.querySelectorAll('select');
            const camposLlenos = Array.from(selects).every(select => {
                return select.value !== '';
            });
            
            if (camposLlenos) {
                hayMateriasConDatos = true;
                
                const materiaValida = validarMateriaUnica(item, index);
                const combinacionValida = validarCombinacionMateriaDocente(item, index);
                const horarioValido = validarHorarioUnico(item, index);
                const aulaValida = validarCapacidadAula(item, index);
                
                if (!(materiaValida && combinacionValida && horarioValido && aulaValida)) {
                    todasValidas = false;
                }
            } else {
                // Si hay campos vacíos pero es la única materia, marcar error
                if (document.querySelectorAll('.materia-item').length === 1) {
                    todasValidas = false;
                }
            }
        });
        
        // Mostrar/ocultar mensaje general de error
        const materiasError = document.getElementById('materias-error');
        if (!hayMateriasConDatos && document.querySelectorAll('.materia-item').length > 0) {
            mostrarError(materiasError, 'Debe completar todos los campos de al menos una materia');
            return false;
        } else {
            limpiarError(materiasError);
        }
        
        return todasValidas;
    }

    function mostrarError(elemento, mensaje) {
        elemento.textContent = mensaje;
        elemento.classList.remove('hidden');
    }

    function limpiarError(elemento) {
        elemento.textContent = '';
        elemento.classList.add('hidden');
    }

    // Validación final antes de enviar
    document.getElementById('grupoForm').addEventListener('submit', function(e) {
        if (!validarCupos() || !validarTodasLasMaterias()) {
            e.preventDefault();
            alert('Por favor, corrige los errores en el formulario antes de enviar.');
            return;
        }

        // Validación adicional de combinaciones únicas
        const combinaciones = new Set();
        const horarios = new Set();
        let hayDuplicados = false;

        document.querySelectorAll('.materia-item').forEach(item => {
            const materiaId = item.querySelector('.materia-select').value;
            const docenteId = item.querySelector('.docente-select').value;
            const horarioId = item.querySelector('.horario-select').value;

            const combinacion = `${materiaId}-${docenteId}`;
            
            if (combinaciones.has(combinacion)) {
                hayDuplicados = true;
                item.querySelector('.combinacion-error').textContent = 'Combinación duplicada detectada.';
                item.querySelector('.combinacion-error').classList.remove('hidden');
            } else {
                combinaciones.add(combinacion);
            }

            if (horarios.has(horarioId)) {
                hayDuplicados = true;
                item.querySelector('.horario-error').textContent = 'Horario duplicado detectado.';
                item.querySelector('.horario-error').classList.remove('hidden');
            } else {
                horarios.add(horarioId);
            }
        });

        if (hayDuplicados) {
            e.preventDefault();
            alert('Error: No puedes tener combinaciones de materia-docente u horarios duplicados en el mismo grupo.');
        }
    });

    // Inicializar contador de descripción
    actualizarContadorDescripcion();
    
    // Validación inicial
    setTimeout(() => {
        actualizarEstadoBotonEnviar();
    }, 100);
});
</script>
@endsection