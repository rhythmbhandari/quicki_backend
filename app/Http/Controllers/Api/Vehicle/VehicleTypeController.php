<?php

namespace App\Http\Controllers\Api\Vehicle;

use App\Modules\Models\VehicleType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VehicleTypeController extends Controller
{
   

 /**
    * @OA\Get(
    *   path="/api/vehicle_type/get_all_data",
    *   tags={"Vehicle"},
    *   summary="VehicleTypeController",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *   )
    *)
    **/
    public function get_all_data()
    {
        return response()->json(VehicleType::all()->toArray());
    }
}
