<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Personal;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpdeskPortalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user && ! (bool) $user->is_active) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect('/admin/login')->withErrors([
                'data.username' => 'Tu cuenta ha sido desactivada o se encuentra PENDIENTE DE APROBACIÓN por la Oficina de Desarrollo Tecnológico (ODT).',
            ]);
        }

        $offices = Office::where('is_active', true)->orderBy('name')->get();
        $categories = TicketCategory::where('is_active', true)->get();

        $allPersonals = Personal::where('is_active', true)->with('user')->get()->groupBy('office_id');
        $allUsers = User::where('is_active', true)->get()->groupBy('office_id');

        $officeResponsibles = [];
        foreach ($offices as $o) {
            $personal = $allPersonals->get($o->id)?->first();
            $userInOffice = $allUsers->get($o->id)?->first();

            $name = '';
            $position = '';
            $phone = '';
            $userId = null;

            if ($personal) {
                $name = mb_strtoupper((string) $personal->full_name, 'UTF-8');
                $position = mb_strtoupper((string) ($personal->position ?: 'RESPONSABLE DE LA DEPENDENCIA'), 'UTF-8');
                $phone = (string) ($personal->phone ?: '');
                $userId = $personal->user?->id ?? $userInOffice?->id;
            } elseif ($userInOffice) {
                $name = mb_strtoupper((string) $userInOffice->name, 'UTF-8');
                $position = 'RESPONSABLE DE LA DEPENDENCIA';
                $userId = $userInOffice->id;
            }

            $officeResponsibles[$o->id] = [
                'id' => $o->id,
                'name' => $name,
                'position' => $position,
                'phone' => $phone,
                'user_id' => $userId,
                'has_responsible' => ! empty($name),
            ];
        }

        if ($user) {
            $office = $user->office;
            $tickets = Ticket::where('requester_id', $user->id)
                ->orWhere(function ($q) use ($user) {
                    if ($user->office_id) {
                        $q->where('office_id', $user->office_id);
                    }
                })
                ->with(['category', 'assignee', 'office'])
                ->orderBy('created_at', 'desc')
                ->get();
            $searchedCode = null;
            $searchResult = null;
        } else {
            $office = null;
            $tickets = collect();
            $searchedCode = trim((string) $request->query('search_code'));
            $searchResult = null;

            if (! empty($searchedCode)) {
                $searchResult = Ticket::where('ticket_code', strtoupper($searchedCode))
                    ->with(['category', 'assignee', 'office'])
                    ->first();
            }
        }

        return view('helpdesk.portal', compact(
            'user',
            'office',
            'offices',
            'categories',
            'tickets',
            'officeResponsibles',
            'searchedCode',
            'searchResult'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'office_id' => 'required|exists:offices,id',
            'requester_name' => 'required|string|max:150',
            'contact_phone' => 'nullable|string|max:50',
            'user_category' => 'required|string|in:Equipos/Hardware,Sistemas/Programas,Accesos/Contraseñas,Red/Internet,Otros',
            'impact' => 'required|string|in:Individual,Grupal,Critico',
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
        ], [
            'office_id.required' => 'Debe seleccionar la Dependencia u Oficina requirente.',
            'office_id.exists' => 'La Dependencia u Oficina seleccionada no es válida.',
            'requester_name.required' => 'El nombre del solicitante o responsable es obligatorio.',
            'user_category.required' => 'Debe seleccionar la categoría del problema.',
            'impact.required' => 'Debe indicar el nivel de urgencia o afectación.',
            'title.required' => 'El asunto o título del problema es obligatorio.',
            'description.required' => 'La descripción detallada del problema es obligatoria.',
        ]);

        $requesterId = null;
        $requesterName = trim((string) $request->requester_name);

        if (Auth::check()) {
            $authUser = Auth::user();
            $requesterId = $authUser->id;
            if (empty($requesterName)) {
                $requesterName = $authUser->name;
            }
        } else {
            if ($request->filled('responsible_user_id')) {
                $userCandidate = User::where('id', $request->responsible_user_id)
                    ->where('office_id', $request->office_id)
                    ->first();
                if ($userCandidate) {
                    $requesterId = $userCandidate->id;
                }
            }
            if (! $requesterId) {
                $userCandidate = User::where('office_id', $request->office_id)->first();
                $requesterId = $userCandidate?->id;
            }
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

        // Mapear categoría técnica adecuada
        $userCat = mb_strtolower(trim((string) $request->user_category), 'UTF-8');
        $catMap = [
            'equipos/hardware' => 'hardware y computadores',
            'sistemas/programas' => 'sistemas municipales',
            'accesos/contraseñas' => 'sistemas municipales',
            'accesos/contrasenas' => 'sistemas municipales',
            'red/internet' => 'red y conectividad',
            'otros' => 'sistemas municipales',
        ];
        $targetCatName = $catMap[$userCat] ?? 'sistemas municipales';
        $category = TicketCategory::whereRaw('LOWER(name) = ?', [$targetCatName])->first();
        $categoryId = $category?->id ?? TicketCategory::first()?->id;

        $ticket = Ticket::create([
            'category_id' => $categoryId,
            'user_category' => $request->user_category,
            'requester_id' => $requesterId,
            'requester_name' => mb_strtoupper($requesterName, 'UTF-8'),
            'contact_phone' => $request->contact_phone,
            'office_id' => $request->office_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $priority,
            'impact' => $request->impact,
            'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
            'status' => 'Abierto',
        ]);

        return redirect()->route('helpdesk.portal', ['search_code' => $ticket->ticket_code])
            ->with('success', "Ticket {$ticket->ticket_code} registrado exitosamente. Nuestro personal técnico atenderá el requerimiento.");
    }
}
