<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Horarios - FICCT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-blue-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <div class="text-white flex items-center space-x-2 px-4">
                <span class="text-2xl font-extrabold">FICCT</span>
            </div>
            <nav>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    Dashboard
                </a>
                <a href="{{ route('users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    Gestión de Usuarios
                </a>
                <a href="{{ route('bitacora.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    Bitácora
                </a>
                <a href="{{ route('materias.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    Gestión de Materias
                </a>
                <a href="{{ route('grupos.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    Gestión de Grupos
                </a>
                <a href="{{ route('aulas.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    Gestión de Aulas
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    Dashboard
                </a>
                <a href="{{ route('horario.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    Mi Horario
                </a>
                <a href="{{ route('asistencia.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                    Registrar Asistencia
                </a>
                @endif
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="flex justify-between items-center px-6 py-4">
                    <div class="flex items-center">
                        <button class="md:hidden rounded-lg focus:outline-none focus:shadow-outline">
                            <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-900 ml-2">@yield('title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ auth()->user()->nombre }}</span>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ auth()->user()->rol }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-blue-600">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
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