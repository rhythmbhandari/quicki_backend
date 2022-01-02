<?php

namespace Database\Seeders;

use App\Modules\Models\Permission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
           // RoleSeeder::class,
           // UserSeeder::class,
           // RiderSeeder::class,
          //  RoleUserSeeder::class,
         
          //  OtpSeeder::class,
            // VehicleSeeder::class,


           //VehicleTypeSeeder::class,
          //In server, first seed vehicle type, then import users and then seed these
            SuggestionSeeder::class,
            PermissionSeeder::class,
            RoleHasPermissionSeeder::class,
     
            ShiftSeeder::class,
            PromotionVoucherSeeder::class,
        ]);
    }
}
