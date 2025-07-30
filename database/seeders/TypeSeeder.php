<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Sedan',
                'slug' => Str::slug('Sedan'),
                'description' => 'A four-door passenger car with a separate trunk for cargo, designed for comfort and efficiency. Ideal for daily commuting and small families.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'SUV',
                'slug' => Str::slug('SUV'),
                'description' => 'A versatile vehicle with higher ground clearance, often with four-wheel drive, suitable for on- and off-road driving. Offers ample seating and cargo space.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Truck',
                'slug' => Str::slug('Truck'),
                'description' => 'A vehicle designed for hauling and towing, featuring a cargo bed and robust build. Popular for construction, agriculture, and heavy-duty tasks.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Coupe',
                'slug' => Str::slug('Coupe'),
                'description' => 'A two-door car with a sporty design, often prioritizing style and performance over practicality. Typically has a sleek profile and limited rear seating.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Hatchback',
                'slug' => Str::slug('Hatchback'),
                'description' => 'A compact car with a rear door that opens to a shared passenger and cargo area, offering flexibility for small families or urban drivers.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Minivan',
                'slug' => Str::slug('Minivan'),
                'description' => 'A family-oriented vehicle with sliding doors, multiple seats, and ample cargo space, designed for comfort and convenience.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Convertible',
                'slug' => Str::slug('Convertible'),
                'description' => 'A car with a retractable roof (soft or hardtop), offering an open-air driving experience. Often associated with luxury or sporty aesthetics.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Electric Vehicle',
                'slug' => Str::slug('Electric Vehicle'),
                'description' => 'A vehicle powered entirely by electricity, stored in batteries, offering eco-friendly and quiet operation.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Hybrid',
                'slug' => Str::slug('Hybrid'),
                'description' => 'A vehicle combining a gasoline engine with an electric motor for improved fuel efficiency and reduced emissions.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'type_name' => 'Motorcycle',
                'slug' => Str::slug('Motorcycle'),
                'description' => 'A two-wheeled vehicle designed for speed, agility, or long-distance travel, appealing to enthusiasts and commuters.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('types')->insert($types);
    }
}
