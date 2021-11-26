<?php

namespace App\Http\Controllers\Api\Vehicle;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//requests
use App\Http\Requests\Api\Vehicle\VehicleRequest;
use App\Http\Requests\Api\Vehicle\UpdateVehicleRequest;

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


        /**
    * @OA\Post(
    *   path="/api/vehicle/create",
    *   tags={"Vehicle"},
    *   summary="Create Vehicle",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                   "vehicle_type_id":1,
    *                  "vehicle_number":"BA 12 PA 1234",
    *                  "image":"file()",
    *                  "make_year":"2009",
    *                  "brand":"Hero",
    *                  "model":"Splender",
    *              }
    *         )
    *     )
    *   ),
    *
    *      @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                     "message": "Vehicle created successfully!",
    *                     "vehicle": {
    *                       "vehicle_type_id": 1,
    *                       "vehicle_number": "BA 12 PA 1234",
    *                       "image": null,
    *                       "make_year": "2009",
    *                       "brand": "Hero",
    *                       "model": "Splender",
    *                       "rider_id": 1,
    *                       "status": "active",
    *                       "slug": "ba-12-pa-1234",
    *                       "updated_at": "2021-11-25T19:53:03.000000Z",
    *                       "created_at": "2021-11-25T19:53:03.000000Z",
    *                       "id": 1,
    *                       "thumbnail_path": "assets/media/noimage.png",
    *                       "image_path": "assets/media/noimage.png"
    *                     }
    *                   }
    *                 )
    *           )
    *      ),
    *
    *      @OA\Response(
    *          response=422,
    *          description="Validation Fail",
    *             @OA\MediaType(
     *           mediaType="application/json",
     *      )
    *      ),
     *      @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    public function store(VehicleRequest $request)
    {
        $user = Auth::user();

        //ROLE CHECK FOR  rider or driver
        if($this->user_service->hasRole($user, 'rider')) {
            $request['rider_id'] = $user->rider->id;
        } else if($this->user_service->hasRole($user, 'driver') ) {
            $request['rider_id'] = $user->rider->id;
        } else {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        //CREATE DOCUMENT
        return DB::transaction(function () use ($request)
        {
            $createdVehicle = $this->vehicle->create($request->all());
    
            if($createdVehicle)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $createdVehicle);
                }
                $response = ['message' => 'Vehicle created successfully!',  "vehicle"=>$createdVehicle];
                return response($response, 201);
            }
            return response("Internal Server Error!", 500);
        });
    }



    /**
    * @OA\Post(
    *   path="/api/vehicle/{vehicle_id}/update",
    *   tags={"Vehicle"},
    *   summary="Update Vehicle",
    *   security={{"bearerAuth":{}}},
    *
    *         @OA\Parameter(
    *         name="vehicle_id",
    *         in="path",
    *         description="Vehicle ID",
    *         required=true,
    *      ),
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "vehicle_type_id":1,
    *                  "vehicle_number":"BA 12 PA 1234",
    *                  "image":"file()",
    *                  "make_year":"2009",
    *                  "brand":"Hero",
    *                  "model":"Splender",
    *              }
    *         )
    *     )
    *   ),
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                         "message": "Vehicle updated Successfully!",
    *                         "vehicle": {
    *                           "id": 1,
    *                           "slug": "ba-12-pa-1234",
    *                           "rider_id": 1,
    *                           "vehicle_type_id": 1,
    *                           "vehicle_number": "BA 99 PA 1234",
    *                           "image": null,
    *                           "make_year": "2010",
    *                           "vehicle_color": null,
    *                           "brand": "HERO",
    *                           "model": "Splender",
    *                           "status": "active",
    *                           "deleted_at": null,
    *                           "last_deleted_by": null,
    *                           "last_updated_by": null,
    *                           "created_at": "2021-11-25T19:53:03.000000Z",
    *                           "updated_at": "2021-11-25T19:57:29.000000Z",
    *                           "thumbnail_path": "assets/media/noimage.png",
    *                           "image_path": "assets/media/noimage.png"
    *                         }
    *                       }
    *                 )
    *           )
    *      ),
    *
    *      @OA\Response(
    *          response=422,
    *          description="Validation Fail",
    *             @OA\MediaType(
     *           mediaType="application/json",
     *      )
    *      ),
     *      @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
     *      @OA\Response(
    *          response=404,
    *          description="Vehicle Not Found!",
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    public function update(UpdateVehicleRequest $request, $vehicle_id)
    {
        $user = Auth::user();
        $vehicle = Vehicle::find($vehicle_id);

        if(!$vehicle)
        {
            $response = ['message' => 'Vehicle Not Found!'];
            return response($response, 404);
        }

        //ROLE CHECK FOR  rider or driver
        if($this->user_service->hasRole($user, 'rider')) {
            $request['rider_id'] = $user->rider->id;
        } else if($this->user_service->hasRole($user, 'driver') ) {
            $request['rider_id'] = $user->rider->id;
        } else {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }


         //UPDATE VEHICLE
         return DB::transaction(function () use ($request,$vehicle,$vehicle_id)
         {
             $updatedVehicle = $this->vehicle->update($vehicle->id,$request->all());
     
             if($updatedVehicle)
             {
                 if ($request->hasFile('image')) {
                     $this->uploadFile($request, $updatedVehicle);
                 }
                 $response = ['message' => 'Vehicle updated Successfully!',  "vehicle"=>$updatedVehicle];
                 return response($response, 200);
             }
             return response("Internal Server Error!", 500);
         });
    }


    
    /**
    * @OA\Get(
    *   path="/api/vehicle/{vehicle_id}/details",
    *   tags={"Vehicle"},
    *   summary="Get Vehicle",
    *   security={{"bearerAuth":{}}},
    *
    *         @OA\Parameter(
    *         name="vehicle_id",
    *         in="path",
    *         description="Vehicle ID",
    *         required=true,
    *      ),
    *
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                     "message": "Success!",
    *                     "Vehilce": {
    *                       "id": 1,
    *                       "slug": "ba-12-pa-1234",
    *                       "rider_id": 1,
    *                       "vehicle_type_id": 1,
    *                       "vehicle_number": "BA 12 PA 1234",
    *                       "image": null,
    *                       "make_year": "2009",
    *                       "vehicle_color": null,
    *                       "brand": "Hero",
    *                       "model": "Splender",
    *                       "status": "active",
    *                       "deleted_at": null,
    *                       "last_deleted_by": null,
    *                       "last_updated_by": null,
    *                       "created_at": "2021-11-25T19:53:03.000000Z",
    *                       "updated_at": "2021-11-25T19:53:03.000000Z",
    *                       "thumbnail_path": "assets/media/noimage.png",
    *                       "image_path": "assets/media/noimage.png"
    *                     }
    *                   }
    *                 )
    *           )
    *      ),
    *
     *      @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
     *      @OA\Response(
    *          response=404,
    *          description="Vehicle Not Found!",
    *      ),

    *)
    **/
    public function getVehicle($vehicle_id)
    {
        $vehicle = Vehicle::find($vehicle_id);

        if(!$vehicle)
        {
            $response = ['message' => 'Vehilce Not Found!'];
            return response($response, 404);
        }
        else {
            $response = ['message' => 'Success!',  "Vehilce"=>$vehicle];
            return response($response, 200);
        }
    }

    /**
    * @OA\Get(
    *   path="/api/rider/vehicle_details",
    *   tags={"Vehicle"},
    *   summary="Get Authenticated Rider's Vehicle",
    *   security={{"bearerAuth":{}}},
    *
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                     "message": "Success!",
    *                     "Vehilce": {
    *                       "id": 1,
    *                       "slug": "ba-12-pa-1234",
    *                       "rider_id": 1,
    *                       "vehicle_type_id": 1,
    *                       "vehicle_number": "BA 12 PA 1234",
    *                       "image": null,
    *                       "make_year": "2009",
    *                       "vehicle_color": null,
    *                       "brand": "Hero",
    *                       "model": "Splender",
    *                       "status": "active",
    *                       "deleted_at": null,
    *                       "last_deleted_by": null,
    *                       "last_updated_by": null,
    *                       "created_at": "2021-11-25T19:53:03.000000Z",
    *                       "updated_at": "2021-11-25T19:53:03.000000Z",
    *                       "thumbnail_path": "assets/media/noimage.png",
    *                       "image_path": "assets/media/noimage.png"
    *                     }
    *                   }
    *                 )
    *           )
    *      ),
    *
     *      @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
     *      @OA\Response(
    *          response=404,
    *          description="No Vehicle Found!",
    *      ),

    *)
    **/
    public function getRiderVehicle()
    {
        return response(["hello"=>"asdas"], 200);
        $user = Auth::user();
        $vehicle = null;

        //ROLE CHECK FOR  rider or driver
        if($this->user_service->hasRole($user, 'rider')) {
            $vehicle = $user->rider->vehicle;
        } else if($this->user_service->hasRole($user, 'driver') ) {
            $vehicle = $user->rider->vehicle;
        } else {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        if($vehicle) {
            $response = ['message' => 'Success!',  "vehilce"=>$vehicle];
            return response($response, 200);
        }
        else{
            $response = ['message' => 'No Vehicle Found!'];
            return response($response, 404);
        }

    }



        //Image for vehicle 
        function uploadFile(Request $request, $vehicle)
        {
            $file = $request->file('image');
            $fileName = $this->vehicle->uploadFile($file);
            if (!empty($vehicle->image))
                $this->vehicle->__deleteImages($vehicle);
    
            $data['image'] = $fileName;
            $this->vehicle->updateImage($vehicle->id, $data);
        }

}
