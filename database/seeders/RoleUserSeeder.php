<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Models\User;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::find(1)->assignRole('admin');
        User::find(2)->assignRole('customer');
        User::find(2)->assignRole('rider');
        User::find(3)->assignRole('customer');
        // RoleUser::create([
        //     'user_id'=>'1',
        //     'role_id'=>'1',
        // ]);
        // RoleUser::create([
        //     'user_id'=>'1',
        //     'role_id'=>'3',
        // ]);
        // RoleUser::create([
        //     'user_id'=>'2',
        //     'role_id'=>'2',
        // ]);
        // RoleUser::create([
        //     'user_id'=>'2',
        //     'role_id'=>'3',
        // ]);
        // RoleUser::create([
        //     'user_id'=>'3',
        //     'role_id'=>'3',
        // ]);
        // RoleUser::create([
        //     'user_id'=>'4',
        //     'role_id'=>'3',
        // ]);
    }
}
