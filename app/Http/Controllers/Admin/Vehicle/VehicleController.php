<?php

namespace App\Http\Controllers\Admin\Vehicle;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//services
use App\Modules\Services\Vehicle\VehicleService;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\Vehicle;
use App\Modules\Models\User;
class VehicleController extends Controller
{
    protected $vehicle, $user_service;

    public function __construct(VehicleService $vehicle, UserService $user_service)
    {
        $this->vehicle = $vehicle;
        $this->user_service = $user_service;
    }


    public function store(Request $request)
    {
        
    }

    public function update(Request $request, $vehicle_id)
    {
        
    }


}
