<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpdeskPortalController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        if (! $user || ! (bool) $user->is_active) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect('/admin/login')->withErrors([
                'data.username' => 'Tu cuenta ha sido desactivada o se encuentra PENDIENTE DE APROBACIÓN por la Oficina de Desarrollo Tecnológico (ODT).',
            ]);
        }
        $office = $user->office;
        $categories = TicketCategory::where('is_active', true)->get();
        
        $tickets = Ticket::where('requester_id', $user->id)
            ->with(['category', 'assignee'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('helpdesk.portal', compact('user', 'office', 'categories', 'tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_category' => 'required|string|in:Equipos/Hardware,Sistemas/Programas,Accesos/Contraseñas,Red/Internet,Otros',
            'impact' => 'required|string|in:Individual,Grupal,Critico',
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        $user = Auth::user();

        if (!$user->office_id) {
            return back()->withErrors(['office_id' => 'El usuario no tiene una oficina asignada para reportar el ticket.']);
        }

        // Mapear el impacto del usuario a prioridad de backend
        $priorityMap = [
            'Individual' => 'Baja',
            'Grupal' => 'Media',
            'Critico' => 'Alta',
        ];
        $priority = $priorityMap[$request->impact] ?? 'Baja';

        // Manejar subida de archivos
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('ticket-attachments', 'public');
                    $attachmentPaths[] = $path;
                }
            }
        }

        Ticket::create([
            'user_category' => $request->user_category,
            'requester_id' => $user->id,
            'office_id' => $user->office_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $priority,
            'impact' => $request->impact,
            'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
            'status' => 'Abierto',
        ]);

        return redirect()->route('helpdesk.portal')->with('success', 'Ticket registrado correctamente.');
    }
}
