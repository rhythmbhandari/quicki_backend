<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('permissions')->delete();
        DB::table('permissions')->insert(
            array(
                //booking
                array(
                    'name' => 'booking-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'booking-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'booking-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'booking-delete',
                    'guard_name' => 'admin',
                ),
                //booking log
                array(
                    'name' => 'bookingLog-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'bookingLog-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'bookingLog-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'bookingLog-delete',
                    'guard_name' => 'admin',
                ),
                //booking booking transaction
                array(
                    'name' => 'bookingTransaction-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'bookingTransaction-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'bookingTransaction-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'bookingTransaction-delete',
                    'guard_name' => 'admin',
                ),
                //booking customer
                array(
                    'name' => 'customer-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'customer-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'customer-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'customer-delete',
                    'guard_name' => 'admin',
                ),
                //booking permission
                array(
                    'name' => 'permission-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'permission-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'permission-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'permission-delete',
                    'guard_name' => 'admin',
                ),
                //booking promos varient
                // array(
                //     'name' => 'promosVarient-view',
                //     'guard_name' => 'admin',
                // ),
                // array(
                //     'name' => 'promosVarient-add',
                //     'guard_name' => 'admin',
                // ),
                // array(
                //     'name' => 'promosVarient-edit',
                //     'guard_name' => 'admin',
                // ),
                // array(
                //     'name' => 'promosVarient-delete',
                //     'guard_name' => 'admin',
                // ),
                //role 
                array(
                    'name' => 'role-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'role-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'role-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'role-delete',
                    'guard_name' => 'admin',
                ),
                //booking sos event
                array(
                    'name' => 'sosEvent-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'sosEvent-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'sosEvent-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'sosEvent-delete',
                    'guard_name' => 'admin',
                ),
                //booking transaction
                array(
                    'name' => 'transaction-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'transaction-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'transaction-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'transaction-delete',
                    'guard_name' => 'admin',
                ),
                //booking transaction Type
                array(
                    'name' => 'transactionType-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'transactionType-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'transactionType-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'transactionType-delete',
                    'guard_name' => 'admin',
                ),
                //booking transaction flow
                array(
                    'name' => 'transactionFlow-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'transactionFlow-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'transactionFlow-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'transactionFlow-delete',
                    'guard_name' => 'admin',
                ),
                //booking user
                array(
                    'name' => 'user-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'user-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'user-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'user-delete',
                    'guard_name' => 'admin',
                ),
                //booking vehicle
                array(
                    'name' => 'vehicle-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicle-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicle-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicle-delete',
                    'guard_name' => 'admin',
                ),
                //booking vehicle document
                // array(
                //     'name' => 'vehicleDocument-view',
                //     'guard_name' => 'admin',
                // ),
                // array(
                //     'name' => 'vehicleDocument-add',
                //     'guard_name' => 'admin',
                // ),
                // array(
                //     'name' => 'vehicleDocument-edit',
                //     'guard_name' => 'admin',
                // ),
                // array(
                //     'name' => 'vehicleDocument-delete',
                //     'guard_name' => 'admin',
                // ),
                //booking vehicle manufacturer
                array(
                    'name' => 'vehicleManufacturer-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicleManufacturer-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicleManufacturer-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicleManufacturer-delete',
                    'guard_name' => 'admin',
                ),
                //booking vehicle model
                array(
                    'name' => 'vehicleModel-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicleModel-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicleModel-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicleModel-delete',
                    'guard_name' => 'admin',
                ),
                //booking vehicle Type
                array(
                    'name' => 'vehicleType-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicleType-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicleType-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicleType-delete',
                    'guard_name' => 'admin',
                ),
                //booking vehicle chekclist
                array(
                    'name' => 'vehicleChecklist-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicleChecklist-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicleChecklist-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'vehicleChecklist-delete',
                    'guard_name' => 'admin',
                ),
                //Reports
                array(
                    'name' => 'report-view',
                    'guard_name' => 'admin',
                ),

                //Reports
                array(
                    'name' => 'activityLog-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'news-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'news-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'news-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'news-delete',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'review-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'review-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'review-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'review-delete',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'setting-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'setting-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'setting-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'setting-delete',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'subscription-view',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'subscription-add',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'subscription-edit',
                    'guard_name' => 'admin',
                ),
                array(
                    'name' => 'subscription-delete',
                    'guard_name' => 'admin',
                ),
            )
        );
    }
}
