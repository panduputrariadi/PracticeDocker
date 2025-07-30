<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'id' => Str::uuid()->toString(),
                'brand_name' => 'Toyota',
                'description' => 'Toyota Motor Corporation, a leading Japanese automaker known for reliability and innovation.',
                'slug' => Str::slug('Toyota'),
            ],
            [
                'id' => Str::uuid()->toString(),
                'brand_name' => 'Honda',
                'description' => 'Honda Motor Co., Ltd., renowned for fuel-efficient vehicles and advanced technology.',
                'slug' => Str::slug('Honda'),
            ],
            [
                'id' => Str::uuid()->toString(),
                'brand_name' => 'Ford',
                'description' => 'Ford Motor Company, an American automaker famous for trucks and SUVs.',
                'slug' => Str::slug('Ford'),
            ],
            [
                'id' => Str::uuid()->toString(),
                'brand_name' => 'Tesla',
                'description' => 'Tesla, Inc., a pioneer in electric vehicles and sustainable energy.',
                'slug' => Str::slug('Tesla'),
            ],
        ];

        DB::table('brands')->insert($brands);
    }
}
