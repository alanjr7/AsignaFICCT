@extends('layouts.app')

@section('title', 'Gestión de Grupos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Gestión de Grupos</h2>
    <a href="{{ route('grupos.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
        + Añadir Grupo
    </a>
</div>

<div class="bg-white shadow rounded-lg">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sigla Grupo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materias</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docentes</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horarios</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cupos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($grupos as $grupo)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $grupo->sigla_grupo }}</div>
                        @if($grupo->descripcion)
                            <div class="text-sm text-gray-500">{{ Str::limit($grupo->descripcion, 50) }}</div>
                        @endif
                    </td>
                    
                    <!-- Materias -->
                    <td class="px-6 py-4">
                        @if($grupo->grupoMaterias->count() > 0)
                            <div class="space-y-1">
                                @foreach($grupo->grupoMaterias as $grupoMateria)
                                    <div class="text-sm">
                                        <span class="font-medium text-gray-900">{{ $grupoMateria->materia->sigla_materia }}</span>
                                        <span class="text-gray-500">- {{ $grupoMateria->materia->nombre_materia }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-sm text-gray-500">Sin materias</span>
                        @endif
                    </td>
                    
                    <!-- Docentes -->
                    <td class="px-6 py-4">
                        @if($grupo->grupoMaterias->count() > 0)
                            <div class="space-y-1">
                                @foreach($grupo->grupoMaterias as $grupoMateria)
                                    @if($grupoMateria->docente)
                                        <div class="text-sm text-gray-900">
                                            {{ $grupoMateria->docente->user->nombre }}
                                        </div>
                                    @else
                                        <span class="text-sm text-red-500">Sin docente</span>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <span class="text-sm text-gray-500">-</span>
                        @endif
                    </td>
                    
                    <!-- Horarios -->
                    <td class="px-6 py-4">
                        @if($grupo->grupoMaterias->count() > 0)
                            <div class="space-y-1">
                                @foreach($grupo->grupoMaterias as $grupoMateria)
                                    @if($grupoMateria->horario)
                                        <div class="text-sm">
                                            <span class="font-medium text-gray-900">{{ $grupoMateria->horario->dias_semana }}</span>
                                            <div class="text-gray-500 text-xs">
                                                {{ \Carbon\Carbon::parse($grupoMateria->horario->hora_inicio)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($grupoMateria->horario->hora_fin)->format('H:i') }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-red-500">Sin horario</span>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <span class="text-sm text-gray-500">-</span>
                        @endif
                    </td>
                    
                    <!-- Cupos -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        Min: {{ $grupo->cupo_minimo }}<br>
                        Max: {{ $grupo->cupo_maximo }}
                    </td>
                    
                    <!-- Acciones -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('grupos.show', $grupo) }}" class="text-blue-600 hover:text-blue-900" title="Ver detalles">
                            Ver
                        </a>
                        <a href="{{ route('grupos.edit', $grupo) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar grupo">
                            Editar
                        </a>
                        <form action="{{ route('grupos.destroy', $grupo) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                onclick="return confirm('¿Eliminar grupo {{ $grupo->sigla_grupo }}?')"
                                title="Eliminar grupo">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No hay grupos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection