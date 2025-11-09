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
                    
                    @if(!empty($data['filtros']['docente_id']) || !empty($data['filtros']['materia_id']))
                    <div class="mt-2">
                        <p class="text-sm text-gray-600">
                            Filtros aplicados:
                            @if(!empty($data['filtros']['docente_id']))
                            | Docente específico
                            @endif
                            @if(!empty($data['filtros']['materia_id']))
                            | Materia específica
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
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $data['total_horarios'] }}</div>
                <div class="text-sm text-gray-600">Total Horarios</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $data['estadisticas_dias']['Lunes'] }}</div>
                <div class="text-sm text-gray-600">Lunes</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $data['estadisticas_dias']['Martes'] }}</div>
                <div class="text-sm text-gray-600">Martes</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $data['estadisticas_dias']['Miércoles'] }}</div>
                <div class="text-sm text-gray-600">Miércoles</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $data['estadisticas_dias']['Jueves'] }}</div>
                <div class="text-sm text-gray-600">Jueves</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $data['estadisticas_dias']['Viernes'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Viernes</div>
            </div>
        </div>

        <!-- Tabla de Horarios -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grupo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Día</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aula</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($data['horarios'] as $horario)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $horario->grupoMateria->materia->nombre_materia }}</div>
                                <div class="text-sm text-gray-500">{{ $horario->grupoMateria->materia->sigla_materia }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $horario->grupoMateria->grupo->nombre_grupo }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $horario->grupoMateria->docente->nombre }}</div>
                                <div class="text-sm text-gray-500">{{ $horario->grupoMateria->docente->ci }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $horario->dia }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $horario->aula->nro_aula ?? 'No asignada' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No se encontraron horarios
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>