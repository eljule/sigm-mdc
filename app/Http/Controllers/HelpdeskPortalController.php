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
            'category_id' => 'required|exists:ticket_categories,id',
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'priority' => 'required|string|in:Baja,Media,Alta',
        ]);

        $user = Auth::user();

        if (!$user->office_id) {
            return back()->withErrors(['office_id' => 'El usuario no tiene una oficina asignada para reportar el ticket.']);
        }

        Ticket::create([
            'category_id' => $request->category_id,
            'requester_id' => $user->id,
            'office_id' => $user->office_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'Abierto',
        ]);

        return redirect()->route('helpdesk.portal')->with('success', 'Ticket registrado correctamente.');
    }
}
