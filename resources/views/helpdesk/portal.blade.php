<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal de Soporte y Mesa de Ayuda - Municipalidad Distrital de Castilla</title>
    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                        mono: ['Courier New', 'Courier', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(226, 232, 240, 0.9);
        }
        /* Custom scrollbar for dropdown */
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 8px;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col font-sans antialiased text-slate-800">

    <!-- Header -->
    <header class="bg-castilla-600 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-castilla.png') }}" alt="Escudo Castilla" class="h-10 sm:h-12 w-auto object-contain bg-white rounded-lg p-1">
                <div class="h-8 w-[1px] bg-emerald-500/50 hidden sm:block"></div>
                <div>
                    <span class="text-white font-bold tracking-tight text-sm sm:text-base block leading-tight">Mesa de Ayuda y Soporte TI</span>
                    <span class="text-emerald-100 text-[10px] sm:text-xs block">Municipalidad Distrital de Castilla</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="text-emerald-100 hover:text-white text-xs sm:text-sm font-semibold transition-colors flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="hidden sm:inline">Inicio</span>
                </a>

                @if($user)
                    <div class="h-6 w-[1px] bg-emerald-500/50"></div>
                    <div class="text-right hidden sm:block">
                        <span class="text-white text-xs sm:text-sm font-medium block uppercase">{{ $user->name }}</span>
                        <span class="text-emerald-200 text-[10px] sm:text-xs block uppercase">{{ $office->name ?? 'Sin Oficina Asignada' }}</span>
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
                @else
                    <div class="h-6 w-[1px] bg-emerald-500/50"></div>
                    <!-- Acceso para personal técnico / administradores -->
                    <a
                        href="{{ url('/helpdesk/login') }}"
                        class="flex items-center gap-1.5 text-emerald-100 hover:text-white hover:bg-emerald-700/40 border border-emerald-500/40 hover:border-emerald-400/60 rounded-xl px-3 py-1.5 text-xs font-semibold transition-all duration-150"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span>Acceso Personal Técnico</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        <!-- Title Banner -->
        <div class="mb-8 bg-gradient-to-r from-castilla-800 to-castilla-900 rounded-3xl p-6 md:p-8 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(0,166,60,0.15),transparent)] pointer-events-none"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 bg-emerald-700/60 border border-emerald-500/40 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Atención de Incidencias y Requerimientos Informáticos
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mb-2 uppercase">Portal de Soporte y Helpdesk</h1>
                <p class="text-emerald-100 text-xs md:text-sm font-light max-w-2xl leading-relaxed">
                    Registra tu incidencia seleccionando tu <strong>Dependencia u Oficina</strong>. El sistema cargará automáticamente los datos del responsable asignado para una rápida atención de nuestro equipo técnico.
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
                            <h2 class="text-lg font-bold text-slate-800">Registrar Incidencia Técnica</h2>
                            <p class="text-[11px] text-slate-400">Selecciona tu oficina y describe el inconveniente para asignar un técnico.</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3.5 rounded-2xl text-xs sm:text-sm flex items-start gap-2.5 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <span class="font-bold block text-emerald-800">¡Registro Exitoso!</span>
                                <span class="leading-relaxed">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-xs sm:text-sm">
                            <span class="font-bold block mb-1">Por favor verifica los siguientes campos:</span>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="ticket_form" action="{{ route('helpdesk.portal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="return validateTicketForm()">
                        @csrf

                        <!-- 1. Dependencia / Oficina Requirente con BUSCADOR INTEGRADO -->
                        <div class="relative" id="office_select_wrapper">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                <span>1. Dependencia u Oficina Requirente <span class="text-rose-500">*</span></span>
                                <span class="text-[10px] text-castilla-600 font-medium normal-case flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Carga automática del responsable
                                </span>
                            </label>

                            <!-- Input oculto para envío y validación del formulario -->
                            <input type="hidden" name="office_id" id="office_id" value="{{ old('office_id', $office?->id) }}">

                            <!-- Botón disparador del Select Buscador -->
                            <button
                                type="button"
                                id="office_trigger_btn"
                                onclick="toggleOfficeDropdown()"
                                class="w-full bg-slate-50 border border-slate-300 hover:border-castilla-600 focus:border-castilla-600 focus:bg-white rounded-2xl px-4 py-3 text-xs sm:text-sm transition-all outline-none flex items-center justify-between text-left shadow-sm cursor-pointer group"
                                aria-haspopup="listbox"
                                aria-expanded="false"
                            >
                                <div class="flex items-center gap-2.5 truncate">
                                    <span class="text-base text-slate-400 group-hover:text-castilla-600 transition-colors">🏛️</span>
                                    <span id="office_trigger_text" class="font-bold text-slate-700 uppercase truncate">
                                        -- SELECCIONA O BUSCA TU DEPENDENCIA / OFICINA --
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5 ml-2 flex-shrink-0">
                                    <svg id="office_chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 group-hover:text-slate-600 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>

                            <p id="office_error_msg" class="text-[11px] text-rose-600 font-semibold mt-1 hidden">
                                ⚠ Debes seleccionar tu Dependencia u Oficina para continuar.
                            </p>

                            <!-- Panel Desplegable con BUSCADOR en tiempo real -->
                            <div
                                id="office_dropdown_menu"
                                class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 overflow-hidden transition-all duration-150"
                            >
                                <!-- Buscador interno -->
                                <div class="p-2.5 bg-slate-50 border-b border-slate-200 sticky top-0 z-10">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            id="office_search_input"
                                            oninput="filterOfficeOptions(this.value)"
                                            placeholder="Escribe para buscar oficina o responsable..."
                                            class="w-full pl-9 pr-8 py-2 bg-white border border-slate-300 focus:border-castilla-600 focus:ring-1 focus:ring-castilla-600 rounded-xl text-xs sm:text-sm font-medium outline-none transition-all uppercase placeholder:normal-case placeholder:text-slate-400"
                                            autocomplete="off"
                                        >
                                        <button
                                            type="button"
                                            onclick="clearOfficeSearch()"
                                            id="office_search_clear"
                                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 hidden cursor-pointer"
                                            title="Limpiar búsqueda"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1 px-1 font-medium">
                                        <span id="office_count_label">{{ count($offices) }} dependencias disponibles</span>
                                        <span class="text-slate-400">Presiona Enter para elegir</span>
                                    </div>
                                </div>

                                <!-- Lista de Dependencias filtrables -->
                                <div id="office_options_list" class="max-h-64 sm:max-h-72 overflow-y-auto custom-scroll divide-y divide-slate-100">
                                    @foreach($offices as $o)
                                        @php
                                            $resp = $officeResponsibles[$o->id] ?? null;
                                            $hasResp = !empty($resp['has_responsible']);
                                        @endphp
                                        <button
                                            type="button"
                                            class="office-option w-full text-left px-4 py-2.5 hover:bg-emerald-50/80 transition-colors flex items-center justify-between cursor-pointer group"
                                            data-id="{{ $o->id }}"
                                            data-name="{{ mb_strtoupper($o->name, 'UTF-8') }}"
                                            data-resp="{{ $hasResp ? mb_strtoupper($resp['name'], 'UTF-8') : '' }}"
                                            onclick="selectOfficeOption('{{ $o->id }}', '{{ addslashes(mb_strtoupper($o->name, 'UTF-8')) }}')"
                                        >
                                            <div class="pr-2">
                                                <div class="text-xs sm:text-sm font-bold text-slate-700 group-hover:text-castilla-700 uppercase leading-snug">
                                                    {{ mb_strtoupper($o->name, 'UTF-8') }}
                                                </div>
                                                @if($hasResp)
                                                    <div class="text-[10px] text-emerald-700 font-semibold flex items-center gap-1 mt-0.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        <span>Responsable: {{ $resp['name'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="office-check-icon hidden text-castilla-600 flex-shrink-0" data-for-id="{{ $o->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                        </button>
                                    @endforeach

                                    <!-- Mensaje cuando no hay resultados de búsqueda -->
                                    <div id="office_no_results" class="p-6 text-center text-slate-400 hidden">
                                        <div class="text-2xl mb-1">🔍</div>
                                        <p class="text-xs font-bold text-slate-600">No se encontraron dependencias</p>
                                        <p class="text-[11px] mt-0.5">Intenta buscar con otra palabra clave.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Tarjeta de Datos del Responsable de Oficina (Carga Automática) -->
                        <div id="responsible_card" class="bg-emerald-50/70 border border-emerald-200 rounded-2xl p-4 transition-all duration-200">
                            <input type="hidden" name="responsible_user_id" id="responsible_user_id" value="">
                            
                            <div class="flex items-center justify-between mb-2.5">
                                <span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    2. Responsable de la Oficina
                                </span>
                                <span id="auto_loaded_badge" class="text-[9px] uppercase tracking-wider bg-emerald-600 text-white font-bold px-2 py-0.5 rounded-full hidden">
                                    Cargado Automáticamente
                                </span>
                            </div>

                            <div class="space-y-2.5">
                                <div>
                                    <label for="requester_name" class="block text-[10px] font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                        Nombre y Apellidos del Responsable / Solicitante <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="requester_name"
                                        id="requester_name"
                                        required
                                        value="{{ old('requester_name', $user?->name) }}"
                                        placeholder="Seleccione la dependencia para cargar al responsable"
                                        class="w-full bg-white border border-emerald-200 focus:border-castilla-600 rounded-xl px-3.5 py-2 text-xs font-bold text-slate-800 uppercase outline-none transition-all"
                                    >
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    <div>
                                        <label for="responsible_position" class="block text-[10px] font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                            Cargo / Función
                                        </label>
                                        <input
                                            type="text"
                                            id="responsible_position"
                                            readonly
                                            placeholder="Cargo del responsable"
                                            class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-[11px] font-medium text-slate-600 uppercase outline-none cursor-default"
                                        >
                                    </div>
                                    <div>
                                        <label for="contact_phone" class="block text-[10px] font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                            Teléfono / Anexo de Contacto
                                        </label>
                                        <input
                                            type="text"
                                            name="contact_phone"
                                            id="contact_phone"
                                            value="{{ old('contact_phone') }}"
                                            placeholder="Ej. Anexo 104 / 987654321"
                                            class="w-full bg-white border border-emerald-200 focus:border-castilla-600 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 outline-none transition-all"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Selección Visual Rápida de Categoría -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">3. Selección Rápida de Categoría</label>
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
                            <label for="user_category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">¿Con qué tiene que ver tu problema? (Categoría) <span class="text-rose-500">*</span></label>
                            <select name="user_category" id="user_category" required class="w-full bg-slate-50 border border-slate-200 focus:border-castilla-600 focus:bg-white rounded-2xl px-4 py-3 text-xs sm:text-sm transition-all outline-none">
                                <option value="" disabled selected>Selecciona la opción más adecuada</option>
                                <option value="Equipos/Hardware">Equipos / Hardware (Computadora, Impresora, Teclado, etc.)</option>
                                <option value="Sistemas/Programas">Sistemas / Programas (SIGM, Navegador, Office)</option>
                                <option value="Accesos/Contraseñas">Accesos / Contraseñas (Restablecer clave, crear cuenta)</option>
                                <option value="Red/Internet">Red / Internet (Sin conexión, Wifi lento, cable suelto)</option>
                                <option value="Otros">Otros problemas</option>
                            </select>
                        </div>

                        <div>
                            <label for="impact" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">¿Qué tanto afecta a tu trabajo? (Urgencia) <span class="text-rose-500">*</span></label>
                            <select name="impact" id="impact" required class="w-full bg-slate-50 border border-slate-200 focus:border-castilla-600 focus:bg-white rounded-2xl px-4 py-3 text-xs sm:text-sm transition-all outline-none">
                                <option value="Individual" selected>Solo me pasa a mí (Trabajo parcial detenido)</option>
                                <option value="Grupal">Afecta a mi área / oficina (Varios usuarios afectados)</option>
                                <option value="Critico">Todo el departamento / oficina está parado (Operación crítica detenida)</option>
                            </select>
                        </div>

                        <div>
                            <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 font-bold">4. Asunto / Falla Corta <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" id="title" required value="{{ old('title') }}" placeholder="Ej. Impresora no enciende o computador con pantalla azul" class="w-full bg-slate-50 border border-slate-200 focus:border-castilla-600 focus:bg-white rounded-2xl px-4 py-3 text-xs sm:text-sm transition-all outline-none">
                        </div>

                        <div>
                            <label for="description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">5. Detalle del Inconveniente (Síntoma) <span class="text-rose-500">*</span></label>
                            <textarea name="description" id="description" rows="3" required placeholder="Describe libremente qué intentabas hacer, qué sucedió y si apareció algún mensaje de error en pantalla." class="w-full bg-slate-50 border border-slate-200 focus:border-castilla-600 focus:bg-white rounded-2xl px-4 py-3 text-xs sm:text-sm transition-all outline-none resize-none">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label for="attachments" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Adjuntar Capturas o Fotos del Error (Opcional)</label>
                            <input type="file" name="attachments[]" id="attachments" multiple accept="image/*,video/*,application/pdf" class="w-full bg-slate-50 border border-slate-200 focus:border-castilla-600 focus:bg-white rounded-2xl px-4 py-2.5 text-xs transition-all outline-none">
                        </div>

                        <button type="submit" class="w-full bg-castilla-600 hover:bg-castilla-700 text-white font-bold py-3.5 rounded-2xl text-center text-sm shadow hover:shadow-md transition-all duration-150 flex items-center justify-center gap-2 mt-2 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span>Registrar y Generar Ticket de Soporte</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Tickets List or Public Inquiry -->
            <div class="lg:col-span-7 space-y-6">
                @if($user)
                    {{-- HISTORIAL DE TICKETS PARA USUARIO LOGEADO --}}
                    <div class="glass-card rounded-3xl p-6 shadow-md">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="bg-castilla-50 p-2.5 rounded-2xl text-castilla-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-slate-800">Mis Incidencias Registradas</h2>
                                    <p class="text-[11px] text-slate-400">Historial y estado en tiempo real de tus solicitudes.</p>
                                </div>
                            </div>
                            <span class="text-xs bg-slate-100 text-slate-600 px-3 py-1 rounded-full font-bold">
                                {{ $tickets->count() }} Casos
                            </span>
                        </div>

                        @if($tickets->isEmpty())
                            <div class="text-center py-12">
                                <div class="text-4xl mb-2">📋</div>
                                <h3 class="text-sm font-bold text-slate-700">No tienes tickets registrados</h3>
                                <p class="text-xs text-slate-400 mt-1">Usa el formulario a la izquierda para reportar tu primer caso.</p>
                            </div>
                        @else
                            <div class="space-y-4 max-h-[650px] overflow-y-auto pr-2">
                                @foreach($tickets as $ticket)
                                    @include('helpdesk.partials.ticket-card', ['ticket' => $ticket])
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    {{-- VISTA PARA INVITADOS / PÚBLICO MUNICIPAL --}}
                    <!-- Consulta Rápida de Ticket -->
                    <div class="glass-card rounded-3xl p-6 shadow-md">
                        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                            <div class="bg-castilla-50 p-2.5 rounded-2xl text-castilla-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-800">Consultar Estado de tu Incidencia</h2>
                                <p class="text-[11px] text-slate-400">Ingresa el código correlativo de tu ticket (ej. INC-2026-0001) para ver los avances del soporte.</p>
                            </div>
                        </div>

                        <form action="{{ route('helpdesk.portal') }}" method="GET" class="flex gap-2 mb-4">
                            <input
                                type="text"
                                name="search_code"
                                value="{{ $searchedCode }}"
                                placeholder="INC-2026-XXXX"
                                class="flex-grow bg-slate-50 border border-slate-300 focus:border-castilla-600 focus:bg-white rounded-2xl px-4 py-2.5 text-xs sm:text-sm font-mono font-bold uppercase transition-all outline-none"
                            >
                            <button
                                type="submit"
                                class="bg-castilla-600 hover:bg-castilla-700 text-white font-semibold px-5 py-2.5 rounded-2xl text-xs sm:text-sm shadow-sm transition-all duration-150 flex items-center gap-1.5 cursor-pointer"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span>Buscar</span>
                            </button>
                        </form>

                        @if($searchResult)
                            <div class="mt-4">
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Resultado de la Búsqueda:</span>
                                @include('helpdesk.partials.ticket-card', ['ticket' => $searchResult])
                            </div>
                        @elseif($searchedCode)
                            <div class="bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-2xl text-xs flex items-start gap-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <span class="font-bold block">No se encontró ningún ticket con el código: {{ $searchedCode }}</span>
                                    <span>Verifica el código correlativo e intenta de nuevo. Los códigos siguen el formato <strong>INC-2026-XXXX</strong>.</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Información de Atención ODT y Pasos -->
                    <div class="glass-card rounded-3xl p-6 shadow-md space-y-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 mb-1 flex items-center gap-2">
                                <span class="text-castilla-600 text-lg">ℹ️</span>
                                Información de Atención - Soporte TI
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                La Oficina de Desarrollo Tecnológico (ODT) brinda soporte integral de computadores, redes, telefonía y sistemas a todas las áreas municipales de Castilla.
                            </p>
                        </div>

                        <!-- Pasos del Flujo de Atención -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 text-center">
                                <div class="w-8 h-8 rounded-full bg-castilla-100 text-castilla-700 font-bold text-xs flex items-center justify-center mx-auto mb-2">1</div>
                                <h4 class="text-xs font-bold text-slate-700 uppercase mb-1">Registro</h4>
                                <p class="text-[10px] text-slate-500 leading-normal">Selecciona tu oficina. Se precarga el responsable y se emite tu código INC.</p>
                            </div>
                            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 text-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center mx-auto mb-2">2</div>
                                <h4 class="text-xs font-bold text-slate-700 uppercase mb-1">Atención ODT</h4>
                                <p class="text-[10px] text-slate-500 leading-normal">Un técnico asume el ticket, diagnostica y repara el equipo o sistema.</p>
                            </div>
                            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 text-center">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center mx-auto mb-2">3</div>
                                <h4 class="text-xs font-bold text-slate-700 uppercase mb-1">Cierre</h4>
                                <p class="text-[10px] text-slate-500 leading-normal">Solución aplicada y firma de conformidad en la ficha técnica.</p>
                            </div>
                        </div>

                        <!-- Canales de Urgencia -->
                        <div class="border-t border-slate-100 pt-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-700 uppercase tracking-wide">Horario Presencial:</span>
                                <span>Lunes a Viernes 8:00 AM - 4:00 PM</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-700 uppercase tracking-wide">Ubicación:</span>
                                <span>Palacio Municipal - 2do Piso (ODT)</span>
                            </div>
                        </div>
                    </div>
                @endif
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

    <!-- Scripts -->
    <script>
        const officeResponsibles = @json($officeResponsibles);

        // Helper para remover acentos y comparar en minúsculas
        function normalizeStr(str) {
            return (str || '')
                .toString()
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim();
        }

        // Abrir / Cerrar el menú desplegable del buscador de oficinas
        function toggleOfficeDropdown() {
            const menu = document.getElementById('office_dropdown_menu');
            const chevron = document.getElementById('office_chevron');
            const isHidden = menu.classList.contains('hidden');

            if (isHidden) {
                openOfficeDropdown();
            } else {
                closeOfficeDropdown();
            }
        }

        function openOfficeDropdown() {
            const menu = document.getElementById('office_dropdown_menu');
            const chevron = document.getElementById('office_chevron');
            const input = document.getElementById('office_search_input');
            const trigger = document.getElementById('office_trigger_btn');

            menu.classList.remove('hidden');
            chevron.classList.add('rotate-180');
            trigger.classList.add('border-castilla-600', 'ring-2', 'ring-castilla-100');

            // Enfocar el campo de búsqueda inmediatamente
            setTimeout(() => {
                input.focus();
                input.select();
            }, 50);
        }

        function closeOfficeDropdown() {
            const menu = document.getElementById('office_dropdown_menu');
            const chevron = document.getElementById('office_chevron');
            const trigger = document.getElementById('office_trigger_btn');

            menu.classList.add('hidden');
            chevron.classList.remove('rotate-180');
            trigger.classList.remove('ring-2', 'ring-castilla-100');
        }

        // Filtrado en tiempo real de las opciones por texto o responsable
        function filterOfficeOptions(query) {
            const q = normalizeStr(query);
            const clearBtn = document.getElementById('office_search_clear');
            const options = document.querySelectorAll('.office-option');
            let matchCount = 0;

            if (clearBtn) {
                clearBtn.classList.toggle('hidden', q.length === 0);
            }

            options.forEach(opt => {
                const name = normalizeStr(opt.getAttribute('data-name'));
                const resp = normalizeStr(opt.getAttribute('data-resp'));
                const match = name.includes(q) || resp.includes(q);

                opt.style.display = match ? 'flex' : 'none';
                if (match) matchCount++;
            });

            const noResults = document.getElementById('office_no_results');
            if (noResults) {
                noResults.classList.toggle('hidden', matchCount > 0);
            }

            const countLabel = document.getElementById('office_count_label');
            if (countLabel) {
                countLabel.textContent = matchCount === options.length
                    ? `${options.length} dependencias disponibles`
                    : `${matchCount} encontrada(s)`;
            }
        }

        function clearOfficeSearch() {
            const input = document.getElementById('office_search_input');
            input.value = '';
            filterOfficeOptions('');
            input.focus();
        }

        // Selección de una oficina desde la lista
        function selectOfficeOption(officeId, officeName) {
            const hiddenInput = document.getElementById('office_id');
            const triggerText = document.getElementById('office_trigger_text');
            const errorMsg = document.getElementById('office_error_msg');
            const triggerBtn = document.getElementById('office_trigger_btn');

            hiddenInput.value = officeId;
            triggerText.textContent = officeName;
            triggerText.classList.remove('text-slate-400');
            triggerText.classList.add('text-slate-800', 'font-bold');

            // Quitar estilos de error si los había
            if (errorMsg) errorMsg.classList.add('hidden');
            if (triggerBtn) triggerBtn.classList.remove('border-rose-500', 'ring-rose-100');

            // Actualizar checkmarks
            document.querySelectorAll('.office-check-icon').forEach(icon => {
                const isCurrent = icon.getAttribute('data-for-id') === officeId.toString();
                icon.classList.toggle('hidden', !isCurrent);
            });

            // Disparar carga de datos del responsable
            handleOfficeChange(officeId);

            // Cerrar menú desplegable
            closeOfficeDropdown();
        }

        // Carga automática del responsable asociado a la oficina
        function handleOfficeChange(officeId) {
            const data = officeResponsibles[officeId];
            const reqNameInput = document.getElementById('requester_name');
            const posInput = document.getElementById('responsible_position');
            const phoneInput = document.getElementById('contact_phone');
            const userIdInput = document.getElementById('responsible_user_id');
            const badge = document.getElementById('auto_loaded_badge');

            if (data && data.has_responsible) {
                reqNameInput.value = data.name;
                posInput.value = data.position;
                if (data.phone) {
                    phoneInput.value = data.phone;
                }
                userIdInput.value = data.user_id || '';
                badge.classList.remove('hidden');
                badge.textContent = 'Responsable Asignado';
            } else {
                reqNameInput.value = '';
                reqNameInput.placeholder = 'Ingrese nombre del solicitante o responsable';
                posInput.value = 'RESPONSABLE DE LA DEPENDENCIA';
                userIdInput.value = '';
                badge.classList.add('hidden');
            }
        }

        // Validación previa al envío para asegurar que seleccionó oficina
        function validateTicketForm() {
            const officeId = document.getElementById('office_id').value;
            const errorMsg = document.getElementById('office_error_msg');
            const triggerBtn = document.getElementById('office_trigger_btn');

            if (!officeId) {
                if (errorMsg) errorMsg.classList.remove('hidden');
                if (triggerBtn) {
                    triggerBtn.classList.add('border-rose-500', 'ring-2', 'ring-rose-100');
                    triggerBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                openOfficeDropdown();
                return false;
            }
            return true;
        }

        // Selección rápida de categoría visual
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

        // Manejar cierre al hacer clic fuera del componente
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('office_select_wrapper');
            const menu = document.getElementById('office_dropdown_menu');
            if (wrapper && !wrapper.contains(e.target) && menu && !menu.classList.contains('hidden')) {
                closeOfficeDropdown();
            }
        });

        // Manejar teclas Escape y Enter en el buscador
        document.addEventListener('keydown', function(e) {
            const menu = document.getElementById('office_dropdown_menu');
            if (!menu || menu.classList.contains('hidden')) return;

            if (e.key === 'Escape') {
                closeOfficeDropdown();
            }
        });

        document.getElementById('office_search_input')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                // Seleccionar la primera opción visible en la lista filtrada
                const firstVisible = Array.from(document.querySelectorAll('.office-option'))
                    .find(opt => opt.style.display !== 'none');
                if (firstVisible) {
                    firstVisible.click();
                }
            }
        });

        // Inicializar estado al cargar la página si ya había una oficina elegida
        document.addEventListener('DOMContentLoaded', function() {
            const currentOfficeId = document.getElementById('office_id')?.value;
            if (currentOfficeId) {
                const opt = document.querySelector(`.office-option[data-id="${currentOfficeId}"]`);
                if (opt) {
                    const name = opt.getAttribute('data-name');
                    selectOfficeOption(currentOfficeId, name);
                }
            }
        });
    </script>
</body>
</html>
