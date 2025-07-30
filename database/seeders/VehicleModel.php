<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VehicleModel extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleModels = [
            [
                'id' => Str::uuid()->toString(),
                'model_name' => 'Tesla',
                'description' => 'Tesla, Inc., a pioneer in electric vehicles and sustainable energy.',
                'slug' => Str::slug('Tesla'),
            ],
            [
                'id' => Str::uuid()->toString(),
                'model_name' => 'Honda',
                'description' => 'Honda Motor Co., Ltd., renowned for fuel-efficient vehicles and advanced technology.',
                'slug' => Str::slug('Honda'),
            ],
            [
                'id' => Str::uuid()->toString(),
                'model_name' => 'Ford',
                'description' => 'Ford Motor Company, an American automaker famous for trucks and SUVs.',
                'slug' => Str::slug('Ford'),
            ],
            [
                'id' => Str::uuid()->toString(),
                'model_name' => 'Toyota',
                'description' => 'Toyota Motor Corporation, a leading Japanese automaker known for reliability and innovation.',
                'slug' => Str::slug('Toyota'),
            ],
        ];

        DB::table('vehicle_models')->insert($vehicleModels);
    }
}
