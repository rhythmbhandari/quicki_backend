<?php

namespace Database\Seeders;

use App\Modules\Models\Role;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        Role::create([
            'name'=>'admin',
            'guard_name'=>'admin',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //2
        Role::create([
            'name'=>'rider',
            'guard_name'=>'rider',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //3
        Role::create([
            'name'=>'customer',
            'guard_name'=>'customer',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //4
        Role::create([
            'name'=>'driver',
            'guard_name'=>'driver',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }
}
