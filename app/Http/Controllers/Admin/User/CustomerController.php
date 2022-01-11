<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Modules\Models\Role;
use Kamaln7\Toastr\Facades\Toastr;
use App\Http\Requests\Admin\Customer\CustomerRequest;

//services
use App\Modules\Services\User\Userservice;

//models
use App\Modules\Models\User;

class CustomerController extends Controller
{


    protected $customer;

    public function __construct(Userservice $customer)
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
        return view('admin.customer.index');
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
    public function store(CustomerRequest $request)
    {
        // dd($request->all());
        $data = $request->except('image');

        // dd($data);

        // $data['status'] = (isset($data['status']) ?  $data['status'] : '') == 'on' ? 'active' : 'in_active';
        if (
            isset($data['home']['name']) && isset($data['home']['latitude']) && isset($data['home']['longitude']) &&
            isset($data['work']['name']) && isset($data['work']['latitude']) && isset($data['work']['longitude'])
        ) {
            $data['location']['home'] = $data['home'];
            $data['location']['work'] = $data['work'];
        }

        // dd($data, $request);
        return DB::transaction(function () use ($request, $data) {
            if ($customer = $this->customer->create($data)) {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $customer);
                }

                if (isset($data['location']))
                    $this->customer->update_location($customer->id, $data);

                // dd($customer, $request, $data);

                Toastr::success('Customer created successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.customer.index');
            }
            Toastr::error('Customer cannot be created.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.customer.index');
        });
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
        $customer = $this->customer->find($id);
        // dd($customer->location);
        // $location = json_decode($customer->location);
        // dd($customer, $location);

        return view('admin.customer.edit', compact('customer'));
    }

    function customerAjax(Request $request)
    {
        // dd($request->all());
        $query = User::with('rider')->select('id', 'first_name', 'last_name')
            ->when($request->q, function ($query) use ($request) {
                $q = $request->q;
                $query = $query->where('first_name', 'LIKE', "%" . $q . "%");
                $query = $query->orWhere('last_name', 'LIKE', "%" . $q . "%");
                return $query;
            })->simplePaginate(10);
        // dd($query->toArray());
        $results = array();
        foreach ($query as $object) {
            array_push($results, [
                'id' => $object['id'],
                'text' => $object->first_name . ' ' . $object->last_name,
                'rider_id' => ($object->rider) ? $object->rider->id : null
            ]);
        }

        $morePages = true;
        $pagination_obj = json_encode($query);
        if (empty($query->nextPageUrl())) {
            $morePages = false;
        }

        $pagination = array(
            "more" => !is_null($query->toArray()['next_page_url'])
        );

        // $pagination = [
        //     'more' => !is_null($query->toArray()['next_page_url'])
        // ];
        return compact('results', 'pagination');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id)
    {
        $data = $request->except('image');
        if (
            isset($data['home']['name']) && isset($data['home']['latitude']) && isset($data['home']['longitude']) &&
            isset($data['work']['name']) && isset($data['work']['latitude']) && isset($data['work']['longitude'])
        ) {
            $data['location']['home'] = $data['home'];
            $data['location']['work'] = $data['work'];
        }
        return DB::transaction(function () use ($request, $data, $id) {
            if ($customer = $this->customer->update($id, $data)) {

                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $customer);
                }

                if (isset($data['location']))
                    $this->customer->update_location($customer->id, $data);

                Toastr::success('Customer updated successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.customer.index');
            }
            Toastr::error('Customer cannot be updated.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.customer.index');
        });
    }

    function uploadFile(Request $request, $customer)
    {
        $file = $request->file('image');
        $fileName = $this->customer->uploadFile($file);
        if (!empty($customer->image))
            $this->customer->__deleteImages($customer);

        $data['image'] = $fileName;
        $this->customer->updateImage($customer->id, $data);
        // dd($fileName, $this->customer->updateImage($customer->id, $data), $data);
    }
}
