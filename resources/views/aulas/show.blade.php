@extends('layouts.app')

@section('title', 'Detalles del Aula')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Detalles del Aula: {{ $aula->nro_aula }}</h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información básica -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Aula</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Número de Aula:</span>
                                <p class="text-lg font-semibold text-gray-900">{{ $aula->nro_aula }}</p>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Tipo:</span>
                                <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    {{ $aula->tipo === 'Teórica' ? 'bg-blue-100 text-blue-800' : 
                                       ($aula->tipo === 'Laboratorio' ? 'bg-purple-100 text-purple-800' : 
                                       'bg-green-100 text-green-800') }}">
                                    {{ $aula->tipo }}
                                </span>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Capacidad:</span>
                                <p class="text-lg font-semibold text-gray-900">{{ $aula->capacidad }} estudiantes</p>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Piso:</span>
                                <p class="text-lg font-semibold text-gray-900">Piso {{ $aula->piso }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información Adicional</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Fecha de Creación:</span>
                                <p class="text-sm text-gray-900">{{ $aula->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Última Actualización:</span>
                                <p class="text-sm text-gray-900">{{ $aula->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Estado:</span>
                                <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Disponible
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('aulas.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                    Volver a la lista
                </a>
                <a href="{{ route('aulas.edit', $aula) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Editar Aula
                </a>
            </div>
        </div>
    </div>
</div>
@endsection