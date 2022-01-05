<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Modules\Models\Subscriber;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subscriber::create([
            'email'=>'amit.karn98@gmail.com',
            'subscribed'=>true
        ]);

        Subscriber::create([
            'email'=>'bishant345@gmail.com',
            'subscribed'=>true
        ]);

    }
}
