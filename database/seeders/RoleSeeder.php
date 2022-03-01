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
            'name' => 'admin',
        ]);

        //2
        Role::create([
            'name' => 'rider',
        ]);

        //3
        Role::create([
            'name' => 'customer',
        ]);

        //4
        Role::create([
            'name' => 'driver',
        ]);
    }
}
