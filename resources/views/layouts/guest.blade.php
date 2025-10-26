<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <style>
            body {
                font-family: 'Figtree', sans-serif;
                background: #f8fafc;
            }
            .institutional-border {
                border-left: 4px solid #1e40af;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Left Panel - Institutional Info -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-900 to-blue-800 text-white">
                <div class="w-full flex flex-col justify-center items-center p-12">
                    <div class="max-w-md">
                        <!-- Institutional Logo -->
                        <div class="mb-8 text-center">
                            <div class="mb-6">
                                <img src="{{ asset('images/logo.png') }}" alt="FICCT Logo" class="w-32 h-32 mx-auto object-contain">
                            </div>
                            <h1 class="text-3xl font-bold mb-2">FICCT</h1>
                            <p class="text-blue-200 text-lg">Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones</p>
                        </div>

                        <!-- Institutional Features -->
                        <div class="space-y-4 mt-8">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="text-blue-100">Sistema seguro de autenticación</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <span class="text-blue-100">Acceso restringido al personal autorizado</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="text-blue-100">Gestión académica integral</span>
                            </div>
                        </div>

                        <!-- Security Notice -->
                        <div class="mt-12 p-4 bg-blue-800/50 rounded-lg border border-blue-700">
                            <p class="text-sm text-blue-200 text-center">
                                🔒 Este es un sistema seguro. Proteja sus credenciales de acceso.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Login Form -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden mb-8 text-center">
                        <div class="mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="FICCT Logo" class="w-24 h-24 mx-auto object-contain">
                        </div>
                        <h1 class="text-2xl font-bold text-gray-800 mb-1">FICCT</h1>
                        <p class="text-gray-600 text-sm">Sistema de Gestión Académica</p>
                    </div>

                    <!-- Login Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 institutional-border">
                        <div class="p-8">
                            <!-- Header -->
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                                    @if(request()->routeIs('login'))
                                        Iniciar Sesión
                                    @endif
                                </h2>
                                <p class="text-gray-600 text-sm">
                                    @if(request()->routeIs('login'))
                                        Ingrese sus credenciales para acceder al sistema
                                    @endif
                                </p>
                            </div>

                            <!-- Form Content -->
                            {{ $slot }}

                            <!-- Additional Links -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                @if(request()->routeIs('login'))
                                    <div class="text-center space-y-3">
                                        @if (Route::has('register'))
                                       
                                        @endif
                                    </div>
                                @elseif(request()->routeIs('register'))
                                    <p class="text-gray-600 text-sm text-center">
                                        ¿Ya tiene una cuenta?
                                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                                            Iniciar sesión
                                        </a>
                                    </p>
                                @elseif(request()->routeIs('password.request'))
                                    <p class="text-gray-600 text-sm text-center">
                                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                                            ← Volver al inicio de sesión
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 text-center">
                        <p class="text-gray-500 text-xs">
                            &copy; {{ date('Y') }} FICCT - Universidad Autónoma Gabriel René Moreno.<br>
                            Sistema de Gestión de Horarios Académicos. v1.0
                        </p>
                        <p class="text-gray-400 text-xs mt-2">
                            Para asistencia técnica contacte al departamento de sistemas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>