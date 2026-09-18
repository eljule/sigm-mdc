<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Office;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class HelpdeskSeeder extends Seeder
{
    /**
     * Seed Helpdesk categories and initial sample tickets.
     */
    public function run(): void
    {
        // 1. Categorías de Tickets
        $categories = [
            ['id' => 1, 'name' => 'red y conectividad', 'description' => 'problemas de internet, cables de red, wifi y telefonía', 'sla_hours' => 2, 'is_active' => true],
            ['id' => 2, 'name' => 'sistemas municipales', 'description' => 'fallas en el sigm u otros aplicativos del municipio', 'sla_hours' => 4, 'is_active' => true],
            ['id' => 3, 'name' => 'hardware y computadores', 'description' => 'equipos que no encienden, problemas de monitor, teclado, mouse', 'sla_hours' => 8, 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            TicketCategory::updateOrCreate(['id' => $cat['id']], $cat);
        }

        // 2. Ticket Demo
        $tickets = [
            ['id' => 1, 'ticket_code' => 'INC-2026-0001', 'category_id' => 1, 'requester_id' => 2, 'office_id' => 26, 'title' => 'sin conexión al servidor tributario', 'description' => 'no puedo ingresar al módulo de recaudación. sale error de tiempo de espera agotado. mis compañeros sí tienen internet pero no pueden abrir el sistema.', 'priority' => 'alta', 'status' => 'abierto', 'user_category' => 'red/internet', 'impact' => 'critico', 'sla_expires_at' => '2026-09-07 16:31:35'],
        ];

        foreach ($tickets as $tData) {
            Ticket::updateOrCreate(['id' => $tData['id']], $tData);
        }
    }
}
