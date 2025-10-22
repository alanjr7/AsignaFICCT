@extends('layouts.app')

@section('title', 'Registro de Asistencia')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Registro de Asistencia</h2>
        <p class="text-gray-600">Bienvenido, {{ auth()->user()->nombre }}</p>
    </div>

    <!-- Card de registro de asistencia -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Registrar Asistencia del Día</h3>
            <p class="text-sm text-gray-600">{{ now()->format('d/m/Y') }}</p>
        </div>
        
        <div class="p-6">
            @if($asistenciasHoy->count() > 0)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-green-800 font-medium">¡Asistencia ya registrada!</p>
                            <p class="text-green-600 text-sm">
                                Registrada a las {{ $asistenciasHoy->first()->hora }} via {{ $asistenciasHoy->first()->metodo }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <p class="text-gray-600 mb-4">Selecciona el método para registrar tu asistencia:</p>
                    
                    <div class="flex justify-center space-x-4">
                        <form action="{{ route('asistencia.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="metodo" value="formulario">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Registrar por Formulario
                            </button>
                        </form>

                        <button type="button" 
                                class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center opacity-50 cursor-not-allowed"
                                title="Próximamente">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            Registrar por QR
                        </button>
                    </div>
                    
                    <p class="text-sm text-gray-500 mt-4">
                        * El registro por QR estará disponible próximamente
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Historial reciente -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Historial de Asistencias (Últimos 7 días)</h3>
        </div>
        
        <div class="p-6">
            @php
                $ultimasAsistencias = \App\Models\Asistencia::where('user_id', auth()->id())
                    ->where('fecha', '>=', now()->subDays(7))
                    ->orderBy('fecha', 'desc')
                    ->get();
            @endphp

            @if($ultimasAsistencias->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($ultimasAsistencias as $asistencia)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $asistencia->fecha->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $asistencia->hora }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="capitalize">{{ $asistencia->metodo }}</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $asistencia->estado === 'presente' ? 'bg-green-100 text-green-800' : 
                                           ($asistencia->estado === 'ausente' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $asistencia->estado }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-500">No hay registros de asistencia en los últimos 7 días.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection