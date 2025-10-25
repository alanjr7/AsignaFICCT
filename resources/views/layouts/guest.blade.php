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
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }
            .login-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 16px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            .logo-container {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 50%;
                padding: 8px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800">
            <!-- Logo Section -->
            <div class="mb-8 transform hover:scale-105 transition-transform duration-300">
                <a href="/" class="block logo-container">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-16 h-16 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                </a>
                <div class="text-center mt-4">
                    <h1 class="text-3xl font-bold text-white drop-shadow-lg">{{ config('app.name', 'Laravel') }}</h1>
                    <p class="text-blue-100 mt-2 text-sm">Sistema de Gestión Académica</p>
                </div>
            </div>

            <!-- Login Card -->
            <div class="w-full sm:max-w-md mt-6 px-8 py-8 login-card transform transition-all duration-500 hover:shadow-2xl">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        @if(request()->routeIs('login'))
                            Iniciar Sesión
                        @elseif(request()->routeIs('register'))
                            Crear Cuenta
                        @elseif(request()->routeIs('password.request'))
                            Recuperar Contraseña
                        @else
                            Autenticación
                        @endif
                    </h2>
                    <p class="text-gray-600 text-sm">
                        @if(request()->routeIs('login'))
                            Accede a tu cuenta para continuar
                        @elseif(request()->routeIs('register'))
                            Únete a nuestra plataforma
                        @elseif(request()->routeIs('password.request'))
                            Te ayudaremos a recuperar el acceso
                        @endif
                    </p>
                </div>

                {{ $slot }}
                
                <!-- Additional Links -->
                <div class="mt-8 text-center">
                    @if(request()->routeIs('login'))
                        <p class="text-gray-600 text-sm">
                            ¿No tienes cuenta?
                            <a href="{{ route('register') }}" class="text-purple-600 hover:text-purple-800 font-medium transition-colors duration-200">
                                Regístrate aquí
                            </a>
                        </p>
                    @elseif(request()->routeIs('register'))
                        <p class="text-gray-600 text-sm">
                            ¿Ya tienes cuenta?
                            <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-800 font-medium transition-colors duration-200">
                                Inicia sesión
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-center">
                <p class="text-blue-100 text-sm">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Todos los derechos reservados.
                </p>
            </div>
        </div>

        <script>
            // Efecto de partículas de fondo (opcional)
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.querySelector('body > div');
                const particles = 15;
                
                for (let i = 0; i < particles; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'absolute w-2 h-2 bg-white rounded-full opacity-20 animate-pulse';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.top = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 5 + 's';
                    container.appendChild(particle);
                }
            });
        </script>
    </body>
</html>