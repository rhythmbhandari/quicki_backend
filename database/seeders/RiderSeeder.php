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
            'device_token'=>'fAoy8lrNQ1KRB14rcbULyX:APA91bEM1WWaHwC0W1Xdlqh3mnk_jxMp6Bvm0X9cXfkJE1PJp1XpzuJUHbQRXT0mVttlVN3dqPLI4HaYqrnIRtHwzbGUk48OD_1Nk6cIQ-5mKYfSS8Xby9g7re6fzhS5BFyqmyzdWAht'
        ]);
    }
}
