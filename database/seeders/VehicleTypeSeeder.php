<?php

namespace Database\Seeders;

use App\Modules\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VehicleType::create([
            'name' => 'bike',
            'price_km' => 15.0,
            'price_min' => 5.0,
            'base_fare' => 50.0,
            'commission' => 15.0,
            'capacity' => 1,
            'status' => 'active',
        ]);

        VehicleType::create([
            'name' => 'car',
            'price_km' => 40.0,
            'price_min' => 15.0,
            'base_fare' => 120.0,
            'commission' => 35.0,
            'capacity' => 3,
            'status' => 'active',
        ]);

        VehicleType::create([
            'name' => 'city_safari',
            'price_km' => 25.0,
            'price_min' => 10.0,
            'base_fare' => 80.0,
            'commission' => 25.0,
            'capacity' => 9,
            'status' => 'active',
        ]);

    }
}
