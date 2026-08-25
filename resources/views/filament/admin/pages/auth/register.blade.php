<x-filament-panels::page.simple>
    @if ($registeredUsername)
        <div style="display: flex; flex-direction: column; gap: 1.5rem; text-align: center; padding: 0.5rem 0;">
            <!-- Icono de Estado -->
            <div style="margin: 0 auto; display: flex; align-items: center; justify-content: center; width: 4.5rem; height: 4.5rem; border-radius: 9999px; background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" style="width: 2.5rem; height: 2.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>

            <!-- Título y Descripción -->
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: #ffffff; margin: 0; tracking: -0.025em;">
                    ¡Solicitud de Registro Enviada!
                </h2>
                <p style="font-size: 0.875rem; color: #9ca3af; margin: 0; line-height: 1.5;">
                    Tu cuenta ha sido registrada con éxito y ha ingresado al proceso de revisión y aprobación por la <strong style="color: #f3f4f6;">Oficina de Desarrollo Tecnológico (ODT)</strong>.
                </p>
            </div>

            <!-- CARD RESALTADA CON ALTO CONTRASTE Y BRILLO -->
            <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.25)); border: 2px solid #10b981; box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.3); padding: 1.25rem; border-radius: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
                <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #34d399; display: flex; align-items: center; justify-content: center; gap: 0.35rem;">
                    🔑 TU USUARIO DE ACCESO AUTOGENERADO
                </span>
                
                <div style="background: rgba(0, 0, 0, 0.55); border: 1.5px dashed #34d399; padding: 0.85rem 1rem; border-radius: 0.75rem; font-size: 2rem; font-weight: 900; font-family: monospace; color: #6ee7b7; letter-spacing: 0.12em; text-shadow: 0 0 15px rgba(52, 211, 153, 0.5); word-break: break-all; user-select: all;">
                    {{ $registeredUsername }}
                </div>

                <p style="font-size: 0.75rem; color: #d1d5db; margin: 0; line-height: 1.4;">
                    📌 <strong>Anota o guarda este usuario</strong>. Lo utilizarás para iniciar sesión una vez que la ODT apruebe tu cuenta.
                </p>
            </div>

            <!-- Botón Volver -->
            <div style="margin-top: 0.5rem;">
                <a href="{{ filament()->getLoginUrl() }}" 
                   style="display: inline-flex; width: 100%; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 0.75rem; background: #008435; padding: 0.85rem 1.25rem; font-size: 0.875rem; font-weight: 700; color: #ffffff; text-decoration: none; box-shadow: 0 4px 14px rgba(0, 132, 53, 0.3); transition: all 0.2s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Volver al Inicio de Sesión
                </a>
            </div>
        </div>
    @else
        <form wire:submit="register" class="space-y-6">
            {{ $this->form }}

            <div style="margin-top: 1.75rem;">
                <x-filament::button type="submit" class="w-full">
                    Solicitar Cuenta de Usuario
                </x-filament::button>
            </div>
        </form>
    @endif
</x-filament-panels::page.simple>
