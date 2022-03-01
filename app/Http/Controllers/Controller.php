<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

//models
use App\Modules\Models\User;
use App\Modules\Models\Role;
use App\Modules\Models\Permission;
class Controller extends BaseController
{
    /**
     * @OA\Info(
     *      version="2.0.0",
     *      title="Quicki V2 API",
     *      description="Quicki APIs documentation"
     * )
     * @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      in="header",
     *      name="bearerAuth",
     *      type="http",
     *      scheme="bearer",
     *      bearerFormat="JWT",
     * ),
     *
     **/
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test()
    {
      Role::findOrFail(7);
        $roles = Role::all();
      //  Permission::create(['name' => 'add userss','guard_name'=>'admin']);
        $role = $roles[0]->givePermissionTo('add users');
        $users = User::all();
        $permissions = Permission::all();
        return view('test',compact('roles','permissions','users'));

    }

}
