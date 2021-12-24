<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Modules\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vehicle::create(
            array(
                'rider_id' => 1,
                'vehicle_type_id' => 1,
                'model' => 'Sint aliquip proident fugiat ad velit ex.',
                'vehicle_number' => 'Ba 11 pa 1111',
                'make_year' => 2021,
                'vehicle_color' => 'black',
                'status' => 'active',
                'capacity' => 1
            )
        );
    }
}
