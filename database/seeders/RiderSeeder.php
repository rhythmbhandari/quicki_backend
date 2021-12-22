<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Models\Rider;
use Carbon\Carbon;

class RiderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rider::create([
            'user_id' => '2',
            'experience' => '3',
            'trained'   => 'YES',
            'status'    => 'active',
            'approved_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
