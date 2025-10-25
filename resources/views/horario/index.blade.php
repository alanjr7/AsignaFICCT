@extends('layouts.app')

@section('title', 'Mi Horario')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Mi Horario</h2>
        <p class="text-gray-600">Horario asignado para {{ auth()->user()->nombre }}</p>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Horario Semanal</h3>
            <p class="text-sm text-gray-600">Período: {{ now()->format('F Y') }}</p>
        </div>
        
        <div class="p-6">
            <!-- Placeholder para horario -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h4 class="text-lg font-medium text-gray-700 mb-2">Horario no asignado</h4>
                <p class="text-gray-500 max-w-md mx-auto">
                    Tu horario aún no ha sido asignado. Por favor, contacta con la administración 
                    para obtener tu horario de clases.
                </p>
            </div>

            <!-- Información del docente -->
            <div class="mt-8 border-t border-gray-200 pt-6">
                <h4 class="text-lg font-medium text-gray-700 mb-4">Información del Docente</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nombre:</p>
                        <p class="font-medium">{{ auth()->user()->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">CI:</p>
                        <p class="font-medium">{{ auth()->user()->ci }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email:</p>
                        <p class="font-medium">{{ auth()->user()->correo }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Rol:</p>
                        <p class="font-medium capitalize">{{ auth()->user()->rol }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection