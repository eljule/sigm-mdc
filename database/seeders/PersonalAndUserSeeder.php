<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Office;
use App\Models\Personal;
use App\Models\Role;
use App\Models\Subsystem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PersonalAndUserSeeder extends Seeder
{
    /**
     * Seed RRHH Personals, System Users, and Subsystem Role Assignments.
     */
    public function run(): void
    {
        // Oficinas clave
        $odtOffice = Office::where('acronym', 'ODT')->orWhere('code', '03.02.05')->first();
        $gatOffice = Office::where('acronym', 'GAT')->orWhere('code', '04')->first();

        $odtOfficeId = $odtOffice?->id ?? 19;
        $gatOfficeId = $gatOffice?->id ?? 26;

        // Subsistemas
        $subCentral     = Subsystem::where('code', 'central')->first();
        $subTransportes = Subsystem::where('code', 'transportes')->first();
        $subItam        = Subsystem::where('code', 'itam')->first();
        $subHelpdesk    = Subsystem::where('code', 'helpdesk')->first();

        // 1. Fichas de Personal (RRHH)
        $personalsData = [
            [
                'id' => 1,
                'document_type_id' => 1,
                'document_number' => '10783864',
                'first_name' => 'jaime henrry',
                'paternal_surname' => 'sandoval',
                'maternal_surname' => 'nieves',
                'full_name' => 'jaime henrry sandoval nieves',
                'gender' => 'masculino',
                'email' => 'jsandoval@municastilla.gob.pe',
                'labor_condition_id' => 3,
                'office_id' => $odtOfficeId,
                'position' => 'jefe de la oficina de desarrollo tecnologico',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'document_type_id' => 1,
                'document_number' => '47806904',
                'first_name' => 'fiorella denisse',
                'paternal_surname' => 'ruiz',
                'maternal_surname' => 'senmache',
                'full_name' => 'fiorella denisse ruiz senmache',
                'gender' => 'femenino',
                'email' => 'fruiz@municastilla.gob.pe',
                'labor_condition_id' => 3,
                'office_id' => $gatOfficeId,
                'position' => 'gerente de administracion tributaria',
                'is_active' => false,
            ],
            [
                'id' => 5,
                'document_type_id' => 1,
                'document_number' => '42747895',
                'first_name' => 'cinthya katty',
                'paternal_surname' => 'ferro',
                'maternal_surname' => 'columbus',
                'full_name' => 'cinthya katty ferro columbus',
                'gender' => 'femenino',
                'email' => null,
                'labor_condition_id' => 2,
                'office_id' => $gatOfficeId,
                'position' => 'gerente de administracion tributaria',
                'is_active' => true,
            ],
        ];

        foreach ($personalsData as $pData) {
            Personal::updateOrCreate(['id' => $pData['id']], $pData);
        }

        // 2. Usuarios del Sistema
        $usersData = [
            [
                'id' => 1,
                'username' => 'jsandoval',
                'name' => 'jaime henrry sandoval nieves',
                'email' => null,
                'password' => Hash::make('password'),
                'personal_id' => 1,
                'document_type_id' => 1,
                'document_number' => '10783864',
                'labor_condition_id' => 1,
                'office_id' => $odtOfficeId,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'username' => 'fruiz',
                'name' => 'fiorella denisse ruiz senmache',
                'email' => null,
                'password' => Hash::make('password'),
                'personal_id' => 2,
                'document_type_id' => 1,
                'document_number' => '47806904',
                'labor_condition_id' => 1,
                'office_id' => $gatOfficeId,
                'is_active' => false,
            ],
            [
                'id' => 5,
                'username' => 'cferro',
                'name' => 'cinthya katty ferro columbus',
                'email' => null,
                'password' => Hash::make('password'),
                'personal_id' => 5,
                'document_type_id' => 1,
                'document_number' => '42747895',
                'labor_condition_id' => 2,
                'office_id' => $gatOfficeId,
                'is_active' => true,
            ],
            [
                'id' => 6,
                'username' => 'tsoporte1',
                'name' => 'jaime henrry sandoval nieves',
                'email' => 'jsandoval@municastilla.gob.pe',
                'password' => Hash::make('password'),
                'personal_id' => 1,
                'document_type_id' => 1,
                'document_number' => '10783864',
                'labor_condition_id' => 3,
                'office_id' => $odtOfficeId,
                'is_active' => true,
            ],
            [
                'id' => 7,
                'username' => 'tsoporte2',
                'name' => 'jaime henrry sandoval nieves',
                'email' => null,
                'password' => Hash::make('password'),
                'personal_id' => 1,
                'document_type_id' => 1,
                'document_number' => '10783864',
                'labor_condition_id' => 3,
                'office_id' => $odtOfficeId,
                'is_active' => true,
            ],
        ];

        foreach ($usersData as $uData) {
            User::updateOrCreate(['id' => $uData['id']], $uData);
        }

        // 3. Asignación de Roles por Subsistema (model_has_roles)
        $userRoleAssignments = [
            // User 1 (jsandoval): Admin Central, Admin TI, Admin Helpdesk, Tecnico Helpdesk
            1 => [
                ['role_name' => 'Administrador Central', 'subsystem_id' => $subCentral?->id ?? 1],
                ['role_name' => 'Administrador de TI', 'subsystem_id' => $subItam?->id ?? 3],
                ['role_name' => 'Administrador de Helpdesk', 'subsystem_id' => $subHelpdesk?->id ?? 4],
                ['role_name' => 'Tecnico de Soporte', 'subsystem_id' => $subHelpdesk?->id ?? 4],
            ],
            // User 2 (fruiz): Usuario Reportante Helpdesk, Operador de Transportes
            2 => [
                ['role_name' => 'Usuario Reportante', 'subsystem_id' => $subHelpdesk?->id ?? 4],
                ['role_name' => 'Operador de Transportes', 'subsystem_id' => $subTransportes?->id ?? 2],
            ],
            // User 5 (cferro): Usuario Reportante Helpdesk
            5 => [
                ['role_name' => 'Usuario Reportante', 'subsystem_id' => $subHelpdesk?->id ?? 4],
            ],
            // User 6 (tsoporte1): Admin-Soporte en Helpdesk, admin-soporte en ITAM
            6 => [
                ['role_name' => 'Admin-Soporte', 'subsystem_id' => $subHelpdesk?->id ?? 4],
                ['role_name' => 'admin-soporte', 'subsystem_id' => $subItam?->id ?? 3],
            ],
            // User 7 (tsoporte2): Tecnico de Soporte en Helpdesk, Tecnico de soporte en ITAM
            7 => [
                ['role_name' => 'Tecnico de Soporte', 'subsystem_id' => $subHelpdesk?->id ?? 4],
                ['role_name' => 'Tecnico de soporte', 'subsystem_id' => $subItam?->id ?? 3],
            ],
        ];

        foreach ($userRoleAssignments as $userId => $assignments) {
            $user = User::find($userId);
            if (! $user) {
                continue;
            }
            foreach ($assignments as $assignment) {
                $role = Role::where('name', $assignment['role_name'])
                    ->where('subsystem_id', $assignment['subsystem_id'])
                    ->first();
                if ($role) {
                    $user->allRoles()->syncWithoutDetaching([
                        $role->id => ['subsystem_id' => $assignment['subsystem_id']]
                    ]);
                }
            }
        }
    }
}
