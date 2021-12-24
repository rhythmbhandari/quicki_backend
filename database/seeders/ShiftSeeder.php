<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Modules\Models\Shift;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shift::create([
            'title' => 'evening',
            'time_from' => 17,
            'time_to' => 20,
            'rate' => 1.2,
            'vehicle_type_id' => 1,
            'status'=>'active'
        ]);
    }
}
