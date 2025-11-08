@extends('layouts.app')

@section('title', 'Asignar Materias al Grupo')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Asignar Materias al Grupo</h2>
        <p class="text-gray-600">{{ $grupo->nombre_grupo }} ({{ $grupo->codigo_grupo }})</p>
    </div>

    <form action="{{ route('grupos.store-materias', $grupo) }}" method="POST">
        @csrf
        
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Materias del Grupo</h3>
                <p class="text-sm text-gray-600">Máximo 6 materias por grupo</p>
            </div>
            
            <div class="p-6">
                <div id="materias-container">
                    @if($grupo->grupoMaterias->count() > 0)
                        @foreach($grupo->grupoMaterias as $index => $grupoMateria)
                        <div class="materia-item border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Materia -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Materia</label>
                                    <select name="materias[{{ $index }}][materia_id]" 
                                            class="materia-select block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Seleccionar materia</option>
                                        @foreach($materias as $materia)
                                        <option value="{{ $materia->id }}" 
                                            {{ $grupoMateria->materia_id == $materia->id ? 'selected' : '' }}>
                                            {{ $materia->id}} - {{ $materia->nombre_materia }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Docente -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Docente</label>
                                    <select name="materias[{{ $index }}][docente_id]" 
                                            class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Seleccionar docente</option>
                                        @foreach($docentes as $docente)
                                        <option value="{{ $docente->id }}"
                                            {{ $grupoMateria->docente_id == $docente->id ? 'selected' : '' }}>
                                            {{ $docente->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Horas -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Horas Semanales</label>
                                    <input type="number" name="materias[{{ $index }}][horas_asignadas]" 
                                           value="{{ $grupoMateria->horas_asignadas }}"
                                           min="1" max="20"
                                           class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                            </div>
                            
                            <button type="button" class="remove-materia mt-2 text-red-600 hover:text-red-800 text-sm">
                                Eliminar materia
                            </button>
                        </div>
                        @endforeach
                    @else
                        <!-- Materia inicial -->
                        <div class="materia-item border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Materia -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Materia</label>
                                    <select name="materias[0][materia_id]" 
                                            class="materia-select block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Seleccionar materia</option>
                                        @foreach($materias as $materia)
                                        <option value="{{ $materia->id }}">
                                            {{ $materia->id}} - {{ $materia->nombre_materia }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Docente -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Docente</label>
                                    <select name="materias[0][docente_id]" 
                                            class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Seleccionar docente</option>
                                        @foreach($docentes as $docente)
                                        <option value="{{ $docente->id }}">
                                            {{ $docente->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Horas -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Horas Semanales</label>
                                    <input type="number" name="materias[0][horas_asignadas]" 
                                           value="4" min="1" max="20"
                                           class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                            </div>
                            
                            <button type="button" class="remove-materia mt-2 text-red-600 hover:text-red-800 text-sm">
                                Eliminar materia
                            </button>
                        </div>
                    @endif
                </div>

                <button type="button" id="add-materia" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition duration-200 mt-4">
                    + Agregar Materia
                </button>
            </div>
        </div>

        <!-- Botones -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('grupos.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                Guardar Asignaciones
            </button>
        </div>
    </form>
</div>

<script>
    let materiaCount = {{ $grupo->grupoMaterias->count() > 0 ? $grupo->grupoMaterias->count() : 1 }};
    
    document.getElementById('add-materia').addEventListener('click', function() {
        if (materiaCount >= 6) {
            alert('Máximo 6 materias por grupo');
            return;
        }
        
        const container = document.getElementById('materias-container');
        const newItem = document.querySelector('.materia-item').cloneNode(true);
        
        // Actualizar índices
        const inputs = newItem.querySelectorAll('select, input');
        inputs.forEach(input => {
            const name = input.getAttribute('name').replace(/\[\d+\]/, `[${materiaCount}]`);
            input.setAttribute('name', name);
            if (input.type !== 'hidden') {
                input.value = '';
            }
        });
        
        container.appendChild(newItem);
        materiaCount++;
    });

    // Delegación de eventos para eliminar materias
    document.getElementById('materias-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-materia')) {
            if (document.querySelectorAll('.materia-item').length > 1) {
                e.target.closest('.materia-item').remove();
                // Reindexar
                const items = document.querySelectorAll('.materia-item');
                items.forEach((item, index) => {
                    const inputs = item.querySelectorAll('select, input');
                    inputs.forEach(input => {
                        const name = input.getAttribute('name').replace(/\[\d+\]/, `[${index}]`);
                        input.setAttribute('name', name);
                    });
                });
                materiaCount = items.length;
            } else {
                alert('Debe haber al menos una materia');
            }
        }
    });
</script>
@endsection