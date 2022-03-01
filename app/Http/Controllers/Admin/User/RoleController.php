<?php

namespace App\Http\Controllers\Admin\User;

use App\Modules\Models\Role;
use App\Modules\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.role.index', compact('roles'));
    }

    /**
     * 
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $savedPermission = $role->permissions->pluck('id')->toArray();
        // dd($savedPermission);
        return view('admin.role.edit', compact('role', 'permissions', 'savedPermission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|unique:roles',
                'permissions' => 'required',
            ]
        );
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        if ($request->permissions <> '') {
            $role->permissions()->attach($request->permissions);
        }
        return redirect()->route('admin.role.index')->with('success', 'Roles added successfully');
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
        $permissions = Permission::all(); //Get all permissions
        return view('admin.role.create', compact('permissions'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('admin.role.index')
            ->with(
                'success',
                'Role deleted successfully!'
            );
    }

    public function getAllData()
    {
        $query = Role::get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('permissions', function (Role $role) {
                return str_replace(array('[', ']', '"'), '', $role->permissions()->pluck('name'));
            })
            ->editColumn('actions', function (Role $role) {
                $editRoute = route('admin.role.edit', $role->id);
                $deleteRoute = '';
                $optionRoute = '';
                $optionRouteText = '';
                return getTableHtml($role, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText);
            })->rawColumns(['actions'])
            ->make(true);
    }
}
