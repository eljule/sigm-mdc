<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal de Soporte y Helpdesk - Municipalidad de Castilla</title>
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        castilla: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#008435',
                            700: '#006e2c',
                            800: '#005a24',
                            900: '#00461c',
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col font-sans antialiased text-slate-800">

    <!-- Header -->
    <header class="bg-castilla-600 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo-banner.png') }}" alt="Escudo Castilla" class="h-12 w-auto object-contain">
                <div class="h-8 w-[1px] bg-emerald-500/50 hidden sm:block"></div>
                <span class="text-white font-bold tracking-tight text-sm sm:text-base hidden sm:inline-block">Mesa de Ayuda y Soporte</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="text-emerald-100 hover:text-white text-xs sm:text-sm font-semibold transition-colors flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="hidden sm:inline">Inicio</span>
                </a>
                <div class="h-6 w-[1px] bg-emerald-500/50"></div>
                <div class="text-right hidden sm:block">
                    <span class="text-white text-xs sm:text-sm font-medium block">{{ $user->name }}</span>
                    <span class="text-emerald-200 text-[10px] sm:text-xs block">{{ $office->name ?? 'Sin Oficina' }}</span>
                </div>
                <div class="h-6 w-[1px] bg-emerald-500/50 hidden sm:block"></div>
                <!-- Botón de Cierre de Sesión -->
                <form method="POST" action="{{ route('helpdesk.portal.logout') }}" class="flex items-center">
                    @csrf
                    <button
                        type="submit"
                        title="Cerrar Sesión"
                        class="flex items-center gap-1.5 text-emerald-100 hover:text-white hover:bg-emerald-700/40 border border-emerald-500/40 hover:border-emerald-400/60 rounded-xl px-2.5 py-1.5 text-xs font-semibold transition-all duration-150 group"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-0.5 transition-transform duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline">Salir</span>
                    </button>
                </form>
            </div>
        </div>
    </header>


    <!-- Main Container -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        <!-- Title Banner -->
        <div class="mb-8 bg-gradient-to-r from-castilla-800 to-castilla-900 rounded-3xl p-6 md:p-8 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(0,166,60,0.15),transparent)] pointer-events-none"></div>
            <div class="relative z-10">
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mb-2 uppercase">Portal de Soporte y Helpdesk</h1>
                <p class="text-emerald-100 text-xs md:text-sm font-light max-w-2xl">
                    Reporta incidencias, fallas de hardware, problemas de conexión o solicita asistencia con los sistemas municipales de forma rápida y sencilla.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Form -->
            <div class="lg:col-span-5 space-y-6">
                <div class="glass-card rounded-3xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                        <div class="bg-castilla-50 p-2.5 rounded-2xl text-castilla-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Registrar Incidente</h2>
                            <p class="text-[11px] text-slate-400">Describe el inconveniente para que un técnico sea asignado.</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-sm">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('helpdesk.portal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <!-- Visual Category Quick Selector -->
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Selección Rápida de Categoría</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-2">
                                <button type="button" onclick="selectQuickCategory('Equipos/Hardware', this)" class="quick-cat-btn bg-slate-50 hover:bg-emerald-50 hover:border-castilla-600 border border-slate-200 rounded-xl p-2.5 text-center transition-all cursor-pointer group">
                                    <div class="text-xl mb-1 group-hover:scale-110 transition-transform">🖥️</div>
                                    <div class="text-[11px] font-bold text-slate-700 group-hover:text-castilla-600">Hardware</div>
                                </button>
                                <button type="button" onclick="selectQuickCategory('Sistemas/Programas', this)" class="quick-cat-btn bg-slate-50 hover:bg-emerald-50 hover:border-castilla-600 border border-slate-200 rounded-xl p-2.5 text-center transition-all cursor-pointer group">
                                    <div class="text-xl mb-1 group-hover:scale-110 transition-transform">💻</div>
                                    <div class="text-[11px] font-bold text-slate-700 group-hover:text-castilla-600">Sistemas</div>
                                </button>
                                <button type="button" onclick="selectQuickCategory('Accesos/Contraseñas', this)" class="quick-cat-btn bg-slate-50 hover:bg-emerald-50 hover:border-castilla-600 border border-slate-200 rounded-xl p-2.5 text-center transition-all cursor-pointer group">
                                    <div class="text-xl mb-1 group-hover:scale-110 transition-transform">🔑</div>
                                    <div class="text-[11px] font-bold text-slate-700 group-hover:text-castilla-600">Accesos</div>
                                </button>
                                <button type="button" onclick="selectQuickCategory('Red/Internet', this)" class="quick-cat-btn bg-slate-50 hover:bg-emerald-50 hover:border-castilla-600 border border-slate-200 rounded-xl p-2.5 text-center transition-all cursor-pointer group">
                                    <div class="text-xl mb-1 group-hover:scale-110 transition-transform">🌐</div>
                                    <div class="text-[11px] font-bold text-slate-700 group-hover:text-castilla-600">Red/Wifi</div>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="user_category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">¿Con qué tiene que ver tu problema? (Categoría)</label>
                            <select name="user_category" id="user_category" required class="w-full bg-slate-50 border border-slate-200 focus:border-castilla-600 focus:bg-white rounded-2xl px-4 py-3 text-sm transition-all outline-none">
                                <option value="" disabled selected>Selecciona la opción más adecuada</option>
                                <option value="Equipos/Hardware">Equipos / Hardware (Computadora, Impresora, Teclado, etc.)</option>
                                <option value="Sistemas/Programas">Sistemas / Programas (SIGM, Navegador, Office)</option>
                                <option value="Accesos/Contraseñas">Accesos / Contraseñas (Restablecer clave, crear cuenta)</option>
                                <option value="Red/Internet">Red / Internet (Sin conexión, Wifi lento, cable suelto)</option>
                                <option value="Otros">Otros problemas</option>
                            </select>
                        </div>

                        <div>
                            <label for="impact" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">¿Qué tanto afecta a tu trabajo? (Urgencia)</label>
                            <select name="impact" id="impact" required class="w-full bg-slate-50 border border-slate-200 focus:border-castilla-600 focus:bg-white rounded-2xl px-4 py-3 text-sm transition-all outline-none">
                                <option value="Individual" selected>Solo me pasa a mí (Trabajo parcial detenido)</option>
                                <option value="Grupal">Afecta a mi área / oficina (Varios usuarios afectados)</option>
                                <option value="Critico">Todo el departamento / oficina está parado (Operación crítica detenida)</option>
                            </select>
                        </div>

                        <div>
                            <label for="title" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5 font-bold">Resumen / Asunto</label>
                            <input type="text" name="title" id="title" required placeholder="Ej. No puedo imprimir mi documento" class="w-full bg-slate-50 border border-slate-200 focus:border-castilla-600 focus:bg-white rounded-2xl px-4 py-3 text-sm transition-all outline-none">
                        </div>

                        <div>
                            <label for="description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Detalle del Inconveniente (Síntoma)</label>
                            <textarea name="description" id="description" rows="4" required placeholder="Describe libremente qué intentabas hacer, qué pasó y si salió algún mensaje de error en pantalla." class="w-full bg-slate-50 border border-slate-200 focus:border-castilla-600 focus:bg-white rounded-2xl px-4 py-3 text-sm transition-all outline-none resize-none"></textarea>
                        </div>

                        <div>
                            <label for="attachments" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Adjuntar capturas de pantalla o fotos del error (Opcional)</label>
                            <input type="file" name="attachments[]" id="attachments" multiple accept="image/*,video/*,application/pdf" class="w-full bg-slate-50 border border-slate-200 focus:border-castilla-600 focus:bg-white rounded-2xl px-4 py-2.5 text-sm transition-all outline-none">
                        </div>

                        <button type="submit" class="w-full bg-castilla-600 hover:bg-castilla-700 text-white font-semibold py-3.5 rounded-2xl text-center text-sm shadow hover:shadow-md transition-all duration-150 flex items-center justify-center gap-2 mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span>Enviar Ticket a Soporte</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Tickets List -->
            <div class="lg:col-span-7 space-y-6">
                <div class="glass-card rounded-3xl p-6 shadow-md">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="bg-castilla-50 p-2.5 rounded-2xl text-castilla-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-800">Mis Tickets de Soporte</h2>
                                <p class="text-[11px] text-slate-400">Historial y estado en tiempo real de tus solicitudes.</p>
                            </div>
                        </div>
                        <span class="text-xs bg-slate-100 text-slate-600 px-3 py-1 rounded-full font-bold uppercase">{{ $tickets->count() }} Tickets</span>
                    </div>

                    @if($tickets->count() == 0)
                        <div class="text-center py-16 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2M4 13V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <p class="text-sm font-medium">Aún no has registrado tickets de soporte.</p>
                            <p class="text-xs text-slate-400 mt-1">Usa el formulario a la izquierda para reportar tu primer caso.</p>
                        </div>
                    @else
                        <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                            @foreach($tickets as $ticket)
                                <div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-sm hover:border-slate-300 transition-all duration-150">
                                    <div class="flex flex-wrap justify-between items-start gap-2 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-castilla-600 uppercase tracking-wider bg-castilla-50 border border-castilla-100 px-2 py-0.5 rounded-lg">{{ $ticket->ticket_code }}</span>
                                            <span class="text-[10px] text-slate-400">{{ $ticket->created_at->format('d/m/Y h:i A') }}</span>
                                        </div>
                                        <div>
                                            @php
                                                $statusColor = match($ticket->status) {
                                                    'Abierto' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'En Proceso' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                    'Resuelto' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'Cerrado' => 'bg-slate-100 text-slate-600 border-slate-200',
                                                    default => 'bg-slate-50 text-slate-600 border-slate-200'
                                                };
                                                
                                                $priorityColor = match($ticket->priority) {
                                                    'Baja' => 'bg-slate-50 text-slate-600 border-slate-200',
                                                    'Media' => 'bg-orange-50 text-orange-700 border-orange-200',
                                                    'Alta' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                    default => 'bg-slate-50 text-slate-600'
                                                };
                                            @endphp
                                            <span class="text-[9px] border px-2 py-0.5 rounded-full font-bold uppercase tracking-wider {{ $statusColor }}">{{ $ticket->status }}</span>
                                            <span class="text-[9px] border px-2 py-0.5 rounded-full font-bold uppercase tracking-wider {{ $priorityColor }}">{{ $ticket->priority }}</span>
                                        </div>
                                    </div>

                                    <h3 class="text-sm font-bold text-slate-800 mb-1 leading-snug">{{ $ticket->title }}</h3>
                                    <p class="text-xs text-slate-500 font-light leading-relaxed mb-3">{{ $ticket->description }}</p>

                                    @if(!empty($ticket->attachments))
                                        <div class="mb-3 text-[10px] flex flex-wrap gap-2 items-center bg-slate-50 p-2 rounded-lg">
                                            <span class="text-slate-400 font-bold uppercase">Adjuntos:</span>
                                            @foreach($ticket->attachments as $attachment)
                                                <a href="{{ Storage::url($attachment) }}" target="_blank" class="text-castilla-600 hover:text-castilla-700 hover:underline font-semibold flex items-center gap-1 bg-white px-2 py-1 border border-slate-200 rounded-md">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    {{ basename($attachment) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="flex flex-wrap items-center justify-between gap-2 pt-3 border-t border-slate-100 text-[10px] text-slate-400">
                                        <div class="flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Categoría Reportada: <strong>{{ $ticket->user_category ?? $ticket->category->name }}</strong></span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span>Asignado a: <strong>{{ $ticket->assignee->name ?? 'Pendiente' }}</strong></span>
                                        </div>
                                    </div>

                                    @if($ticket->status === 'Resuelto' && $ticket->solution_applied)
                                        <div class="mt-3 bg-emerald-50/50 border border-emerald-100/80 p-3 rounded-xl text-xs">
                                            <span class="font-bold text-emerald-800 block mb-0.5">Solución Técnica Aplicada:</span>
                                            <p class="text-slate-600 font-light">{{ $ticket->solution_applied }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-500 py-6 border-t border-slate-900 text-center text-xs mt-12">
        <div class="max-w-7xl mx-auto px-4">
            <p class="mb-1">
                Municipalidad Distrital de Castilla &copy; {{ date('Y') }} &bull; Todos los derechos reservados.
            </p>
            <p class="text-slate-600">
                Desarrollado y administrado por la Oficina de Desarrollo Tecnológico (ODT).
            </p>
        </div>
    </footer>

    <script>
        function selectQuickCategory(val, btn) {
            const sel = document.getElementById('user_category');
            if (sel) {
                sel.value = val;
            }
            document.querySelectorAll('.quick-cat-btn').forEach(b => {
                b.classList.remove('bg-emerald-100', 'border-castilla-600', 'shadow-sm');
                b.classList.add('bg-slate-50');
            });
            btn.classList.remove('bg-slate-50');
            btn.classList.add('bg-emerald-100', 'border-castilla-600', 'shadow-sm');
        }
    </script>
</body>
</html>
