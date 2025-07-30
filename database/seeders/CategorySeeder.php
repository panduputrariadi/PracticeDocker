<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Family Car',
                'slug' => Str::slug('Family Car'),
                'description' => 'Vehicles for families',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Bike',
                'slug' => Str::slug('Motor'),
                'description' => 'Bike for ride',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Truck',
                'slug' => Str::slug('Truck'),
                'description' => 'Truck for hauling',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Car',
                'slug' => Str::slug('Car'),
                'description' => 'Car for daily commute',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
