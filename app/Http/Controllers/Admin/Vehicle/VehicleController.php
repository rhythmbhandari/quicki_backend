<?php

namespace App\Http\Controllers\Admin\Vehicle;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//services
use App\Modules\Services\Vehicle\VehicleService;
use App\Http\Requests\Admin\Vehicle\VehicleRequest;
use App\Modules\Services\Vehicle\VehicleTypeService;
use App\Modules\Services\User\UserService;
use App\Modules\Services\User\RiderService;
use Kamaln7\Toastr\Facades\Toastr;


class VehicleController extends Controller
{
    protected $vehicle, $rider, $vehicle_type;

    public function __construct(VehicleService $vehicle, RiderService $rider, VehicleTypeService $vehicle_type)
    {
        $this->vehicle = $vehicle;
        $this->rider = $rider;
        $this->vehicle_type = $vehicle_type;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $vendor = $this->vendor->all();
        $vehicles = $this->vehicle->all();
        return view('admin.vehicle.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $vendors = $this->vendor->all();
        // $manufacturers = $this->manufacturer->all();
        $vehicle_types = $this->vehicle_type->all();
        $rider = $this->rider->all();
        // $models = $this->model->all();
        return view('admin.vehicle.create', compact('vehicle_types'));
    }

    public function getAllData(Request $request)
    {
        return $this->vehicle->getAllData($request);
    }

    public function store(VehicleRequest $request)
    {
        // dd($request->all());
        if ($vehicle = $this->vehicle->create($request->all())) {

            if ($request->hasFile('image')) {
                $this->uploadFile($request, $vehicle, 'image');
            }
            if ($request->hasFile('bluebook_file')) {
                $this->uploadFile($request, $vehicle, 'bluebook_file');
            }
            if ($request->hasFile('insurance_file')) {
                $this->uploadFile($request, $vehicle, 'insurance_file');
            }

            Toastr::success('Vehicle created successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.vehicle.index');
        }
        Toastr::error('Vehicle cannot be created.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('admin.vehicle.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vehicle = $this->vehicle->find($id);
        $vehicle_type = $vehicle->vehicle_type;
        $rider = $vehicle->rider;
        $rider['user'] = $rider->user;
        // dd($vehicle->bluebook_path);
        return view('admin.vehicle.edit', compact('vehicle', 'rider', 'vehicle_type'));
    }

    function uploadFile(Request $request, $vehicle, $type)
    {

        $file = $request->file($type);
        $fileName = $this->vehicle->uploadFile($file, $type);

        if (!empty($vehicle->image))
            $this->vehicle->__deleteImages($vehicle);


        $data[$type] = $fileName;
        $this->vehicle->updateImage($vehicle->id, $data);
    }

    public function update(VehicleRequest $request, $vehicle_id)
    {
        if ($this->vehicle->update($vehicle_id, $request->all())) {
            $vehicle = $this->vehicle->find($vehicle_id);
            if ($request->hasFile('image')) {
                $this->uploadFile($request, $vehicle, 'image');
            }
            if ($request->hasFile('bluebook_file')) {
                $this->uploadFile($request, $vehicle, 'bluebook_file');
            }
            if ($request->hasFile('insurance_file')) {
                $this->uploadFile($request, $vehicle, 'insurance_file');
            }
            Toastr::success('Vendor updated successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.vehicle.index');
        }
        Toastr::error('Vendor cannot be updated.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('admin.vehicle.index');
    }
}
