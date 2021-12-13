<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Modules\Models\Role;
use App\Modules\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permission.index', compact('permissions'));
    }

    /**
     * 
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        // dd($savedPermission);
        return view('admin.permission.edit', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // dd("test");
        // $this->validate(
        //     $request,
        //     [
        //         'name' => 'required|unique:roles',
        //         'permissions' => 'required',
        //     ]
        // );
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->save();
        if ($request->has('roles')) {
            $permission->roles()->attach($request->roles);
        }
        return redirect()->route('admin.permission.index')->with('success', 'Roles added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id); //Get role with the given id


        //Validate name and permission fields
        // $this->validate($request, [
        //     'name' => 'required',
        //     'permissions' => 'required',
        // ]);

        $request->validate([
            'name' => 'required'
        ]);

        // dd($request->all());
        $input = $request->except(['permissions']);
        $role->fill($input)->save();
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }




        return redirect()->route('admin.role.index')->with('success', 'Roles updated successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all(); //Get all permissions
        return view('admin.permission.create', compact('roles'));
    }

    public function getAllData()
    {
        $query = Permission::get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('roles', function (Permission $permission) {
                return str_replace(array('[', ']', '"'), '', $permission->roles()->pluck('name'));
            })
            ->editColumn('actions', function (Permission $permission) {
                $editRoute = route('admin.role.edit', $permission->id);
                $deleteRoute = '';
                $optionRoute = '';
                $optionRouteText = '';
                return getTableHtml($permission, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText);
            })->rawColumns(['actions'])
            ->make(true);
    }
}
