<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal SIGM - Municipalidad de Castilla</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Static Tailwind CSS v2 -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
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
<body class="bg-slate-50 min-h-screen flex flex-col font-sans antialiased">

    <!-- Header Banner -->
    <header class="bg-castilla-600 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <!-- Left Side Logo -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo-banner.png') }}" alt="Escudo Castilla" class="h-12 w-auto object-contain">
            </div>

            <!-- Right Side Auth Links -->
            <div>
                @auth
                    <div class="flex items-center gap-4">
                        <span class="text-white text-sm font-medium hidden md:inline">Bienvenido, {{ auth()->user()->name }}</span>
                        <a href="{{ url('/admin') }}" class="bg-white hover:bg-slate-100 text-castilla-600 font-semibold px-4 py-2 rounded-lg text-sm shadow transition-all duration-150">
                            Ir al Panel Central
                        </a>
                    </div>
                @else
                    <a href="{{ url('/admin/login') }}" class="bg-white hover:bg-slate-100 text-castilla-600 font-semibold px-5 py-2 rounded-lg text-sm shadow transition-all duration-150 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Iniciar Sesión
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-castilla-800 to-castilla-900 py-16 md:py-24 text-white overflow-hidden shadow-inner">
        <!-- Background light design effect -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(0,166,60,0.15),transparent)] pointer-events-none"></div>

        <div class="max-w-5xl mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">
                SISTEMA INTEGRADO DE GESTIÓN MUNICIPAL
            </h1>
            <p class="text-lg md:text-xl text-emerald-100 max-w-3xl mx-auto font-light leading-relaxed">
                Ecosistema modular unificado para automatizar y optimizar los procesos de las distintas dependencias de la Municipalidad Distrital de Castilla.
            </p>
        </div>
    </section>

    <!-- Subsystems Cards Section -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-10 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($subsystems as $subsystem)
                @php
                    // Mapear el código del subsistema a su imagen correspondiente
                    $imagePath = match($subsystem->code) {
                        'central' => asset('images/subsystem_central.png'),
                        'rentas' => asset('images/subsystem_rentas.png'),
                        'itam' => asset('images/subsystem_itam.png'),
                        'helpdesk' => asset('images/subsystem_helpdesk.png'),
                        default => asset('images/subsystem_central.png')
                    };
                @endphp
                <div class="glass-card rounded-2xl shadow-lg hover:shadow-2xl overflow-hidden transition-all duration-300 transform hover:-translate-y-2 flex flex-col group">
                    <!-- Image Area -->
                    <div class="relative overflow-hidden aspect-video bg-emerald-50 border-b border-slate-100">
                        <img src="{{ $imagePath }}" alt="{{ $subsystem->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>

                    <!-- Content Area -->
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2 leading-tight group-hover:text-castilla-600 transition-colors duration-150">
                                {{ $subsystem->name }}
                            </h3>
                            <p class="text-slate-600 text-sm leading-relaxed mb-6 font-light">
                                {{ $subsystem->description }}
                            </p>
                        </div>

                        <!-- Button -->
                        <a href="{{ url($subsystem->url_path) }}" class="w-full bg-castilla-600 hover:bg-castilla-700 text-white font-semibold py-2.5 px-4 rounded-xl text-center text-sm shadow hover:shadow-md transition-all duration-150 flex items-center justify-center gap-2">
                            <span>Ingresar al Sistema</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-8 border-t border-slate-800 text-center text-xs">
        <div class="max-w-7xl mx-auto px-4">
            <p class="mb-2">
                Municipalidad Distrital de Castilla &copy; {{ date('Y') }} &bull; Todos los derechos reservados.
            </p>
            <p class="text-slate-500">
                Desarrollado y administrado por la Oficina de Desarrollo Tecnológico (ODT).
            </p>
        </div>
    </footer>

</body>
</html>
