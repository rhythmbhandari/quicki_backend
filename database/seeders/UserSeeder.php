<?php

namespace Database\Seeders;

use App\Modules\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //


        User::create([
            'first_name' => 'Sasuke',
            'middle_name' => '',
            'last_name' => 'Uchiha',
            'email' => 'sasuke@gmail.com',
            'phone' => '9862170927',
            'username' => 'sasuke',
            'emergency_contacts' => '{"9816810976","987654321","981122345"}',
            'password' => Hash::make('password'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'status' => 'active'
        ]);

        User::create([
            'first_name' => 'Naruto',
            'middle_name' => '',
            'last_name' => 'Uzumaki',
            'email' => 'naruto@gmail.com',
            'phone' => '9816810976',
            'username' => 'naruto',
            'password' => Hash::make('password'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'status' => 'active'
        ]);

        User::create([
            'first_name' => 'Kakashi',
            'middle_name' => '',
            'last_name' => 'Hatake',
            'email' => 'kakashi@gmail.com',
            'phone' => '9843936821',
            'username' => 'kakashi',
            'emergency_contacts' => '{"9816810976","987654321","981122345"}',
            'password' => Hash::make('password'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'status' => 'active'
        ]);

        User::create([
            'first_name' => 'Rythm',
            'middle_name' => '',
            'last_name' => 'Bhandari',
            'email' => 'rythm@gmail.com',
            'phone' => '9869191572',
            'username' => 'rythm',
            'emergency_contacts' => '{"9816810976","987654321","981122345"}',
            'password' => Hash::make('password'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'status' => 'active'
        ]);
    }
}
