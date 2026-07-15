<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\DocumentType;
use App\Models\LaborCondition;
use App\Models\Office;
use App\Models\Role;
use App\Models\Software;
use App\Models\Subsystem;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\Ubigeo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tipos de Documento
        $dni = DocumentType::create([
            'code' => '01',
            'name' => 'DNI',
            'length' => 8,
            'is_active' => true,
        ]);

        $ruc = DocumentType::create([
            'code' => '06',
            'name' => 'RUC',
            'length' => 11,
            'is_active' => true,
        ]);

        // 2. Ubigeo demo (Lima)
        Ubigeo::create([
            'code' => '150101',
            'department' => 'LIMA',
            'province' => 'LIMA',
            'district' => 'LIMA',
            'is_active' => true,
        ]);

        // 3. Condiciones Laborales
        $cas = LaborCondition::create([
            'code' => 'CAS',
            'name' => 'Contrato Administrativo de Servicios (CAS)',
            'is_active' => true,
        ]);

        $nombrado = LaborCondition::create([
            'code' => 'NOM',
            'name' => 'Decreto Legislativo 276 (Nombrado)',
            'is_active' => true,
        ]);

        // 4. Estructura de Oficinas
        $gerencia = Office::create([
            'parent_id' => null,
            'code' => '01',
            'name' => 'Gerencia Municipal',
            'acronym' => 'GM',
            'is_active' => true,
        ]);

        $rentasOffice = Office::create([
            'parent_id' => $gerencia->id,
            'code' => '01.03.02',
            'name' => 'Subgerencia de Rentas',
            'acronym' => 'SGR',
            'is_active' => true,
        ]);

        $sistemasOffice = Office::create([
            'parent_id' => $gerencia->id,
            'code' => '01.05.01',
            'name' => 'Oficina de Tecnologías de la Información',
            'acronym' => 'OTI',
            'is_active' => true,
        ]);

        // 5. Subsistemas
        $subsystemCentral = Subsystem::create([
            'code' => 'central',
            'name' => 'Dashboard Central y Configuración',
            'description' => 'Núcleo y launcher del SIGM',
            'icon' => 'heroicon-o-cog-6-tooth',
            'url_path' => '/admin',
            'is_active' => true,
        ]);

        $subsystemRentas = Subsystem::create([
            'code' => 'rentas',
            'name' => 'Rentas Municipales',
            'description' => 'Gestión tributaria y cobranzas',
            'icon' => 'heroicon-o-currency-dollar',
            'url_path' => '/rentas',
            'is_active' => true,
        ]);

        $subsystemItam = Subsystem::create([
            'code' => 'itam',
            'name' => 'Inventario y Gestión TI',
            'description' => 'Gestión de activos de hardware y software (ITAM)',
            'icon' => 'heroicon-o-computer-desktop',
            'url_path' => '/itam',
            'is_active' => true,
        ]);

        $subsystemHelpdesk = Subsystem::create([
            'code' => 'helpdesk',
            'name' => 'Soporte Técnico y Helpdesk',
            'description' => 'Mesa de partes y resolución de incidencias',
            'icon' => 'heroicon-o-ticket',
            'url_path' => '/helpdesk',
            'is_active' => true,
        ]);

        // 6. Roles Específicos por Subsistema
        $adminCentralRole = Role::create([
            'subsystem_id' => $subsystemCentral->id,
            'name' => 'Administrador Central',
            'guard_name' => 'web',
        ]);

        $operadorRentasRole = Role::create([
            'subsystem_id' => $subsystemRentas->id,
            'name' => 'Operador de Rentas',
            'guard_name' => 'web',
        ]);

        $adminItamRole = Role::create([
            'subsystem_id' => $subsystemItam->id,
            'name' => 'Administrador de TI',
            'guard_name' => 'web',
        ]);

        $tecnicoHelpdeskRole = Role::create([
            'subsystem_id' => $subsystemHelpdesk->id,
            'name' => 'Técnico de Soporte',
            'guard_name' => 'web',
        ]);

        $usuarioHelpdeskRole = Role::create([
            'subsystem_id' => $subsystemHelpdesk->id,
            'name' => 'Usuario Reportante',
            'guard_name' => 'web',
        ]);

        // 7. Usuarios de Prueba
        // Juan Perez (Técnico de TI / Admin central / Admin ITAM)
        $userJuan = User::create([
            'name' => 'Juan Perez',
            'email' => 'jperez@sigm.gob.pe',
            'password' => Hash::make('password'),
            'document_type_id' => $dni->id,
            'document_number' => '44556677',
            'labor_condition_id' => $cas->id,
            'office_id' => $sistemasOffice->id,
            'is_active' => true,
        ]);

        // Asignar roles a Juan Perez
        $userJuan->allRoles()->attach($adminCentralRole->id, ['subsystem_id' => $subsystemCentral->id]);
        $userJuan->allRoles()->attach($adminItamRole->id, ['subsystem_id' => $subsystemItam->id]);
        $userJuan->allRoles()->attach($tecnicoHelpdeskRole->id, ['subsystem_id' => $subsystemHelpdesk->id]);

        // Maria Gomez (Usuario ordinario de Rentas)
        $userMaria = User::create([
            'name' => 'Maria Gomez',
            'email' => 'mgomez@sigm.gob.pe',
            'password' => Hash::make('password'),
            'document_type_id' => $dni->id,
            'document_number' => '44556688',
            'labor_condition_id' => $cas->id,
            'office_id' => $rentasOffice->id,
            'is_active' => true,
        ]);

        // Asignar roles a Maria Gomez
        $userMaria->allRoles()->attach($usuarioHelpdeskRole->id, ['subsystem_id' => $subsystemHelpdesk->id]);
        $userMaria->allRoles()->attach($operadorRentasRole->id, ['subsystem_id' => $subsystemRentas->id]);

        // 8. Seeding para ITAM (Equipos y Software)
        $laptop = Asset::create([
            'asset_code' => 'PAT-2026-0001',
            'category' => 'Laptop',
            'brand' => 'Lenovo',
            'model' => 'ThinkPad L14 Gen 4',
            'serial_number' => 'L3N0V014G4',
            'processor' => 'AMD Ryzen 5 7530U',
            'ram' => '16 GB DDR4',
            'storage' => '512 GB PCIe NVMe',
            'ip_address' => '192.168.10.120',
            'mac_address' => '00:1A:2B:3C:4D:5E',
            'status' => 'Asignado',
            'purchase_date' => '2026-01-15',
            'warranty_expiration' => '2029-01-15',
            'notes' => 'Entregado a María Gomez en perfecto estado.',
        ]);

        $pc = Asset::create([
            'asset_code' => 'PAT-2026-0002',
            'category' => 'PC',
            'brand' => 'HP',
            'model' => 'ProDesk 400 G9 SFF',
            'serial_number' => 'HPP400G9SFF',
            'processor' => 'Intel Core i5-12500',
            'ram' => '8 GB DDR4',
            'storage' => '256 GB SSD',
            'ip_address' => '192.168.10.121',
            'mac_address' => '00:1A:2B:3C:4D:5F',
            'status' => 'Disponible',
            'purchase_date' => '2026-02-20',
            'warranty_expiration' => '2028-02-20',
            'notes' => 'Para asignación temporal.',
        ]);

        $win11 = Software::create([
            'name' => 'Windows 11 Professional',
            'version' => '23H2',
            'license_type' => 'OEM',
            'license_key' => 'XXXXX-XXXXX-XXXXX-XXXXX-XXXXX',
            'max_activations' => 1,
        ]);

        $office365 = Software::create([
            'name' => 'Microsoft 365 Business Standard',
            'version' => 'Cloud',
            'license_type' => 'Suscripción',
            'license_key' => 'Licencia vinculada a cuenta institucional',
            'expiration_date' => '2027-01-01',
            'max_activations' => 50,
        ]);

        $laptop->softwares()->attach($win11->id, ['installed_at' => '2026-01-16']);
        $laptop->softwares()->attach($office365->id, ['installed_at' => '2026-01-16']);

        // 9. Seeding para HELPDESK (Categorías y Tickets)
        $catRed = TicketCategory::create([
            'name' => 'Red y Conectividad',
            'description' => 'Problemas de internet, cables de red, wifi y telefonía',
            'sla_hours' => 2,
        ]);

        $catSistemas = TicketCategory::create([
            'name' => 'Sistemas Municipales',
            'description' => 'Fallas en el SIGM u otros aplicativos del municipio',
            'sla_hours' => 4,
        ]);

        $catHardware = TicketCategory::create([
            'name' => 'Hardware y Computadores',
            'description' => 'Equipos que no encienden, problemas de monitor, teclado, mouse',
            'sla_hours' => 8,
        ]);

        // Registrar un ticket de ejemplo
        Ticket::create([
            'category_id' => $catRed->id,
            'requester_id' => $userMaria->id,
            'office_id' => $rentasOffice->id,
            'title' => 'Sin conexión al servidor tributario',
            'description' => 'No puedo ingresar al módulo de recaudación. Sale error de tiempo de espera agotado. Mis compañeros sí tienen internet pero no pueden abrir el sistema.',
            'priority' => 'Alta',
            'status' => 'Abierto',
        ]);
    }
}
