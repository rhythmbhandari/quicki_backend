<?php

namespace App\Http\Controllers\Admin\Vehicle;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//services
use App\Modules\Services\Vehicle\VehicleService;
use App\Modules\Services\Vehicle\VehicleTypeService;
use App\Modules\Services\User\UserService;
use App\Modules\Services\User\RiderService;

//models
use App\Modules\Models\Vehicle;
use App\Modules\Models\User;

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
        dd("hwl");
        return $this->vehicle->getAllData($request);
    }

    public function store(Request $request)
    {
    }

    public function update(Request $request, $vehicle_id)
    {
    }
}
