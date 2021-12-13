<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Modules\Models\Role;

//services
use App\Modules\Services\User\UserService;
use App\Modules\Services\Customer\CustomerService;

//models
use App\Modules\Models\User;

class CustomerController extends Controller
{


    protected $customer;

    public function __construct(CustomerService $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAllData()
    {
        return $this->customer->getAllData();
    }


    public function index()
    {

        // $user = User::find(1)->assignRole('customer');
        $customers = $this->customer->all();
        return view('admin.customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $vendors = Vendor::where('status', 'active')->get();
        $roles = Role::get();
        return view('admin.customer.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($user = $this->user->create($request->all())) {
            if ($request->hasFile('image')) {
                $this->uploadFile($request, $user);
            }

            if ($request->has('role')) {
                $user->roles()->attach($request->roles);
            }

            Toastr::success('User created successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.user.index');
        }
        Toastr::error('User cannot be created.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('admin.user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $getdata = $this->customer->find($id);

        // $vendors = $this->vendor->all($id);
        $roles = Role::get();
        $assignedRoles = $getdata->roles->pluck('id')->toArray();

        return view('admin.users.edit', compact('getdata', 'roles', 'assignedRoles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if ($this->customer->update($id, $request->all())) {

            $customer = $this->customer->find($id);
            if ($request->hasFile('photo')) {
                $this->uploadFile($request, $customer);
            }

            if ($request->roles <> '') {
                $customer->roles()->sync($request->roles);
            } else {
                $customer->roles()->detach();
            }

            Toastr::success('User updated successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.user.index');
        }
        Toastr::error('User cannot be updated.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('admin.user.index');
    }

    function uploadFile(Request $request, $customer)
    {
        $file = $request->file('photo');
        $fileName = $this->customer->uploadFile($file);
        if (!empty($customer->photo))
            $this->customer->__deleteImages($customer);

        $data['photo'] = $fileName;
        $this->customer->updateImage($customer->id, $data);
    }
}
