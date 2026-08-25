<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal SIGM - Municipalidad de Castilla</title>
    <!-- Anti-FOUC script for Dark Mode -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Static Tailwind CSS v2 -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Reglas CSS para Modo Oscuro en Tailwind CDN */
        html.dark body {
            background-color: #0f172a !important; /* slate-900 */
            color: #f8fafc !important;
        }

        html.dark header {
            background-color: #020617 !important; /* slate-950 */
            border-color: #1e293b !important;
        }

        html.dark section {
            background-image: linear-gradient(to bottom right, #0f172a, #020617) !important;
        }

        html.dark .glass-card {
            background-color: rgba(30, 41, 59, 0.95) !important; /* slate-800 */
            border-color: rgba(51, 65, 85, 0.8) !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
        }

        html.dark .glass-card h3 {
            color: #ffffff !important;
        }

        html.dark .glass-card p {
            color: #cbd5e1 !important; /* slate-300 */
        }

        html.dark .card-graphic-bg {
            background-color: rgba(15, 23, 42, 0.9) !important;
            border-color: rgba(51, 65, 85, 0.6) !important;
        }

        html.dark footer {
            background-color: #020617 !important;
            border-color: #1e293b !important;
            color: #94a3b8 !important;
        }

        html.dark .bg-secondary-btn {
            background-color: #1e293b !important;
            color: #34d399 !important;
            border-color: #10b981 !important;
        }

        html.dark .bg-secondary-btn:hover {
            background-color: #334155 !important;
        }

        .bg-castilla-600 {
            background-color: #008435 !important;
        }

        .hover\:bg-castilla-700:hover {
            background-color: #006e2c !important;
        }

        .text-castilla-600 {
            color: #008435 !important;
        }

        .from-castilla-800 {
            --tw-gradient-from: #005a24 !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(0, 90, 36, 0)) !important;
        }

        .to-castilla-900 {
            --tw-gradient-to: #00461c !important;
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 h-full flex flex-col justify-between font-sans antialiased overflow-x-hidden">

    <!-- Header Bar -->
    <header class="bg-castilla-600 shadow-sm flex-shrink-0 border-b border-castilla-700 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo-banner.png') }}" alt="Escudo Castilla"
                    class="h-11 sm:h-12 md:h-14 w-auto object-contain transition-all">
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Dark / Light Mode Toggle Button -->
                <button onclick="toggleDarkMode()" type="button"
                    title="Alternar Modo Oscuro / Claro"
                    class="p-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-amber-300 transition-all shadow-sm flex items-center justify-center cursor-pointer">
                    <!-- Sun Icon (visible in dark mode) -->
                    <svg id="theme-sun-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon Icon (visible in light mode) -->
                    <svg id="theme-moon-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                @auth
                    <span class="text-white text-xs sm:text-sm font-medium hidden sm:inline">Bienvenido,
                        {{ auth()->user()->name }}</span>
                    <a href="{{ url('/admin') }}"
                        class="bg-white hover:bg-slate-100 text-castilla-600 font-semibold px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm shadow transition-all border border-transparent">
                        Ir al Panel Central
                    </a>
                    <form method="POST" action="{{ route('helpdesk.portal.logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm shadow transition-all flex items-center gap-1.5 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="hidden sm:inline">Cerrar Sesión</span>
                        </button>
                    </form>
                @else
                    <a href="{{ url('/login') }}"
                        class="bg-white hover:bg-slate-100 text-castilla-600 font-semibold px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm shadow transition-all flex items-center gap-1.5 border border-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Iniciar Sesión
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Error Notification Banner if redirected -->
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-3 flex-shrink-0">
            <div class="bg-red-500/10 border border-red-500/30 text-red-600 dark:text-red-400 text-xs sm:text-sm font-semibold p-3 rounded-xl text-center shadow-sm">
                ⚠️ {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Hero Title & Subtitle Section -->
    <section
        class="bg-gradient-to-br from-castilla-800 to-castilla-900 py-4 sm:py-5 text-white text-center shadow-md flex-shrink-0 transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-tight mb-1.5">
                SISTEMA INTEGRADO DE GESTIÓN MUNICIPAL
            </h1>
            <p class="text-xs sm:text-sm md:text-base text-emerald-100 font-light max-w-2xl mx-auto">
                Selecciona el subsistema autorizado para iniciar sesión o solicitar asistencia técnica.
            </p>
        </div>
    </section>

    <!-- Subsystems Cards Section -->
    <main class="flex-grow flex items-center justify-center max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 w-full">
        @php
            $count = $subsystems->count();
            $gridCols = match ($count) {
                1 => 'grid-cols-1 max-w-sm mx-auto',
                2 => 'grid-cols-1 md:grid-cols-2 max-w-2xl mx-auto',
                3 => 'grid-cols-1 md:grid-cols-3 max-w-4xl mx-auto',
                default => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4 max-w-7xl mx-auto',
            };
        @endphp

        <div class="grid {{ $gridCols }} gap-5 w-full items-stretch">
            @foreach($subsystems as $subsystem)
                @php
                    $imagePath = match ($subsystem->code) {
                        'central' => asset('images/subsystem_central.png'),
                        'rentas' => asset('images/subsystem_rentas.png'),
                        'itam' => asset('images/subsystem_itam.png'),
                        'helpdesk' => asset('images/subsystem_helpdesk.png'),
                        default => asset('images/subsystem_central.png')
                    };

                    $user = auth()->user();
                    $canAccessHelpdeskPanel = false;

                    if ($user) {
                        if ($user->hasRole('Administrador Central')) {
                            $canAccessHelpdeskPanel = true;
                        } else {
                            $userRolesInSubsystem = \Illuminate\Support\Facades\DB::table('model_has_roles')
                                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                                ->where('model_has_roles.model_id', $user->id)
                                ->where('model_has_roles.subsystem_id', $subsystem->id)
                                ->pluck('roles.name')
                                ->toArray();

                            $operativeRoles = array_diff($userRolesInSubsystem, ['Usuario Reportante']);
                            $canAccessHelpdeskPanel = !empty($operativeRoles);
                        }
                    }
                @endphp
                <div
                    class="glass-card rounded-xl shadow-md hover:shadow-xl overflow-hidden transition-all duration-200 transform hover:-translate-y-1 flex flex-col justify-between group border border-slate-200">
                    <div>
                        <!-- Image Area (Full Uncropped Graphic) -->
                        <div
                            class="card-graphic-bg relative overflow-hidden h-32 sm:h-36 md:h-40 bg-emerald-50/60 border-b border-slate-100 p-2">
                            <img src="{{ $imagePath }}" alt="{{ $subsystem->name }}"
                                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                        </div>

                        <!-- Content Area -->
                        <div class="p-4 sm:p-5">
                            <h3
                                class="text-sm sm:text-base md:text-lg font-bold text-slate-800 mb-1 leading-tight group-hover:text-castilla-600 transition-colors">
                                {{ $subsystem->name }}
                            </h3>
                            <p class="text-slate-600 text-xs sm:text-sm leading-normal font-light">
                                {{ $subsystem->description }}
                            </p>
                        </div>
                    </div>

                    <!-- Button Actions Area -->
                    <div class="p-4 sm:p-5 pt-0 space-y-2 w-full">
                        @if($subsystem->code === 'helpdesk')
                            @if(!$user || $canAccessHelpdeskPanel)
                                <a href="{{ url($subsystem->url_path) }}"
                                    class="w-full bg-castilla-600 hover:bg-castilla-700 text-white font-semibold py-2 sm:py-2.5 px-3 sm:px-4 rounded-lg text-center text-xs sm:text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                                    <span>Ingresar al Panel</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            @endif

                            <a href="{{ url('/soporte') }}"
                                class="w-full bg-secondary-btn {{ (!$user || $canAccessHelpdeskPanel) ? 'bg-white hover:bg-emerald-50 border border-castilla-600 text-castilla-600' : 'bg-castilla-600 hover:bg-castilla-700 text-white' }} font-semibold py-2 sm:py-2.5 px-3 sm:px-4 rounded-lg text-center text-xs sm:text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                                <span>Generar Ticket de Soporte</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </a>
                        @else
                            <a href="{{ url($subsystem->url_path) }}"
                                class="w-full bg-castilla-600 hover:bg-castilla-700 text-white font-semibold py-2 sm:py-2.5 px-3 sm:px-4 rounded-lg text-center text-xs sm:text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                                <span>Ingresar al Sistema</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 py-3 text-center text-xs sm:text-sm flex-shrink-0 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4">
            Municipalidad Distrital de Castilla &copy; {{ date('Y') }} &bull; Todos los derechos reservados. &bull;
            <span class="text-slate-500">Desarrollado por ODT</span>
        </div>
    </footer>

    <!-- Theme Switcher JS -->
    <script>
        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            const sunIcon = document.getElementById('theme-sun-icon');
            const moonIcon = document.getElementById('theme-moon-icon');
            if (isDark) {
                if (sunIcon) sunIcon.classList.remove('hidden');
                if (moonIcon) moonIcon.classList.add('hidden');
            } else {
                if (sunIcon) sunIcon.classList.add('hidden');
                if (moonIcon) moonIcon.classList.remove('hidden');
            }
        }

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
            updateThemeIcons();
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcons);
    </script>
</body>

</html>
