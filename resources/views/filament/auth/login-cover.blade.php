<div class="hidden lg:flex login-cover-sidebar">
    <!-- Abstract background pattern/radial glow -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(0,166,60,0.25),transparent)] pointer-events-none"></div>
    <div class="absolute inset-0 login-grid-pattern pointer-events-none"></div>
    
    <!-- Top Left Info -->
    <div class="flex items-center gap-2 relative z-10">
        <span class="login-cover-badge" style="font-size: 0.75rem !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; font-weight: 600 !important; color: #ffffff !important; background-color: rgba(255, 255, 255, 0.1) !important; padding: 0.25rem 0.75rem !important; border-radius: 9999px !important; border: 1px solid rgba(255, 255, 255, 0.2) !important; display: inline-block !important; font-family: 'Outfit', sans-serif !important;">
            Ecosistema Modular SIGM
        </span>
    </div>

    <!-- Center Content: Logo and Title in Glassmorphic Card -->
    <div class="login-glass-card" style="margin-top: auto !important; margin-bottom: auto !important; max-width: 36rem !important; width: 100% !important; position: relative !important; z-index: 10 !important; background: rgba(255, 255, 255, 0.05) !important; backdrop-filter: blur(24px) !important; -webkit-backdrop-filter: blur(24px) !important; padding: 2.5rem !important; border-radius: 1.5rem !important; border: 1px solid rgba(255, 255, 255, 0.15) !important; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important; box-sizing: border-box !important;">
        <img src="{{ asset('logo-white.png') }}" alt="Logo Municipalidad" class="login-cover-logo" style="height: 6rem !important; width: auto !important; margin-bottom: 2rem !important; object-fit: contain !important; display: block !important;">
        <h2 class="login-cover-title" style="font-size: 2.5rem !important; line-height: 1.1 !important; font-weight: 800 !important; letter-spacing: -0.025em !important; margin-top: 0 !important; margin-bottom: 1rem !important; color: #ffffff !important; font-family: 'Outfit', sans-serif !important;">
            Municipalidad Distrital de Castilla
        </h2>
        <p class="login-cover-desc" style="font-size: 1rem !important; font-weight: 300 !important; line-height: 1.625 !important; color: rgba(255, 255, 255, 0.8) !important; margin-bottom: 1.5rem !important; font-family: 'Outfit', sans-serif !important;">
            Acceso exclusivo para colaboradores y personal técnico autorizado. Gestione los servicios tributarios, soporte helpdesk e inventarios tecnológicos de forma centralizada.
        </p>
        <div class="flex items-center gap-3" style="display: flex !important; align-items: center !important; gap: 0.75rem !important;">
            <span class="h-2 w-2 rounded-full bg-emerald-400 shrink-0" style="height: 0.5rem !important; width: 0.5rem !important; border-radius: 9999px !important; background-color: #34d399 !important; flex-shrink: 0 !important; display: inline-block !important;"></span>
            <span class="text-sm text-emerald-200" style="font-size: 0.875rem !important; color: rgba(255, 255, 255, 0.9) !important; font-family: 'Outfit', sans-serif !important;">Innovación y Eficiencia al servicio del ciudadano.</span>
        </div>
    </div>

    <!-- Bottom Footer -->
    <div class="text-xs relative z-10 flex justify-between" style="font-size: 0.75rem !important; color: rgba(255, 255, 255, 0.6) !important; font-family: 'Outfit', sans-serif !important;">
        <span>© {{ date('Y') }} Municipalidad de Castilla - OTI</span>
        <span>v{{ app()->version() }}</span>
    </div>
</div>
