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
            'capacity' => 1,
            'status' => 'active',
            'commission' => 15,
            
            'base_fare' => 30,
            'base_covered_km' => 2,
            'base_covers_duration' => 'yes',

            'price_km' => 17,
            'price_min' => 1,
            'min_charge' => 50,

            'default_surge_rate' => 1.2,
            'min_surge_customers' => 30,
            'surge_rates' => [
                1 => 1.2,
                2 => 1.5,
                3 => 2
            ]
        ]);

        VehicleType::create([
            'name' => 'car',
            'capacity' => 3,
            'status' => 'active',
            'commission' => 35,
            
            'base_fare' => 100,
            'base_covered_km' => 2,
            'base_covers_duration' => 'no',

            'price_km' => 35,
            'price_min' => 2,
            'min_charge' => 150,

            
            'default_surge_rate' => 1.2,
            'min_surge_customers' => 40,
            'surge_rates' => [
                1 => 1.2,
                2 => 1.5,
                3 => 2
            ]
        ]);

        VehicleType::create([
            'name' => 'city_safari',
            'capacity' => 6,
            'status' => 'active',
            'commission' => 25,
            
            'base_fare' => 10,
            'base_covered_km' => 1,
            'base_covers_duration' => 'no',
            
            'price_km' =>10,
            'price_min' => 2,
            'min_charge' => 20,

            'default_surge_rate' => 1.2,
            'min_surge_customers' => 50,
            // 'surge_level' => 3,
            'surge_rates' => [
                1 => 1.2,
                2 => 1.5,
                3 => 2
            ]
        ]);

        VehicleType::create([
            'name' => 'ambulance',
            'capacity' => 6,
            'status' => 'active',
            'commission' => 0,
            
            'base_fare' => 0,
            'base_covered_km' => 0,
            'base_covers_duration' => 'no',
            
            'price_km' =>0,
            'price_min' => 0,
            'min_charge' => 0,

            'default_surge_rate' => 0,
            'min_surge_customers' => 50,
            // 'surge_level' => 3,
            'surge_rates' => null
        ]);

    }
}
