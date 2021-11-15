<?php

namespace App\Http\Controllers\Api\Vehicle;

use App\Modules\Models\VehicleType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VehicleType  $vehicleType
     * @return \Illuminate\Http\Response
     */
    public function show(VehicleType $vehicleType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VehicleType  $vehicleType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VehicleType $vehicleType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VehicleType  $vehicleType
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehicleType $vehicleType)
    {
        //
    }

 /**
     * @OA\Post(
     *   path="/api/vehicle_type/get_all_data",
     *   tags={"Auth"},
     *   summary="VehicleTypeController",
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *         mediaType="multipart/form-data",
     *         @OA\Schema(
     *             example={
     *                 "email": "admin@gmail.com",
     *                 "phone": "9876543210",
     *                 "first_name": "admin",
     *                 "last_name": "user",
     *                 "gender": "male",
     *                 "password": "password",
     *                 "password_confirmation": "password",
     *                 "dob": "2000/01/01",
     *                 "license": {
     *                      "issue_date": "2018/01/01",
     *                      "expire_date": "2018/01/01",
     *                      "image": "file()",
     *                  },
     *                  "facebook_id" : "",
     *                  "google_id" : ""
     *              }
     *         )
     *     )
     *   ),
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
