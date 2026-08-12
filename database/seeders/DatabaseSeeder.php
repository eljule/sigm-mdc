<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\DocumentType;
use App\Models\LaborCondition;
use App\Models\Office;
use App\Models\Permission;
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
            'code' => '200104',
            'department' => 'PIURA',
            'province' => 'PIURA',
            'district' => 'CASTILLA',
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

        $locador = LaborCondition::create([
            'code' => 'LOC',
            'name' => 'Locador',
            'is_active' => true,
        ]);

        // 4. Estructura de Oficinas
        $alcaldia = Office::create([
            'parent_id' => null,
            'code' => '02',
            'name' => 'Alcaldia',
            'acronym' => 'GM',
            'is_active' => true,
        ]);

        $gerencia = Office::create([
            'parent_id' => null,
            'code' => '02.01',
            'name' => 'Gerencia Municipal',
            'acronym' => 'GM',
            'is_active' => true,
        ]);

        $secretariaOffice = Office::create([
            'parent_id' => $gerencia->id,
            'code' => '02.01.01',
            'name' => 'Secretaría General',
            'acronym' => 'SG',
            'is_active' => true,
        ]);
        
        $adminOffice = Office::create([
            'parent_id' => $gerencia->id,
            'code' => '02.01.02',
            'name' => 'Oficina General de Administración y Finanzas',
            'acronym' => 'OGAF',
            'is_active' => true,
        ]);
        
        $rentasOffice = Office::create([
            'parent_id' => $gerencia->id,
            'code' => '02.01.05',
            'name' => 'Gerencia de Administración Tributaria',
            'acronym' => 'GAT',
            'is_active' => true,
        ]);

        $sistemasOffice = Office::create([
            'parent_id' => $adminOffice->id,
            'code' => '02.01.02.05',
            'name' => 'Oficina de Desarrollo Tecnológico',
            'acronym' => 'ODT',
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

        // 6. Roles y Permisos Específicos por Subsistema
        $this->call(RoleAndPermissionSeeder::class);

        $adminCentralRole     = Role::where('name', 'Administrador Central')->first();
        $operadorRentasRole   = Role::where('name', 'Operador de Rentas')->first();
        $adminItamRole        = Role::where('name', 'Administrador de TI')->first();
        $adminHelpdeskRole    = Role::where('name', 'Administrador de Helpdesk')->first();
        $tecnicoHelpdeskRole  = Role::where('name', 'Técnico de Soporte')->first();
        $usuarioHelpdeskRole  = Role::where('name', 'Usuario Reportante')->first();

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

         // Carlos Mendoza (Usuario ordinario de Rentas)
        $userCarlos = User::create([
            'name' => 'Carlos Mendoza',
            'email' => 'cmendoza@sigm.gob.pe',
            'password' => Hash::make('password'),
            'document_type_id' => $dni->id,
            'document_number' => '44679876',
            'labor_condition_id' => $cas->id,
            'office_id' => $secretariaOffice->id,
            'is_active' => true,
        ]);

        // Asignar roles a Carlos Mendoza
        $userCarlos->allRoles()->attach($usuarioHelpdeskRole->id, ['subsystem_id' => $subsystemHelpdesk->id]);

        // Josue Espinoza (Usuario soporte tecnico)
        $userJosue = User::create([
            'name' => 'Josue Espinoza',
            'email' => 'jespinoza@sigm.gob.pe',
            'password' => Hash::make('password'),
            'document_type_id' => $dni->id,
            'document_number' => '44889977',
            'labor_condition_id' => $locador->id,
            'office_id' => $sistemasOffice->id,
            'is_active' => true,
        ]);

        // Asignar roles a Josue Espinoza
        $userJosue->allRoles()->attach($tecnicoHelpdeskRole->id, ['subsystem_id' => $subsystemHelpdesk->id]);

        // 8. Seeding para ITAM (Equipos y Software)
        $brandHP = \App\Models\AssetBrand::create(['name' => 'HP', 'description' => 'Hewlett-Packard']);
        $brandLenovo = \App\Models\AssetBrand::create(['name' => 'Lenovo', 'description' => 'Lenovo Group']);

        $modelThinkPad = \App\Models\AssetModel::create([
            'asset_brand_id' => $brandLenovo->id,
            'name' => 'ThinkPad L14 Gen 4',
            'description' => 'Laptop corporativa'
        ]);
        $modelProDesk = \App\Models\AssetModel::create([
            'asset_brand_id' => $brandHP->id,
            'name' => 'ProDesk 400 G9 SFF',
            'description' => 'Computadora de escritorio SFF'
        ]);
        $modelKeyboard = \App\Models\AssetModel::create([
            'asset_brand_id' => $brandHP->id,
            'name' => 'Keyboard 150 USB',
            'description' => 'Teclado USB de oficina'
        ]);
        $modelMouse = \App\Models\AssetModel::create([
            'asset_brand_id' => $brandHP->id,
            'name' => 'Mouse 150 USB',
            'description' => 'Mouse USB óptico'
        ]);
        $modelMonitor = \App\Models\AssetModel::create([
            'asset_brand_id' => $brandHP->id,
            'name' => 'P24h G5 FHD',
            'description' => 'Monitor de 24 pulgadas FHD'
        ]);

        $catLaptop = \App\Models\AssetCategory::create(['name' => 'Laptop', 'description' => 'Laptops y computadoras portátiles']);
        $catPC = \App\Models\AssetCategory::create(['name' => 'PC de Escritorio', 'description' => 'Computadoras de escritorio']);
        $catTeclado = \App\Models\AssetCategory::create(['name' => 'Teclado', 'description' => 'Teclados de computadora']);
        $catMouse = \App\Models\AssetCategory::create(['name' => 'Mouse', 'description' => 'Mouses / Ratones ópticos']);
        $catMonitor = \App\Models\AssetCategory::create(['name' => 'Monitor', 'description' => 'Monitores y pantallas']);

        // Bloques y características para PC
        $blockSpecsPC = \App\Models\AssetBlock::create([
            'asset_category_id' => $catPC->id,
            'name' => 'Especificaciones Técnicas',
            'sort_order' => 1
        ]);
        $blockNetPC = \App\Models\AssetBlock::create([
            'asset_category_id' => $catPC->id,
            'name' => 'Red y Estado',
            'sort_order' => 2
        ]);

        $charProcessorPC = \App\Models\AssetCharacteristic::create([
            'asset_block_id' => $blockSpecsPC->id,
            'name' => 'Procesador',
            'type' => 'text',
            'is_required' => false,
            'sort_order' => 1
        ]);
        $charRamPC = \App\Models\AssetCharacteristic::create([
            'asset_block_id' => $blockSpecsPC->id,
            'name' => 'Memoria RAM',
            'type' => 'text',
            'is_required' => false,
            'sort_order' => 2
        ]);
        $charStoragePC = \App\Models\AssetCharacteristic::create([
            'asset_block_id' => $blockSpecsPC->id,
            'name' => 'Almacenamiento',
            'type' => 'text',
            'is_required' => false,
            'sort_order' => 3
        ]);

        $charIpPC = \App\Models\AssetCharacteristic::create([
            'asset_block_id' => $blockNetPC->id,
            'name' => 'Dirección IP',
            'type' => 'text',
            'is_required' => false,
            'sort_order' => 1
        ]);
        $charMacPC = \App\Models\AssetCharacteristic::create([
            'asset_block_id' => $blockNetPC->id,
            'name' => 'Dirección MAC',
            'type' => 'text',
            'is_required' => false,
            'sort_order' => 2
        ]);

        // Bloques y características para Laptop
        $blockSpecsLaptop = \App\Models\AssetBlock::create([
            'asset_category_id' => $catLaptop->id,
            'name' => 'Especificaciones Técnicas',
            'sort_order' => 1
        ]);
        $blockNetLaptop = \App\Models\AssetBlock::create([
            'asset_category_id' => $catLaptop->id,
            'name' => 'Red y Estado',
            'sort_order' => 2
        ]);

        $charProcessorLap = \App\Models\AssetCharacteristic::create([
            'asset_block_id' => $blockSpecsLaptop->id,
            'name' => 'Procesador',
            'type' => 'text',
            'is_required' => false,
            'sort_order' => 1
        ]);
        $charRamLap = \App\Models\AssetCharacteristic::create([
            'asset_block_id' => $blockSpecsLaptop->id,
            'name' => 'Memoria RAM',
            'type' => 'text',
            'is_required' => false,
            'sort_order' => 2
        ]);
        $charStorageLap = \App\Models\AssetCharacteristic::create([
            'asset_block_id' => $blockSpecsLaptop->id,
            'name' => 'Almacenamiento',
            'type' => 'text',
            'is_required' => false,
            'sort_order' => 3
        ]);

        $charIpLap = \App\Models\AssetCharacteristic::create([
            'asset_block_id' => $blockNetLaptop->id,
            'name' => 'Dirección IP',
            'type' => 'text',
            'is_required' => false,
            'sort_order' => 1
        ]);
        $charMacLap = \App\Models\AssetCharacteristic::create([
            'asset_block_id' => $blockNetLaptop->id,
            'name' => 'Dirección MAC',
            'type' => 'text',
            'is_required' => false,
            'sort_order' => 2
        ]);

        // Bloques y características para Monitor
        $blockSpecsMonitor = \App\Models\AssetBlock::create([
            'asset_category_id' => $catMonitor->id,
            'name' => 'Detalles de Pantalla',
            'sort_order' => 1
        ]);
        $charSizeMonitor = \App\Models\AssetCharacteristic::create([
            'asset_block_id' => $blockSpecsMonitor->id,
            'name' => 'Resolución y Tamaño',
            'type' => 'text',
            'is_required' => false,
            'sort_order' => 1
        ]);

        // Creación de activos con FK de categoría y modelo
        $laptop = Asset::create([
            'asset_category_id' => $catLaptop->id,
            'asset_model_id' => $modelThinkPad->id,
            'asset_code' => 'PAT-2026-0001',
            'computer_code' => 'COD-TI-0001',
            'serial_number' => 'L3N0V014G4',
            'status' => 'Asignado',
            'purchase_date' => '2026-01-15',
            'warranty_expiration' => '2029-01-15',
            'notes' => 'Entregado a María Gomez en perfecto estado.',
        ]);

        \App\Models\AssetCharacteristicValue::create(['asset_id' => $laptop->id, 'asset_characteristic_id' => $charProcessorLap->id, 'value' => 'AMD Ryzen 5 7530U']);
        \App\Models\AssetCharacteristicValue::create(['asset_id' => $laptop->id, 'asset_characteristic_id' => $charRamLap->id, 'value' => '16 GB DDR4']);
        \App\Models\AssetCharacteristicValue::create(['asset_id' => $laptop->id, 'asset_characteristic_id' => $charStorageLap->id, 'value' => '512 GB PCIe NVMe']);
        \App\Models\AssetCharacteristicValue::create(['asset_id' => $laptop->id, 'asset_characteristic_id' => $charIpLap->id, 'value' => '192.168.10.120']);
        \App\Models\AssetCharacteristicValue::create(['asset_id' => $laptop->id, 'asset_characteristic_id' => $charMacLap->id, 'value' => '00:1A:2B:3C:4D:5E']);

        $pc = Asset::create([
            'asset_category_id' => $catPC->id,
            'asset_model_id' => $modelProDesk->id,
            'asset_code' => 'PAT-2026-0002',
            'computer_code' => 'COD-TI-0002',
            'serial_number' => 'HPP400G9SFF',
            'status' => 'Disponible',
            'purchase_date' => '2026-02-20',
            'warranty_expiration' => '2028-02-20',
            'notes' => 'Para asignación temporal.',
        ]);

        \App\Models\AssetCharacteristicValue::create(['asset_id' => $pc->id, 'asset_characteristic_id' => $charProcessorPC->id, 'value' => 'Intel Core i5-12500']);
        \App\Models\AssetCharacteristicValue::create(['asset_id' => $pc->id, 'asset_characteristic_id' => $charRamPC->id, 'value' => '8 GB DDR4']);
        \App\Models\AssetCharacteristicValue::create(['asset_id' => $pc->id, 'asset_characteristic_id' => $charStoragePC->id, 'value' => '256 GB SSD']);
        \App\Models\AssetCharacteristicValue::create(['asset_id' => $pc->id, 'asset_characteristic_id' => $charIpPC->id, 'value' => '192.168.10.121']);
        \App\Models\AssetCharacteristicValue::create(['asset_id' => $pc->id, 'asset_characteristic_id' => $charMacPC->id, 'value' => '00:1A:2B:3C:4D:5F']);

        $keyboard = Asset::create([
            'parent_id' => $pc->id,
            'asset_category_id' => $catTeclado->id,
            'asset_model_id' => $modelKeyboard->id,
            'asset_code' => 'PAT-2026-0003',
            'computer_code' => 'COD-TI-0003',
            'serial_number' => 'HPKB150USB',
            'status' => 'Disponible',
            'notes' => 'Teclado USB estándar de la PC principal.',
        ]);

        $mouse = Asset::create([
            'parent_id' => $pc->id,
            'asset_category_id' => $catMouse->id,
            'asset_model_id' => $modelMouse->id,
            'asset_code' => 'PAT-2026-0004',
            'computer_code' => 'COD-TI-0004',
            'serial_number' => 'HPMS150USB',
            'status' => 'Disponible',
            'notes' => 'Mouse óptico USB estándar de la PC principal.',
        ]);

        $monitor = Asset::create([
            'parent_id' => $pc->id,
            'asset_category_id' => $catMonitor->id,
            'asset_model_id' => $modelMonitor->id,
            'asset_code' => 'PAT-2026-0005',
            'computer_code' => 'COD-TI-0005',
            'serial_number' => 'HPMON24G5',
            'status' => 'Disponible',
            'notes' => 'Monitor FHD de 24 pulgadas.',
        ]);
        \App\Models\AssetCharacteristicValue::create(['asset_id' => $monitor->id, 'asset_characteristic_id' => $charSizeMonitor->id, 'value' => '1920x1080 @ 75Hz, 23.8"']);

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

        // Registrar la asignación correspondiente para el activo que tiene estado 'Asignado'
        \App\Models\AssetAssignment::create([
            'asset_id' => $laptop->id,
            'user_id' => $userMaria->id,
            'office_id' => $rentasOffice->id,
            'assigned_at' => '2026-01-16 09:00:00',
            'notes' => 'Entregado a María Gomez en perfecto estado.',
        ]);

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

        // 10. Seeding de Insumos y Consumibles (ITAM)
        $vga = \App\Models\Consumable::create([
            'name' => 'Cable VGA 1.8m',
            'stock' => 15,
            'unit' => 'Unidades',
            'min_stock' => 3,
        ]);

        $cat6 = \App\Models\Consumable::create([
            'name' => 'Cable de Red Cat6 3m',
            'stock' => 40,
            'unit' => 'Unidades',
            'min_stock' => 5,
        ]);

        $rj45 = \App\Models\Consumable::create([
            'name' => 'Conector RJ45 Amp',
            'stock' => 100,
            'unit' => 'Unidades',
            'min_stock' => 10,
        ]);

        $toner = \App\Models\Consumable::create([
            'name' => 'Tóner HP LaserJet 85A',
            'stock' => 8,
            'unit' => 'Unidades',
            'min_stock' => 2,
        ]);

        // Registrar un ticket de ejemplo
        Ticket::create([
            'category_id' => $catRed->id,
            'user_category' => 'Red/Internet',
            'requester_id' => $userMaria->id,
            'office_id' => $rentasOffice->id,
            'title' => 'Sin conexión al servidor tributario',
            'description' => 'No puedo ingresar al módulo de recaudación. Sale error de tiempo de espera agotado. Mis compañeros sí tienen internet pero no pueden abrir el sistema.',
            'impact' => 'Critico',
            'priority' => 'Alta',
            'status' => 'Abierto',
        ]);
    }
}
