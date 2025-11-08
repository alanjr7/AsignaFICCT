@extends('layouts.app')

@section('title', 'Asignar Horarios')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Asignar Horarios - {{ $grupoMateria->materia->sigla_materia }}</h1>
                <p class="text-gray-600">Grupo: {{ $grupoMateria->grupo->nombre_grupo }}</p>
                <p class="text-gray-600">
                    Horas semanales: {{ $grupoMateria->horas_asignadas }}h | 
                    Asignadas: {{ $grupoMateria->horasAsignadas() }}h | 
                    Pendientes: {{ $grupoMateria->horasPendientes() }}h
                </p>
            </div>
            <a href="{{ route('horario.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                ← Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Asignar Nuevo Horario</h3>
            
            @if($grupoMateria->horasPendientes() > 0)
            <form action="{{ route('horario-docente.store', $grupoMateria) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Aula *</label>
                        <select name="aula_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione aula</option>
                            @foreach($aulas as $aula)
                                <option value="{{ $aula->id }}">{{ $aula->nro_aula }} ({{ $aula->tipo }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Día *</label>
                        <select name="dia" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione día</option>
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sábado">Sábado</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hora Inicio *</label>
                            <input type="time" name="hora_inicio" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hora Fin *</label>
                            <input type="time" name="hora_fin" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg mt-6 transition duration-200">
                    💾 Asignar Horario
                </button>
            </form>
            @else
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <div class="text-green-600 text-2xl mb-2">✅</div>
                <p class="text-green-800 font-semibold">Todas las horas han sido asignadas</p>
                <p class="text-green-700 text-sm mt-1">No puedes agregar más horarios a esta materia.</p>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Horarios Asignados</h3>
            
            @if($horariosExistentes->count() > 0)
            <div class="space-y-3">
                @foreach($horariosExistentes as $horario)
                <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-semibold text-gray-800">{{ $horario->dia }}</div>
                            <div class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                            </div>
                            <div class="text-sm text-blue-600">{{ $horario->aula->nro_aula ?? 'Sin aula' }}</div>
                        </div>
                        <form action="{{ route('horario-docente.destroy', $horario) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-500 hover:text-red-700 transition duration-200"
                                    onclick="return confirm('¿Está seguro de eliminar este horario?')">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="text-gray-400 text-4xl mb-4">⏰</div>
                <p class="text-gray-500">No hay horarios asignados aún.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection