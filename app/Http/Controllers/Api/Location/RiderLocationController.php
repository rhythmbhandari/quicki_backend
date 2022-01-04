<?php

namespace App\Http\Controllers\Api\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//requests
use App\Http\Requests\Api\Location\LocationRequest;
use App\Http\Requests\Api\Location\AvailableRidersRequest;
use App\Http\Requests\Api\Location\AvailableUsersRequest;

//services
use App\Modules\Services\Location\RiderLocationService;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\Rider;
use App\Modules\Models\RiderLocation;
use App\Modules\Models\VehicleType;
use App\Modules\Models\Booking;

class RiderLocationController extends Controller
{
    
    protected $rider_location, $user_service;

    public function __construct(RiderLocationService $rider_location, UserService $user_service)
    {
        $this->rider_location = $rider_location;
        $this->user_service = $user_service;
    }

        /**
    * @OA\Post(
    *   path="/api/rider/online",
    *   tags={"AVAILABLE AND ONLINE/OFFLINE RIDERS/USERS"},
    *   summary="Rider Online",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "latitude": 27.691248,
    *                  "longitude":85.332710,
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
    *                   example={"message":"Rider got Online Successfully!","updatedRiderLocation":{"id":1,"longitude":235345,"latitude":1324234,"rider_id":2,"status":"active","created_at":"2021-11-18T08:32:18.000000Z","updated_at":"2021-11-18T09:47:00.000000Z","availability":"not_available"}}
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
    *          description="Document Not Found!",
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
    function getRiderOnline(LocationRequest $request)
    {
        $user = Auth::user();
        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
        


        //CREATE or UPDATE RIDER LOCATION
        $rider_location = RiderLocation::where('rider_id',$user->rider->id)->first();
        $data = $request->all();
        if($rider_location)
        {
            //UPDATE RIDER LOCATION
            $data['status'] = 'active';
            $updatedRiderLocation = $this->rider_location->update($rider_location->id, $data);
            // $rider_location->status = 'active';
            // $rider_location->save();
            $response = ['message' => 'Rider got Online Successfully!',  "updatedRiderLocation"=>RiderLocation::findOrFail($rider_location->id)];
            return response($response, 200);
        }
        else{
            //CREATE NEW RIDER LOCATION
            $data['status'] = 'active';
            $data['rider_id'] = $user->rider->id;
            $createdRiderLocation = $this->rider_location->create($data);
            $response = ['message' => 'Rider got Online Successfully for the first time!',  "createdRiderLocation"=>$createdRiderLocation];
            return response($response, 201);
        }
        
        return response("Internal Server Error!", 500);

    }

    /**
    * @OA\Post(
    *   path="/api/rider/offline",
    *   tags={"AVAILABLE AND ONLINE/OFFLINE RIDERS/USERS"},
    *   summary="Rider Offline",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "latitude":27.687169,
    *                  "longitude":85.304219,
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
    *                   example={"message":"Rider got Offline Successfully!","updatedRiderLocation":{"id":1,"longitude":235345,"latitude":1324234,"rider_id":2,"status":"in_active","created_at":"2021-11-18T08:32:18.000000Z","updated_at":"2021-11-18T09:51:27.000000Z","availability":"not_available"}}
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
    *          description="Document Not Found!",
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
    function getRiderOffline(LocationRequest $request)
    {
        $user = Auth::user();
        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
   


        //CREATE or UPDATE RIDER LOCATION
        $rider_location = RiderLocation::where('rider_id',$user->rider->id)->first();
        $data = $request->all();
        if($rider_location)
        {
            //UPDATE RIDER LOCATION
            $data['status'] = 'in_active';
            $updatedRiderLocation = $this->rider_location->update($rider_location->id, $data);
            // $rider_location->status = 'in_active';
            // $rider_location->save();
            $response = ['message' => 'Rider got Offline Successfully!',  "updatedRiderLocation"=>RiderLocation::findOrFail($rider_location->id)];
            return response($response, 200);
        }
        else{
            //CREATE NEW RIDER LOCATION
            $data['status'] = 'in_active';
            $data['rider_id'] = $user->rider->id;
            $createdRiderLocation = $this->rider_location->create($data);
            $response = ['message' => 'Rider got Offline Successfully for the first time!',  "createdRiderLocation"=>$createdRiderLocation];
            return response($response, 201);
        }
        
        return response("Internal Server Error!", 500);


    }


    /**
    * @OA\Post(
    *   path="/api/riders/available",
    *   tags={"AVAILABLE AND ONLINE/OFFLINE RIDERS/USERS"},
    *   summary="Available Riders ",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "origin_latitude":27.6871690,
    *                  "origin_longitude":85.3042190,
    *                  "vehicle_type_id":1,
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
    *                     "message": "Success!",
    *                     "available_riders": {
    *                       {
    *                         "id": 1,
    *                         "longitude": 85.304219,
    *                         "latitude": 27.687169,
    *                         "rider_id": 1,
    *                         "status": "active",
    *                         "deleted_at": null,
    *                         "created_at": "2021-11-25T19:11:04.000000Z",
    *                         "updated_at": "2021-11-26T06:12:56.000000Z",
    *                         "availability": "available"
    *                       }
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
    *          response=404,
    *          description="Document Not Found!",
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
    function getAvailableRiders(AvailableRidersRequest $request)
    {   
      
        $available_riders = [];
      
        if(isset($request->vehicle_type_id))
        {  
            $available_riders = 
            $this->rider_location->getNearbyAvailableRiders($request->origin_latitude,$request->origin_longitude,$request->vehicle_type_id);
        }
        else{
            $available_riders = 
            $this->rider_location->getNearbyAvailableRiders($request->origin_latitude,$request->origin_longitude);
        }

      
        $response = ['message' => 'Success!',  "available_riders"=>$available_riders];
        return response($response, 200);

        return response("Internal Server Error!", 500);
    }






    /**
    * @OA\Get(
    *   path="/api/users/available",
    *   tags={"AVAILABLE AND ONLINE/OFFLINE RIDERS/USERS"},
    *   summary="Available Users/ Nearby Pending Bookings [IN PROGRESS!]",
    *   security={{"bearerAuth":{}}},
    *
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example=
    *                   {
    *                     "message": "Success!",
    *                     "available_users": {
    *                       {
    *                         "id": 10,
    *                         "stoppage": {
    *                           {
    *                             "name": "Sanepa, Lalitpur",
    *                             "latitude": 27.1234,
    *                             "longitude": 85.3434
    *                           },
    *                           {
    *                             "name": "New Baneshwor, Kathmandu",
    *                             "latitude": 28.3454,
    *                             "longitude": 87.1234
    *                           }
    *                         },
    *                         "user_id": 5,
    *                         "vehicle_type_id": 1,
    *                         "rider_id": null,
    *                         "location_id": 10,
    *                         "start_time": null,
    *                         "end_time": null,
    *                         "origin": "Sanepa, Lalitpur",
    *                         "destination": "New Baneshwor, Kathmandu",
    *                         "distance": 12,
    *                         "duration": 20,
    *                         "passenger_number": 2,
    *                         "status": "pending",
    *                         "price": 160,
    *                         "payment_type": "CASH",
    *                         "deleted_at": null,
    *                         "created_at": "2021-11-27T20:02:56.000000Z",
    *                         "updated_at": "2021-11-27T20:02:56.000000Z",
    *                         "location": {"origin":{"latitude":27.7278388,"longitude":85.37820680000002,"name":"Jorpati, Nepal"},"destination":{"latitude":27.6515356,"longitude":85.327837,"name":"Satdobato, Lalitpur, Nepal"}}
    *                       }
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
    *          response=400,
    *          description="You need to get online to view active user bookings!",
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
    function getAvailableUsers($rider_id=null)
    {   
       
        $user = Auth::user();
        $rider_location = null;

        if(!$rider_id)
        {
            //ROLE CHECK FOR RIDER
            if( ! $this->user_service->hasRole($user, 'rider') )
            {
                $response = ['message' => 'Forbidden Access!'];
                return response($response, 403);
            }

            $rider_id =  $user->rider->id;

            //Check if the rider is online or not
            $rider_location = RiderLocation::where('rider_id',$rider_id)->first();
            if(!$rider_location || $rider_location->status != 'active'){
                $response = ['message' => 'You need to get online to view active user bookings!'];
                return response($response, 400);
            }

        }
// dd()
        $nearby_pending_bookings = $this->rider_location->getNearbyAvailableUsers(
            $rider_location->latitude, $rider_location->longitude,$rider_location->rider->vehicle->vehicle_type_id 
        );

        $response = ['message' => 'Success!',  "available_users"=>$nearby_pending_bookings];
        return response($response, 200);
        
        return response("Internal Server Error!", 500);

      
        // $vehicle_type_id = $rider_location->rider->vehicle->vehicle_type_id;

        // /***********FOR NOW TEMPORARYY CODE */
        // $available_users = Booking::where('status','pending')->where('vehicle_type_id',$vehicle_type_id)->get();

        // $response = ['message' => 'Success!',  "available_users"=>$available_users];
        // return response($response, 200);

        // $nearby_pending_bookings = [];
        // $this->rider_location->getNearbyAvailableUsers($request->origin_latitude,$request->origin_longitude);


        dd("END");

        dd('assa');

        // $available_riders = [];
      
        // if(isset($request->vehicle_type_id))
        // {  
        //     $available_riders = 
        //     $this->rider_location->getNearbyAvailableRiders($request->origin_latitude,$request->origin_longitude,$request->vehicle_type_id);
        // }
        // else{
        //     $available_riders = 
        //     $this->rider_location->getNearbyAvailableRiders($request->origin_latitude,$request->origin_longitude);
        // }

      
        // $response = ['message' => 'Success!',  "available_riders"=>$available_riders];
        // return response($response, 200);

      
    }











    /**
    * @OA\Get(
    *   path="/api/rider/{rider_id}/location",
    *   tags={"Details"},
    *   summary="Get Rider's Location  ",
    *   security={{"bearerAuth":{}}},
    *      @OA\Parameter(
    *         name="rider_id",
    *         in="path",
    *         description="Rider ID",
    *         required=true,
    *      ),
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *               @OA\Schema(
    *                   example={}
    *
    *               )
    *      )
    *   ),
    *      @OA\Response(
    *          response=404,
    *          description="Rider could not be located at the moment!",
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *      ),
    *)
    **/
    public function getRiderLocation($rider_id)
    {

        $rider = Rider::find($rider_id);

        if(!$rider)
        {
            $response = ['message' => 'Rider could not be located at the moment!'];
            return response($response, 404);
        }

        if(isset($rider->rider_location))
        {
            $rider_location = $rider->rider_location;

            $response = ['message' => 'Success!',  "rider_location"=>$rider_location];
            return response($response, 200);
        }
        else{
            $response = ['message' => 'Rider could not be located at the moment!'];
            return response($response, 404);
        }
        


        return response("Internal Server Error!", 500);
    }

    

}
