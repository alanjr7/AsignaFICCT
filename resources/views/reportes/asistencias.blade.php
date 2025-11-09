<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $titulo }}</h1>
                    <p class="text-gray-600">Generado el: {{ now()->format('d/m/Y H:i') }}</p>
                    
                    @if(!empty($data['filtros']['fecha_inicio']) || !empty($data['filtros']['docente_id']))
                    <div class="mt-2">
                        <p class="text-sm text-gray-600">
                            Filtros aplicados:
                            @if(!empty($data['filtros']['fecha_inicio']))
                            Desde {{ $data['filtros']['fecha_inicio'] }} 
                            @endif
                            @if(!empty($data['filtros']['fecha_fin']))
                            hasta {{ $data['filtros']['fecha_fin'] }}
                            @endif
                            @if(!empty($data['filtros']['docente_id']))
                            | Docente específico
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
                <div class="text-right">
                    <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        🖨️ Imprimir
                    </button>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $data['estadisticas']['total'] }}</div>
                <div class="text-sm text-gray-600">Total Registros</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $data['estadisticas']['entradas'] }}</div>
                <div class="text-sm text-gray-600">Entradas</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $data['estadisticas']['salidas'] }}</div>
                <div class="text-sm text-gray-600">Salidas</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $data['estadisticas']['docentes_unicos'] }}</div>
                <div class="text-sm text-gray-600">Docentes</div>
            </div>
        </div>

        <!-- Tabla de Asistencias -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grupo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($data['asistencias'] as $asistencia)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $asistencia->docente->nombre }}</div>
                                <div class="text-sm text-gray-500">{{ $asistencia->docente->ci }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $asistencia->horario->grupoMateria->materia->nombre_materia }}</div>
                                <div class="text-sm text-gray-500">{{ $asistencia->horario->grupoMateria->materia->sigla_materia }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $asistencia->horario->grupoMateria->grupo->nombre_grupo }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $asistencia->fecha->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($asistencia->hora_marcado)->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $asistencia->tipo == 'entrada' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $asistencia->tipo == 'entrada' ? '✅ Entrada' : '🚪 Salida' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $asistencia->direccion ?? 'No disponible' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No se encontraron registros de asistencia
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Resumen -->
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">📈 Resumen del Reporte</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p><strong>Período:</strong> 
                        @if(!empty($data['filtros']['fecha_inicio']))
                            {{ $data['filtros']['fecha_inicio'] }} al {{ $data['filtros']['fecha_fin'] ?? 'Actual' }}
                        @else
                            Todos los registros
                        @endif
                    </p>
                    <p><strong>Total de días:</strong> {{ $data['estadisticas']['dias_cubiertos'] }}</p>
                </div>
                <div>
                    <p><strong>Docentes únicos:</strong> {{ $data['estadisticas']['docentes_unicos'] }}</p>
                    <p><strong>Relación entradas/salidas:</strong> 
                        {{ $data['estadisticas']['entradas'] }}:{{ $data['estadisticas']['salidas'] }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>