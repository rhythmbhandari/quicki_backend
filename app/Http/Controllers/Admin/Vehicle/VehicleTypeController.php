<?php

namespace App\Http\Controllers\Admin\Vehicle;

use App\Modules\Models\VehicleType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VehicleTypeController extends Controller
{
   

    public function get_all_data()
    {
        return response()->json(VehicleType::all()->toArray());
    }
}
