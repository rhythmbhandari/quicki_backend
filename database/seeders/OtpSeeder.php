<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Modules\Models\Otp;

class OtpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Otp::create([
            'phone'=>'9816810976',
            'code'=>'1111',
            'code_status'=>'pending',
        ]);

        Otp::create([
            'phone'=>'9862170927',
            'code'=>'1111',
            'code_status'=>'pending',
        ]);

        Otp::create([
            'phone'=>'9869191572',
            'code'=>'1111',
            'code_status'=>'pending',
        ]);
    }
}
