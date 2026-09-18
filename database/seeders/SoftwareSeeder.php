<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Software;
use Illuminate\Database\Seeder;

class SoftwareSeeder extends Seeder
{
    /**
     * Seed institutional software catalog.
     */
    public function run(): void
    {
        $softwares = [
            ['id' => 1, 'name' => 'windows 11 professional', 'version' => '23h2', 'license_type' => 'oem', 'license_key' => 'xxxxx-xxxxx-xxxxx-xxxxx-xxxxx', 'expiration_date' => null, 'max_activations' => 1],
            ['id' => 2, 'name' => 'microsoft 365 business standard', 'version' => 'CLOUD', 'license_type' => 'suscripción', 'license_key' => 'LICENCIA VINCULADA A CUENTA INSTITUCIONAL', 'expiration_date' => '2027-01-01', 'max_activations' => 50],
            ['id' => 3, 'name' => 'windows 10 profesional', 'version' => '22h2', 'license_type' => 'volumen', 'license_key' => null, 'expiration_date' => null, 'max_activations' => 1],
            ['id' => 4, 'name' => 'windows 11 profesional', 'version' => '23h2', 'license_type' => 'volumen', 'license_key' => null, 'expiration_date' => null, 'max_activations' => 1],
        ];

        foreach ($softwares as $sw) {
            Software::updateOrCreate(['id' => $sw['id']], $sw);
        }
    }
}
