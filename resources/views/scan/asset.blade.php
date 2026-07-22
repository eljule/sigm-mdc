<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha Técnica del Activo: {{ $asset->computer_code }}</title>
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
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
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            950: '#052e16',
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
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-emerald-50 via-slate-100 to-castilla-100 min-h-screen font-sans flex flex-col justify-between">

    <!-- Header Banner -->
    <header class="bg-gradient-to-r from-castilla-800 to-castilla-700 py-6 px-4 shadow-md border-b border-castilla-600">
        <div class="max-w-md mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white p-2 rounded-lg shadow-sm">
                    <img src="{{ asset('logo-white.png') }}" alt="Logo Castilla" class="h-10 w-auto object-contain">
                </div>
                <div>
                    <h2 class="text-white font-extrabold text-base tracking-tight leading-tight">MUNICIPALIDAD DE CASTILLA</h2>
                    <span class="text-emerald-300 text-[10px] uppercase font-semibold tracking-wider">Oficina de Desarrollo Tecnológico (ODT)</span>
                </div>
            </div>
            <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[10px] font-bold px-2 py-1 rounded-full uppercase">
                ITAM
            </span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center p-4">
        <div class="w-full max-w-md glass-card rounded-3xl shadow-xl overflow-hidden my-4">
            
            <!-- Asset Header -->
            <div class="p-6 bg-gradient-to-br from-castilla-700 to-castilla-900 text-white relative">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(52,211,153,0.15),transparent)] pointer-events-none"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <span class="bg-castilla-600 text-emerald-100 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                            {{ $asset->category->name ?? 'Activo' }}
                        </span>
                        <h1 class="text-2xl font-extrabold mt-2 tracking-tight">{{ $asset->model?->brand?->name ?? '' }} {{ $asset->model?->name ?? 'Activo sin Modelo' }}</h1>
                        <p class="text-xs text-emerald-200 mt-1">S/N: {{ $asset->serial_number }}</p>
                    </div>
                    <!-- Status Badge -->
                    @php
                        $statusColors = match ($asset->status) {
                            'Disponible' => 'bg-emerald-400 text-emerald-950 border-emerald-300',
                            'Asignado' => 'bg-sky-400 text-sky-950 border-sky-300',
                            'Mantenimiento' => 'bg-amber-400 text-amber-950 border-amber-300',
                            'Baja' => 'bg-rose-400 text-rose-950 border-rose-300',
                            default => 'bg-slate-400 text-slate-950 border-slate-300'
                        };
                    @endphp
                    <span class="{{ $statusColors }} border text-xs font-bold px-3 py-1 rounded-xl shadow-sm">
                        {{ $asset->status }}
                    </span>
                </div>
            </div>

            <!-- Asset Details Grid -->
            <div class="p-6 space-y-6">
                
                <!-- Identification Codes -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/60 p-3 rounded-2xl border border-slate-100">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Código de TI</span>
                        <span class="text-sm font-bold text-slate-800 tracking-tight">{{ $asset->computer_code ?? 'NO ASIGNADO' }}</span>
                    </div>
                    <div class="bg-white/60 p-3 rounded-2xl border border-slate-100">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Cód. Patrimonial</span>
                        <span class="text-sm font-bold text-slate-800 tracking-tight">{{ $asset->asset_code }}</span>
                    </div>
                </div>

                <!-- Active Assignment -->
                @php
                    $activeAssignment = $asset->assignments->whereNull('returned_at')->first();
                @endphp
                <div class="bg-white/80 p-4 rounded-2xl border border-slate-200/60 shadow-sm">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-castilla-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:1rem; height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Asignación Responsable
                    </h3>
                    @if ($activeAssignment)
                        <div class="space-y-2">
                            <div>
                                <span class="text-[10px] text-slate-400 block leading-none">Usuario Asignado</span>
                                <span class="text-sm font-semibold text-slate-800">{{ $activeAssignment->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block leading-none">Oficina / Dependencia</span>
                                <span class="text-sm font-semibold text-slate-800">{{ $activeAssignment->office->name }} ({{ $activeAssignment->office->acronym }})</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block leading-none">Asignado el</span>
                                <span class="text-xs text-slate-600">{{ $activeAssignment->assigned_at->format('d/m/Y h:i A') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2 p-1">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            <span class="text-xs text-emerald-700 font-semibold">Disponible en almacén de ODT</span>
                        </div>
                    @endif
                </div>

                <!-- Dynamic EAV Blocks & Characteristics -->
                @if($asset->category)
                    @foreach ($asset->category->blocks as $block)
                        @php
                            $charsWithValues = $block->characteristics->map(function ($char) use ($asset) {
                                $valRecord = $asset->characteristicValues->where('asset_characteristic_id', $char->id)->first();
                                $char->value = $valRecord ? $valRecord->value : null;
                                return $char;
                            })->filter(fn ($char) => $char->value !== null && $char->value !== '');
                        @endphp

                        @if ($charsWithValues->count() > 0)
                            <div class="space-y-3">
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-1 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-castilla-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:1rem; height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    {{ $block->name }}
                                </h3>
                                <div class="bg-white/60 p-4 rounded-2xl border border-slate-100 space-y-3 text-xs">
                                    @foreach ($charsWithValues as $char)
                                        <div class="flex justify-between py-1 border-b border-slate-100 last:border-0">
                                            <span class="text-slate-400">{{ $char->name }}</span>
                                            @if ($char->type === 'boolean')
                                                <span class="font-semibold text-slate-800">{{ $char->value === '1' || strtolower($char->value) === 'true' || $char->value === 'si' || $char->value === 'sí' ? 'Sí' : 'No' }}</span>
                                            @else
                                                <span class="font-semibold text-slate-800 text-right">{{ $char->value }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                <!-- Parent Relationship -->
                @if ($asset->parent)
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 p-4 rounded-2xl border border-emerald-200/50 shadow-sm flex justify-between items-center">
                        <div>
                            <span class="text-[9px] text-emerald-600 uppercase font-bold tracking-wider block">Parte del Equipo Principal</span>
                            <span class="text-xs font-bold text-slate-800">{{ $asset->parent->model?->brand?->name ?? '' }} {{ $asset->parent->model?->name ?? 'Equipo Principal sin Modelo' }}</span>
                            <span class="text-[10px] text-slate-500 block">Cód: {{ $asset->parent->computer_code }}</span>
                        </div>
                        <a href="{{ url('/scan/activo/' . $asset->parent->computer_code) }}" class="bg-castilla-600 hover:bg-castilla-700 text-white font-semibold text-[10px] px-3 py-1.5 rounded-xl shadow-sm transition-all" style="text-decoration:none;">
                            Ver Padre
                        </a>
                    </div>
                @endif

                <!-- Sub-Components Relationship -->
                @if ($asset->components->count() > 0)
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-1 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-castilla-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:1rem; height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Componentes / Periféricos Relacionados
                        </h3>
                        <div class="space-y-2">
                            @foreach ($asset->components as $comp)
                                <div class="bg-white/80 p-3 rounded-2xl border border-slate-200/60 flex justify-between items-center shadow-sm">
                                    <div>
                                        <span class="text-[9px] bg-slate-100 text-slate-600 border border-slate-200/50 px-2 py-0.5 rounded-full font-bold uppercase">{{ $comp->category->name ?? 'Componente' }}</span>
                                        <span class="text-xs font-semibold text-slate-800 block mt-1">{{ $comp->model?->brand?->name ?? '' }} {{ $comp->model?->name ?? 'Componente sin Modelo' }}</span>
                                        <span class="text-[10px] text-slate-400">S/N: {{ $comp->serial_number }} | Cód: {{ $comp->computer_code }}</span>
                                    </div>
                                    <a href="{{ url('/scan/activo/' . $comp->computer_code) }}" class="text-castilla-600 hover:text-castilla-700 font-bold text-[10px] flex items-center gap-1 hover:underline" style="text-decoration:none;">
                                        Escaneo
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:0.75rem; height:0.75rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Software Installed -->
                @if ($asset->softwares->count() > 0)
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-1 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-castilla-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:1rem; height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Software Autorizado
                        </h3>
                        <div class="space-y-2">
                            @foreach ($asset->softwares as $sw)
                                <div class="bg-white/60 p-3 rounded-2xl border border-slate-100 flex justify-between items-center">
                                    <div>
                                        <span class="text-xs font-semibold text-slate-800">{{ $sw->name }}</span>
                                        <span class="text-[10px] text-slate-400 block">Licencia: {{ $sw->license_type }} | Versión: {{ $sw->version }}</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-mono bg-slate-100 px-2 py-0.5 rounded-md">Instalado</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-6 text-center text-[11px] border-t border-slate-800">
        <div class="max-w-md mx-auto px-4 space-y-1">
            <p>Municipalidad Distrital de Castilla &copy; {{ date('Y') }}</p>
            <p class="text-slate-500">Desarrollado por la Oficina de Desarrollo Tecnológico (ODT)</p>
        </div>
    </footer>

</body>
</html>
