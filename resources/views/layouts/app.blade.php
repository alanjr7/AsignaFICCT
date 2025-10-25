<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Horarios - FICCT</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gradient-to-b from-blue-800 to-blue-900 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-300 ease-in-out z-10">
            <div class="flex items-center justify-center space-x-2 px-4 mb-8">
                <span class="text-2xl font-bold tracking-tight">FICCT</span>
            </div>
            <nav class="space-y-2">
                @if(auth()->user()->isAdmin())
                <a href="{{ route('dashboard') }}" class="flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-blue-700 hover:shadow-md group">
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-blue-700 hover:shadow-md group">
                    <span class="font-medium">Gestión de Usuarios</span>
                </a>
                <a href="{{ route('bitacora.index') }}" class="flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-blue-700 hover:shadow-md group">
                    <span class="font-medium">Bitácora</span>
                </a>
                <a href="{{ route('materias.index') }}" class="flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-blue-700 hover:shadow-md group">
                    <span class="font-medium">Gestión de Materias</span>
                </a>
                <a href="{{ route('grupos.index') }}" class="flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-blue-700 hover:shadow-md group">
                    <span class="font-medium">Gestión de Grupos</span>
                </a>
                <a href="{{ route('aulas.index') }}" class="flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-blue-700 hover:shadow-md group">
                    <span class="font-medium">Gestión de Aulas</span>
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-blue-700 hover:shadow-md group">
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('horario.index') }}" class="flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-blue-700 hover:shadow-md group">
                    <span class="font-medium">Mi Horario</span>
                </a>
                <a href="{{ route('asistencia.index') }}" class="flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-blue-700 hover:shadow-md group">
                    <span class="font-medium">Registrar Asistencia</span>
                </a>
                @endif
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex justify-between items-center px-6 py-4">
                    <div class="flex items-center">
                        <button class="md:hidden rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 p-1 mr-2">
                            <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6 text-gray-600">
                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 font-medium">{{ auth()->user()->nombre }}</span>
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">{{ auth()->user()->rol }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-blue-600 font-medium transition duration-150">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-6 shadow-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6 shadow-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        const sidebar = document.querySelector('.transform');
        const button = document.querySelector('button.md\\:hidden');
        
        if (button) {
            button.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    </script>
</body>
</html>