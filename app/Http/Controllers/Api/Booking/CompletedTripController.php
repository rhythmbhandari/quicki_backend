<?php

namespace App\Http\Controllers\Api\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//services
use App\Modules\Services\Booking\CompletedTripService;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\User;
use App\Modules\Models\Rider;
use App\Modules\Models\CompletedTrip;


class CompletedTripController extends Controller
{
    
    protected $completed_trip, $user_service;

    public function __construct(CompletedTripService $completed_trip, UserService $user_service)
    {
        $this->completed_trip = $completed_trip;
        $this->user_service = $user_service;
    }

    /**
    * @OA\Get(
    *   path="/api/user/booking/history",
    *   tags={"Booking"},
    *   summary="User Booking History",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                           "message":"Success!",
    *                           "completed_trips": {
    *                                 {
    *                                 "id": 1,
    *                                 "user_id": 3,
    *                                 "rider_id": 1,
    *                                 "booking_id": 1,
    *                                 "location_id": 1,
    *                                 "origin": "Sanepa, Lalitpur",
    *                                 "destination": "New Baneshwor, Kathmandu",
    *                                 "distance": null,
    *                                 "duration": null,
    *                                 "passenger_number": 1,
    *                                 "profile_img_user": null,
    *                                 "profile_img_rider": null,
    *                                 "status": "completed",
    *                                 "cancelled_by_type": null,
    *                                 "cancelled_by_id": null,
    *                                 "cancel_message": null,
    *                                 "deleted_at": null,
    *                                 "created_at": "2021-11-16T12:31:03.000000Z",
    *                                 "updated_at": "2021-11-16T12:46:58.000000Z",
    *                                 "location": {
    *                                   "id": 1,
    *                                   "longitude_origin": 27.123456,
    *                                   "latitude_origin": 85.123423,
    *                                   "longitude_destination": 27.234325,
    *                                   "latitude_destination": 86.12313,
    *                                   "created_at": "2021-11-16T12:31:03.000000Z",
    *                                   "updated_at": "2021-11-16T12:31:03.000000Z"
    *                                 },
    *                                 "rider": {
    *                                   "id": 1,
    *                                   "user_id": 2,
    *                                   "experience": 3,
    *                                   "trained": "YES",
    *                                   "status": "active",
    *                                   "approved_at": "2021-11-16 12:18:06",
    *                                   "deleted_at": null,
    *                                   "last_deleted_by": null,
    *                                   "last_updated_by": null,
    *                                   "created_at": "2021-11-16T12:18:06.000000Z",
    *                                   "updated_at": "2021-11-16T12:18:06.000000Z",
    *                                   "vehicle": null
    *                                 }
    *                               },
    *                           }
    *                   }
    *                 )
    *      )
    *   ),
    *   @OA\Response(
    *      response=403,
    *       description="Forbidden Access!",
    *   )
    *)
    **/
    public function getUserTrips()
    {
        $user = Auth::user();

        //ROLE CHECK FOR CUSTOMER
        if( ! $this->user_service->hasRole($user, 'customer') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
          

        //Fetch booking history/ completed trips
        $completed_trips = CompletedTrip::where('user_id',$user->id)->with('location')->with('rider')->get();

        $response = ['message' => 'Success!',  "completed_trips"=>$completed_trips];
        return response($response, 200);

    }

    /**
    * @OA\Get(
    *   path="/api/rider/booking/history",
    *   tags={"Booking"},
    *   summary="Rider Booking History",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                           "message":"Success!",
    *                           "completed_trips": {
    *                                 {
    *                                 "id": 1,
    *                                 "user_id": 3,
    *                                 "rider_id": 1,
    *                                 "booking_id": 1,
    *                                 "location_id": 1,
    *                                 "origin": "Sanepa, Lalitpur",
    *                                 "destination": "New Baneshwor, Kathmandu",
    *                                 "distance": null,
    *                                 "duration": null,
    *                                 "passenger_number": 1,
    *                                 "profile_img_user": null,
    *                                 "profile_img_rider": null,
    *                                 "status": "completed",
    *                                 "cancelled_by_type": null,
    *                                 "cancelled_by_id": null,
    *                                 "cancel_message": null,
    *                                 "deleted_at": null,
    *                                 "created_at": "2021-11-16T12:31:03.000000Z",
    *                                 "updated_at": "2021-11-16T12:46:58.000000Z",
    *                                 "location": {
    *                                   "id": 1,
    *                                   "longitude_origin": 27.123456,
    *                                   "latitude_origin": 85.123423,
    *                                   "longitude_destination": 27.234325,
    *                                   "latitude_destination": 86.12313,
    *                                   "created_at": "2021-11-16T12:31:03.000000Z",
    *                                   "updated_at": "2021-11-16T12:31:03.000000Z"
    *                                 },
    *                                 "rider": {
    *                                   "id": 1,
    *                                   "user_id": 2,
    *                                   "experience": 3,
    *                                   "trained": "YES",
    *                                   "status": "active",
    *                                   "approved_at": "2021-11-16 12:18:06",
    *                                   "deleted_at": null,
    *                                   "last_deleted_by": null,
    *                                   "last_updated_by": null,
    *                                   "created_at": "2021-11-16T12:18:06.000000Z",
    *                                   "updated_at": "2021-11-16T12:18:06.000000Z",
    *                                   "vehicle": null
    *                                 }
    *                               },
    *                           }
    *                   }
    *                 )
    *      )
    *   ),
    *   @OA\Response(
    *      response=403,
    *       description="Forbidden Access!",
    *   )
    *)
    **/
    public function getRiderTrips()
    {
        $user = Auth::user();

        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
          

        //Fetch booking history/ completed trips
        $completed_trips = CompletedTrip::where('user_id',$user->id)->with('location')->with('user')->get();

        $response = ['message' => 'Success!',  "completed_trips"=>$completed_trips];
        return response($response, 200);

    }

}
