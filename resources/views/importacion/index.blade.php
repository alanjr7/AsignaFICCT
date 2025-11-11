@extends('layouts.app')

@section('title', 'Importar Docentes y Horarios - CSV')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Importar Docentes y Horarios</h1>
            <p class="text-gray-600">Suba un archivo CSV con la información de docentes, grupos, materias y horarios.</p>
        </div>

        <!-- Alertas -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif

        @if(session('resultado'))
            @php $resultado = session('resultado'); @endphp
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Resultado de la Importación</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $resultado['importados'] }}</div>
                        <div class="text-green-700">Registros Importados</div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $resultado['omitidos'] }}</div>
                        <div class="text-yellow-700">Registros Omitidos</div>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-red-600">{{ count($resultado['errores']) }}</div>
                        <div class="text-red-700">Errores Encontrados</div>
                    </div>
                </div>

                @if(count($resultado['errores']) > 0)
                    <div class="mt-4">
                        <h4 class="font-semibold text-gray-800 mb-2">Detalle de Errores:</h4>
                        <div class="bg-gray-50 rounded-lg p-4 max-h-60 overflow-y-auto">
                            @foreach($resultado['errores'] as $error)
                                <div class="text-red-600 text-sm py-1 border-b border-gray-200 last:border-b-0">
                                    {{ $error }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Formulario de Importación -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form action="{{ route('importacion.importar') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                
                <div class="mb-6">
                    <label for="archivo" class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Archivo CSV
                    </label>
                    <input type="file" name="archivo" id="archivo" 
                           accept=".csv,.txt"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Formatos aceptados: CSV, TXT (Máximo 10MB)</p>
                    @error('archivo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                            id="submitBtn">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                        Importar Datos
                    </button>

                    <a href="{{ route('importacion.descargar-plantilla') }}" 
                       class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Descargar Plantilla
                    </a>

                    <a href="{{ route('importacion.ver-ejemplo') }}" 
                       class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Ver Ejemplo
                    </a>
                </div>
            </form>
        </div>

        <!-- Instrucciones -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">Instrucciones de Uso</h3>
            <ul class="list-disc list-inside space-y-2 text-blue-700">
                <li><strong>Descargue la plantilla</strong> para ver el formato requerido</li>
                <li><strong>Mantenga los encabezados de columna</strong> exactamente como en la plantilla</li>
                <li><strong>Los días deben ser:</strong> Lunes, Martes, Miércoles, Jueves, Viernes o Sábado</li>
                <li><strong>Las horas deben estar en formato 24h</strong> (ej: 08:30, 14:15)</li>
                <li><strong>La hora fin debe ser posterior</strong> a la hora inicio</li>
                <li><strong>El sistema detectará automáticamente</strong> conflictos de horarios</li>
                <li><strong>Los usuarios duplicados por CI</strong> no se crearán nuevamente</li>
            </ul>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Procesando Archivo</h3>
            <p class="text-sm text-gray-500 mt-1">Por favor espere mientras se importan los datos...</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('importForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingModal = document.getElementById('loadingModal');

    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Procesando...';
        loadingModal.classList.remove('hidden');
    });
});
</script>
@endsection