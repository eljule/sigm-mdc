<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\AssetMaintenance;
use App\Models\Ticket;
use Illuminate\Http\Request;

class FichaController extends Controller
{
    public function asignacion(int $id)
    {
        $assignment = AssetAssignment::with([
            'asset.model.brand',
            'asset.category',
            'asset.components.category',
            'asset.components.model.brand',
            'user',
            'office'
        ])->findOrFail($id);

        return view('fichas.asignacion', compact('assignment'));
    }

    public function componente(int $id)
    {
        $asset = Asset::with([
            'parent.model.brand',
            'parent.category',
            'model.brand',
            'category'
        ])->findOrFail($id);

        return view('fichas.componente', compact('asset'));
    }

    public function ticket(int $id)
    {
        $ticket = Ticket::with([
            'category',
            'requester',
            'office',
            'assignee',
            'ticketConsumables.consumable'
        ])->findOrFail($id);

        return view('fichas.ticket', compact('ticket'));
    }

    public function mantenimiento(int $id)
    {
        $maintenance = AssetMaintenance::with([
            'asset.model.brand',
            'asset.category',
            'ticket',
        ])->findOrFail($id);

        return view('fichas.mantenimiento', compact('maintenance'));
    }

    public function baja(int $id)
    {
        $decommission = \App\Models\AssetDecommission::with([
            'asset.model.brand',
            'asset.category',
            'asset.assignments' => fn ($q) => $q->with(['user', 'office'])->orderByDesc('assigned_at'),
            'ticket',
            'decommissionedBy',
        ])->findOrFail($id);

        return view('fichas.baja', compact('decommission'));
    }
}
