<div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:border-slate-300 transition-all duration-150">
    <div class="flex flex-wrap justify-between items-start gap-2 mb-2">
        <div class="flex items-center gap-2">
            <span class="text-xs font-mono font-bold text-castilla-700 uppercase tracking-wider bg-castilla-50 border border-castilla-200 px-2.5 py-0.5 rounded-lg">{{ $ticket->ticket_code }}</span>
            <span class="text-[10px] text-slate-400 font-medium">{{ $ticket->created_at->format('d/m/Y h:i A') }}</span>
        </div>
        <div>
            @php
                $statusColor = match(strtolower($ticket->status)) {
                    'abierto' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'en proceso' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'internado' => 'bg-orange-50 text-orange-700 border-orange-200',
                    'en espera' => 'bg-purple-50 text-purple-700 border-purple-200',
                    'resuelto' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'cerrado' => 'bg-slate-100 text-slate-600 border-slate-200',
                    default => 'bg-slate-50 text-slate-600 border-slate-200'
                };
                
                $priorityColor = match(strtolower($ticket->priority)) {
                    'baja' => 'bg-slate-50 text-slate-600 border-slate-200',
                    'media' => 'bg-orange-50 text-orange-700 border-orange-200',
                    'alta' => 'bg-rose-50 text-rose-700 border-rose-200',
                    default => 'bg-slate-50 text-slate-600'
                };
            @endphp
            <span class="text-[9px] border px-2 py-0.5 rounded-full font-bold uppercase tracking-wider {{ $statusColor }}">{{ $ticket->status }}</span>
            <span class="text-[9px] border px-2 py-0.5 rounded-full font-bold uppercase tracking-wider {{ $priorityColor }}">{{ $ticket->priority }}</span>
        </div>
    </div>

    <h3 class="text-sm font-bold text-slate-800 mb-1 leading-snug uppercase">{{ $ticket->title }}</h3>
    <p class="text-xs text-slate-600 font-light leading-relaxed mb-3">{{ $ticket->description }}</p>

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

    <div class="flex flex-wrap items-center justify-between gap-2 pt-3 border-t border-slate-100 text-[10px] text-slate-500">
        <div class="flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span>Oficina: <strong class="uppercase text-slate-700">{{ $ticket->office->name ?? 'N/D' }}</strong></span>
        </div>
        <div class="flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Solicitante: <strong class="uppercase text-slate-700">{{ $ticket->requester?->name ?? $ticket->requester_name ?? 'Usuario Municipal' }}</strong></span>
        </div>
        <div class="flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Técnico Asignado: <strong class="uppercase text-slate-700">{{ $ticket->assignee?->name ?? 'PENDIENTE' }}</strong></span>
        </div>
    </div>

    @if(in_array(strtolower($ticket->status), ['resuelto', 'cerrado']) && $ticket->solution_applied)
        <div class="mt-3 bg-emerald-50/60 border border-emerald-200/80 p-3 rounded-xl text-xs">
            <span class="font-bold text-emerald-800 block mb-0.5 uppercase">Solución Técnica Aplicada:</span>
            <p class="text-slate-700 font-medium">{{ $ticket->solution_applied }}</p>
        </div>
    @endif
</div>
