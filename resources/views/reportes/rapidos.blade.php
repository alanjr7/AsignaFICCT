@extends('layouts.app')

@section('title', 'Reporte Rápido')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">🚀 {{ $reportesRapidos[$tipo]['titulo'] }}</h1>
            
            <form action="{{ route('reportes.rapido.descargar', $tipo) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Formato</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($formatos as $formato)
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                            <input type="radio" name="formato" value="{{ $formato }}" 
                                class="mr-3 text-blue-500 focus:ring-blue-500" required>
                            <div>
                                <span class="font-medium text-gray-800">
                                    @if($formato === 'excel') 📊 CSV/Excel
                                    @elseif($formato === 'pdf') 📄 PDF
                                    @elseif($formato === 'html') 🌐 HTML
                                    @endif
                                </span>
                                <p class="text-sm text-gray-600 mt-1">
                                    @if($formato === 'excel') Formato CSV (compatible con Excel)
                                    @elseif($formato === 'pdf') Formato imprimible
                                    @elseif($formato === 'html') Vista en navegador
                                    @endif
                                </p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('reportes.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                        ← Volver
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 flex items-center">
                        📥 Descargar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection