<?php

namespace Database\Seeders;

use App\Modules\Models\Permission;
use App\Modules\Models\Role;
use Illuminate\Database\Seeder;

class RoleHasPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //for superadmin role
        Role::first()->permissions()->sync(Permission::pluck('id'));


        // Role::skip(1)->first()->permissions()->sync(
        //     Permission::where('name', 'garage-view')
        //         ->orWhere('name', 'vehicle-view')
        //         ->orWhere('name', 'booking-view')
        //         ->orWhere('name', 'sos-view')
        //         ->get()->pluck('id')
        // );
        // Role::skip(1)->first()->permissions()->sync(Permission::where('name', 'vehicle-view')->get()->pluck('id'));
        // Role::skip(1)->first()->permissions()->sync(Permission::where('name', 'booking-view')->get()->pluck('id'));
        // Role::skip(1)->first()->permissions()->sync(Permission::where('name', 'sos-view')->get()->pluck('id'));
    }
}
