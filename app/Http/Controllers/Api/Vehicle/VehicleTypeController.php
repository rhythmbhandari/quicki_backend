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
    *   summary="Vehicle Types",
    *
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                           "message": "Successful!",
    *                           "vehicle_types": {
    *                                 {
    *                                   "id": 1,
    *                                   "name": "bike",
    *                                   "slug": "bike",
    *                                   "image": null,
    *                                   "price_km": 15,
    *                                   "price_min": 5,
    *                                   "base_fare": 50,
    *                                   "commission": 15,
    *                                   "capacity": "1",
    *                                   "status": "active",
    *                                   "deleted_at": null,
    *                                   "created_at": "2021-11-17T07:38:22.000000Z",
    *                                   "updated_at": "2021-11-17T07:38:22.000000Z",
    *                                   "thumbnail_path": "assets/media/noimage.png",
    *                                   "image_path": "assets/media/noimage.png"
    *                                 },
    *                                 {
    *                                   "id": 2,
    *                                   "name": "car",
    *                                   "slug": "car",
    *                                   "image": null,
    *                                   "price_km": 40,
    *                                   "price_min": 15,
    *                                   "base_fare": 120,
    *                                   "commission": 35,
    *                                   "capacity": "3",
    *                                   "status": "active",
    *                                   "deleted_at": null,
    *                                   "created_at": "2021-11-17T07:38:22.000000Z",
    *                                   "updated_at": "2021-11-17T07:38:22.000000Z",
    *                                   "thumbnail_path": "assets/media/noimage.png",
    *                                   "image_path": "assets/media/noimage.png"
    *                                 },
    *                                 {
    *                                   "id": 3,
    *                                   "name": "city_safari",
    *                                   "slug": "city-safari",
    *                                   "image": null,
    *                                   "price_km": 25,
    *                                   "price_min": 10,
    *                                   "base_fare": 80,
    *                                   "commission": 25,
    *                                   "capacity": "9",
    *                                   "status": "active",
    *                                   "deleted_at": null,
    *                                   "created_at": "2021-11-17T07:38:22.000000Z",
    *                                   "updated_at": "2021-11-17T07:38:22.000000Z",
    *                                   "thumbnail_path": "assets/media/noimage.png",
    *                                   "image_path": "assets/media/noimage.png"
    *                                 }
    *                           }
    *                       }
    *                 )
    *           )
    *       )
    *)
    **/
    public function get_all_data()
    {
        return response()->json(VehicleType::all()->toArray());
    }
}
