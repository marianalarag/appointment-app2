<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodType;

class BloodTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir tipos de sangre
        $bloodTypes = [
            'A+',
            'A-',
            'B+',
            'B-',
            'AB+',
            'AB-',
            'O+',
            'O-',
        ];

        // Crear tipos de sangre solo si no existen
        foreach ($bloodTypes as $type) {
            BloodType::firstOrCreate([
                'name' => $type,
            ]);
        }
    }
}
