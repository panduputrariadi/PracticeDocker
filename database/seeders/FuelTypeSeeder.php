<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fuelTypes = [
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Gasolina',
                'description' => 'Combustible de origen petróleo refinado.',
                'slug' => Str::slug('Gasolina')
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Diesel',
                'description' => 'Combustible de origen petróleo refinado.',
                'slug' => Str::slug('Diesel')
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Electrico',
                'description' => 'Combustible de origen petróleo refinado.',
                'slug' => Str::slug('Electrico')
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Hibrido',
                'description' => 'Combustible de origen petróleo refinado.',
                'slug' => Str::slug('Hibrido')
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Gas',
                'description' => 'Combustible de origen petróleo refinado.',
                'slug' => Str::slug('Gas')
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Biodiesel',
                'description' => 'Combustible de origen petróleo refinado.',
                'slug' => StR::slug('Biodiesel')
            ]
        ];

        DB::table('fuel_types')->insert($fuelTypes);
    }
}
