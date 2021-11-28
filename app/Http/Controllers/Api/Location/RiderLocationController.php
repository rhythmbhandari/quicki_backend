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
    *   summary="Available Riders [IN PROGRESS!]",
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
    *                   example={"message":"Success!","available_riders":{{"id":2,"user_id":4,"experience":5,"trained":"YES","status":"in_active","approved_at":null,"deleted_at":null,"last_deleted_by":null,"last_updated_by":null,"created_at":"2021-11-18T05:46:49.000000Z","updated_at":"2021-11-18T05:46:49.000000Z","user":{"id":4,"slug":"nitesh-d-luffy","first_name":"Nitesh","middle_name":"D.","last_name":"Manandhar","image":null,"dob":"2000-01-01","gender":null,"google_id":null,"facebook_id":null,"username":"nitesh","phone":"9816810976","email":"nitesh@gmail.com","status":null,"email_verified_at":null,"last_logged_in":null,"no_of_logins":null,"avatar":null,"deleted_at":null,"last_updated_by":null,"last_deleted_by":null,"created_at":"2021-11-18T05:46:49.000000Z","updated_at":"2021-11-18T05:50:51.000000Z","name":"Nitesh D. Manandhar"},"vehicle":{"id":1,"slug":"ba-99-pa-5544","rider_id":2,"vehicle_type_id":1,"vehicle_number":"BA 99 PA 5544","image":null,"make_year":"2016","vehicle_color":"black","brand":"TVS","model":"Apache 160R","status":"in_active","deleted_at":null,"last_deleted_by":null,"last_updated_by":null,"created_at":"2021-11-18T05:46:49.000000Z","updated_at":"2021-11-18T05:46:49.000000Z","thumbnail_path":"assets\/media\/noimage.png","image_path":"assets\/media\/noimage.png","documents":{}},"reviews":{}}}}
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
    *                   example={"message":"Success!","available_riders":{{"id":2,"user_id":4,"experience":5,"trained":"YES","status":"in_active","approved_at":null,"deleted_at":null,"last_deleted_by":null,"last_updated_by":null,"created_at":"2021-11-18T05:46:49.000000Z","updated_at":"2021-11-18T05:46:49.000000Z","user":{"id":4,"slug":"nitesh-d-luffy","first_name":"Nitesh","middle_name":"D.","last_name":"Manandhar","image":null,"dob":"2000-01-01","gender":null,"google_id":null,"facebook_id":null,"username":"nitesh","phone":"9816810976","email":"nitesh@gmail.com","status":null,"email_verified_at":null,"last_logged_in":null,"no_of_logins":null,"avatar":null,"deleted_at":null,"last_updated_by":null,"last_deleted_by":null,"created_at":"2021-11-18T05:46:49.000000Z","updated_at":"2021-11-18T05:50:51.000000Z","name":"Nitesh D. Manandhar"},"vehicle":{"id":1,"slug":"ba-99-pa-5544","rider_id":2,"vehicle_type_id":1,"vehicle_number":"BA 99 PA 5544","image":null,"make_year":"2016","vehicle_color":"black","brand":"TVS","model":"Apache 160R","status":"in_active","deleted_at":null,"last_deleted_by":null,"last_updated_by":null,"created_at":"2021-11-18T05:46:49.000000Z","updated_at":"2021-11-18T05:46:49.000000Z","thumbnail_path":"assets\/media\/noimage.png","image_path":"assets\/media\/noimage.png","documents":{}},"reviews":{}}}}
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
    function getAvailableUsers()
    {   
       
        $user = Auth::user();
        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        /***********FOR NOW TEMPORARYY CODE */
        $available_users = Booking::where('status','pending')->with('location')->get();
      
        $response = ['message' => 'Success!',  "available_users"=>$available_users];
        return response($response, 200);


        dd("END");

        //Check if the rider is online or not
        $rider_location = RiderLocation::where('rider_id',$user->rider->id)->first();
        if(!$rider_location || $rider_location->status != 'active'){
            $response = ['message' => 'You need to get online to view active user bookings!'];
            return response($response, 400);
        }

        dd('assa');

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

    }





    

}
