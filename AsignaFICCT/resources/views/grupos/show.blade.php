@extends('layouts.app')

@section('title', 'Detalles del Grupo')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Grupo: {{ $grupo->sigla_grupo }}</h2>
        <div class="space-x-2">
            <a href="{{ route('grupos.edit', $grupo) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                Editar Grupo
            </a>
            <a href="{{ route('grupos.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                Volver a Lista
            </a>
        </div>
    </div>

    <!-- Información del Grupo -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Información del Grupo</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Sigla del Grupo</h4>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $grupo->sigla_grupo }}</p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Cupo Mínimo</h4>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $grupo->cupo_minimo }} estudiantes</p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Cupo Máximo</h4>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $grupo->cupo_maximo }} estudiantes</p>
                </div>
                
                @if($grupo->descripcion)
                <div class="md:col-span-3">
                    <h4 class="text-sm font-medium text-gray-500">Descripción</h4>
                    <p class="mt-1 text-gray-900">{{ $grupo->descripcion }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Materias del Grupo -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Materias del Grupo</h3>
            <button onclick="mostrarModalAgregarMateria()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200 text-sm">
                + Agregar Materia
            </button>
        </div>
        
        <div class="p-6">
            @if($grupo->grupoMaterias->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aula</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($grupo->grupoMaterias as $grupoMateria)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $grupoMateria->materia->sigla_materia }}</div>
                                    <div class="text-sm text-gray-500">{{ $grupoMateria->materia->nombre_materia }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($grupoMateria->docente)
                                        <div class="text-sm text-gray-900">{{ $grupoMateria->docente->user->nombre }}</div>
                                        <div class="text-sm text-gray-500">{{ $grupoMateria->docente->codigo_docente }}</div>
                                    @else
                                        <span class="text-sm text-red-500">Sin docente</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($grupoMateria->aula)
                                        {{ $grupoMateria->aula->nro_aula }}
                                    @else
                                        <span class="text-red-500">Sin aula</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($grupoMateria->horario)
                                        <div class="text-sm font-medium text-gray-900">{{ $grupoMateria->horario->dias_semana }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($grupoMateria->horario->hora_inicio)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($grupoMateria->horario->hora_fin)->format('H:i') }}
                                        </div>
                                    @else
                                        <span class="text-sm text-red-500">Sin horario</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form action="{{ route('grupos.eliminar-materia', ['grupo' => $grupo, 'grupoMateria' => $grupoMateria]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition duration-200"
                                                onclick="return confirm('¿Eliminar {{ $grupoMateria->materia->nombre_materia }} del grupo?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="text-gray-500 mb-4">No hay materias asignadas a este grupo.</p>
                    <button onclick="mostrarModalAgregarMateria()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        Agregar Primera Materia
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>


<!-- Modal para agregar materia -->
<div id="modalAgregarMateria" class="fixed inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50 z-50 hidden overflow-y-auto">
    <div class="relative w-full max-w-lg mx-4 md:mx-auto bg-white rounded-lg shadow-lg max-h-[90vh] overflow-y-auto p-6">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-4 pb-2 border-b">
            <h3 class="text-lg font-medium text-gray-800">Agregar Materia al Grupo</h3>
            <button onclick="cerrarModalAgregarMateria()" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>

        <!-- Formulario -->
        <form action="{{ route('grupos.agregar-materia', $grupo) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <!-- Materia -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Materia *</label>
                    <select name="materia_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        <option value="">Seleccionar materia</option>
                        @foreach($materias as $materia)
                            <option value="{{ $materia->sigla_materia }}">{{ $materia->sigla_materia }} - {{ $materia->nombre_materia }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Docente -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Docente *</label>
                    <select name="docente_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        <option value="">Seleccionar docente</option>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->id }}">{{ $docente->user->nombre }} ({{ $docente->codigo_docente }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Aula -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Aula *</label>
                    <select name="aula_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        <option value="">Seleccionar aula</option>
                        @foreach($aulas as $aula)
                            <option value="{{ $aula->id }}">{{ $aula->nro_aula }} - {{ $aula->tipo }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Horario -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Horario *</label>
                    <select name="horario_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        <option value="">Seleccionar horario</option>
                        @foreach($horarios as $horario)
                            <option value="{{ $horario->id }}">
                                {{ $horario->dias_semana }} - 
                                {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}/{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="cerrarModalAgregarMateria()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200 text-sm">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200 text-sm">
                    Agregar Materia
                </button>
            </div>
        </form>
    </div>
</div>


<script>
function mostrarModalAgregarMateria() {
    document.getElementById('modalAgregarMateria').classList.remove('hidden');
}

function cerrarModalAgregarMateria() {
    document.getElementById('modalAgregarMateria').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('modalAgregarMateria');
    if (event.target === modal) {
        cerrarModalAgregarMateria();
    }
}
</script>
@endsection