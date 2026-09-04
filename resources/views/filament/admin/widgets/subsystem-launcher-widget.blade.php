<x-filament-widgets::widget>
    <x-filament::section>
        <div class="mb-4">
            <h2 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2 m-0">
                <span>🚀 Acceso Rápido a Subsistemas Municipales</span>
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 mb-0">
                Selecciona un subsistema para ingresar a su panel de gestión operativa.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- ITAM -->
            <a href="{{ url('/itam') }}" class="block p-4 rounded-xl border border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/50 dark:bg-emerald-950/20 hover:border-emerald-500 transition-all duration-150 group decoration-none">
                <div class="flex items-center justify-between">
                    <span class="text-2xl">💻</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200">Activo</span>
                </div>
                <h3 class="text-sm font-bold text-emerald-950 dark:text-emerald-100 mt-3 mb-1 group-hover:text-emerald-600 transition-colors">
                    ITAM - Gestión de Activos TI
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 m-0">
                    Inventarios, equipos, consumibles, licencias y mantenimientos.
                </p>
            </a>

            <!-- Helpdesk -->
            <a href="{{ url('/helpdesk') }}" class="block p-4 rounded-xl border border-sky-200 dark:border-sky-900/50 bg-sky-50/50 dark:bg-sky-950/20 hover:border-sky-500 transition-all duration-150 group decoration-none">
                <div class="flex items-center justify-between">
                    <span class="text-2xl">🎫</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-sky-100 dark:bg-sky-900 text-sky-800 dark:text-sky-200">Activo</span>
                </div>
                <h3 class="text-sm font-bold text-sky-950 dark:text-sky-100 mt-3 mb-1 group-hover:text-sky-600 transition-colors">
                    Helpdesk - Mesa de Ayuda
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 m-0">
                    Atención de incidencias, tickets de soporte y monitoreo de técnicos.
                </p>
            </a>

            <!-- Licencias de Transportes -->
            <a href="{{ url('/') }}" class="block p-4 rounded-xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/50 dark:bg-amber-950/20 hover:border-amber-500 transition-all duration-150 group decoration-none">
                <div class="flex items-center justify-between">
                    <span class="text-2xl">🛵</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200">Portal</span>
                </div>
                <h3 class="text-sm font-bold text-amber-950 dark:text-amber-100 mt-3 mb-1 group-hover:text-amber-600 transition-colors">
                    Licencias de Transportes
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 m-0">
                    Empadronamiento de vehículos, licencias de conducir y registro de mototaxis.
                </p>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
