<?php

namespace App\Http\Controllers\Api\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

//services
use App\Modules\Services\Booking\CompletedTripService;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\User;
use App\Modules\Models\Rider;
use App\Modules\Models\CompletedTrip;
use App\Modules\Models\VehicleType;


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
    *                   example={"message":"Success!","completed_trips":{"current_page":1,"data":{{"id":13,"user_id":2,"rider_id":null,"booking_id":2,"location_id":2,"start_time":"2021-11-27 16:36:23","end_time":"2021-11-27 16:36:23","origin":"Sanepa, Lalitpur","destination":"New Baneshwor, Kathmandu","stoppage":{{"name":"Sanepa, Lalitpur","latitude":27.1234,"longitude":85.3434},{"name":"New Baneshwor, Kathmandu","latitude":28.3454,"longitude":"87.1234"}},"distance":12,"duration":20,"passenger_number":1,"profile_img_user":null,"profile_img_rider":null,"status":"cancelled","price":160,"payment_type":"CASH","cancelled_by_type":null,"cancelled_by_id":null,"cancel_message":null,"deleted_at":null,"created_at":"2021-11-26T01:04:14.000000Z","updated_at":"2021-11-27T10:51:23.000000Z","location":{"id":2,"longitude_origin":27.123456,"latitude_origin":85.123423,"longitude_destination":27.234325,"latitude_destination":86.12313,"deleted_at":null,"created_at":"2021-11-26T01:04:14.000000Z","updated_at":"2021-11-26T01:04:14.000000Z"},"rider":null}},"first_page_url":"http:\/\/127.0.0.1:8000\/api\/user\/booking\/history?page=1","from":1,"last_page":1,"last_page_url":"http:\/\/127.0.0.1:8000\/api\/user\/booking\/history?page=1","links":{{"url":null,"label":"« Previous","active":false},{"url":"http:\/\/127.0.0.1:8000\/api\/user\/booking\/history?page=1","label":"1","active":true},{"url":null,"label":"Next »","active":false}},"next_page_url":null,"path":"http:\/\/127.0.0.1:8000\/api\/user\/booking\/history","per_page":5,"prev_page_url":null,"to":1,"total":1}}
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
        //dd("completed trips user");
        $user = Auth::user();

        //ROLE CHECK FOR CUSTOMER
        if( ! $this->user_service->hasRole($user, 'customer') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
          

        //Fetch booking history/ completed trips
        $completed_trips = CompletedTrip::where('user_id',$user->id)->with('location')->with('rider')->paginate(5);

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
    *                   example={"message":"Success!","completed_trips":{"current_page":1,"data":{{"id":13,"user_id":2,"rider_id":null,"booking_id":2,"location_id":2,"start_time":"2021-11-27 16:36:23","end_time":"2021-11-27 16:36:23","origin":"Sanepa, Lalitpur","destination":"New Baneshwor, Kathmandu","stoppage":{{"name":"Sanepa, Lalitpur","latitude":27.1234,"longitude":85.3434},{"name":"New Baneshwor, Kathmandu","latitude":28.3454,"longitude":"87.1234"}},"distance":12,"duration":20,"passenger_number":1,"profile_img_user":null,"profile_img_rider":null,"status":"cancelled","price":160,"payment_type":"CASH","cancelled_by_type":null,"cancelled_by_id":null,"cancel_message":null,"deleted_at":null,"created_at":"2021-11-26T01:04:14.000000Z","updated_at":"2021-11-27T10:51:23.000000Z","location":{"id":2,"longitude_origin":27.123456,"latitude_origin":85.123423,"longitude_destination":27.234325,"latitude_destination":86.12313,"deleted_at":null,"created_at":"2021-11-26T01:04:14.000000Z","updated_at":"2021-11-26T01:04:14.000000Z"},"user":{"id":2,"slug":"sasuke-uchiha","first_name":"Sasuke","middle_name":"","last_name":"Uchiha","image":null,"dob":null,"gender":null,"google_id":null,"facebook_id":"amit@facebook.com","username":"sasuke","phone":"9816810976","email":"sasuke@gmail.com","location":{"home":{"name":"New Baneshwor, Kathmandu","latitude":27.691153232923103,"longitude":85.33177163310808},"work":{"name":"Sanepa, Lalitpur","latitude":27.687052088825897,"longitude":85.30439019937253}},"status":null,"email_verified_at":null,"last_logged_in":null,"no_of_logins":null,"avatar":null,"device_token":null,"deleted_at":null,"last_updated_by":null,"last_deleted_by":null,"created_at":"2021-11-25T12:16:44.000000Z","updated_at":"2021-11-25T12:31:46.000000Z","name":"Sasuke Uchiha","thumbnail_path":"assets\/media\/user_placeholder.png","image_path":"assets\/media\/user_placeholder.png"}}},"first_page_url":"http:\/\/127.0.0.1:8000\/api\/rider\/booking\/history?page=1","from":1,"last_page":1,"last_page_url":"http:\/\/127.0.0.1:8000\/api\/rider\/booking\/history?page=1","links":{{"url":null,"label":"« Previous","active":false},{"url":"http:\/\/127.0.0.1:8000\/api\/rider\/booking\/history?page=1","label":"1","active":true},{"url":null,"label":"Next »","active":false}},"next_page_url":null,"path":"http:\/\/127.0.0.1:8000\/api\/rider\/booking\/history","per_page":5,"prev_page_url":null,"to":1,"total":1}}
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
        $completed_trips = CompletedTrip::where('rider_id',$user->rider->id)->with('location')->with('user')->paginate(5);

        $response = ['message' => 'Success!',  "completed_trips"=>$completed_trips];
        return response($response, 200);

    }


    
    /**
    * @OA\Get(
    *   path="/api/user/vehicle_type/{vehicle_type_id}/booking/{booking_status}/history",
    *   tags={"Booking"},
    *   summary="Completed/Cancelled User Booking History for Specific Vehicle Type",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="vehicle_type_id",
    *         in="path",
    *         description="Vehicle Type ID [Accepted Value: Valid Vehicle Type's id]",
    *         required=true,
    *      ),
    *      @OA\Parameter(
    *         name="booking_status",
    *         in="path",
    *         description="Booking Status [Accepted Values: 'completed', 'cancelled' ]",
    *         required=true,
    *      ),
    *
    *
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *                @OA\Schema(      
    *                   example={"message":"Success!","completed_trips":{"current_page":1,"data":{{"id":13,"user_id":2,"rider_id":null,"booking_id":2,"location_id":2,"start_time":"2021-11-27 16:36:23","end_time":"2021-11-27 16:36:23","origin":"Sanepa, Lalitpur","destination":"New Baneshwor, Kathmandu","stoppage":{{"name":"Sanepa, Lalitpur","latitude":27.1234,"longitude":85.3434},{"name":"New Baneshwor, Kathmandu","latitude":28.3454,"longitude":"87.1234"}},"distance":12,"duration":20,"passenger_number":1,"profile_img_user":null,"profile_img_rider":null,"status":"cancelled","price":160,"payment_type":"CASH","cancelled_by_type":null,"cancelled_by_id":null,"cancel_message":null,"deleted_at":null,"created_at":"2021-11-26T01:04:14.000000Z","updated_at":"2021-11-27T10:51:23.000000Z","location":{"id":2,"longitude_origin":27.123456,"latitude_origin":85.123423,"longitude_destination":27.234325,"latitude_destination":86.12313,"deleted_at":null,"created_at":"2021-11-26T01:04:14.000000Z","updated_at":"2021-11-26T01:04:14.000000Z"},"user":{"id":2,"slug":"sasuke-uchiha","first_name":"Sasuke","middle_name":"","last_name":"Uchiha","image":null,"dob":null,"gender":null,"google_id":null,"facebook_id":"amit@facebook.com","username":"sasuke","phone":"9816810976","email":"sasuke@gmail.com","location":{"home":{"name":"New Baneshwor, Kathmandu","latitude":27.691153232923103,"longitude":85.33177163310808},"work":{"name":"Sanepa, Lalitpur","latitude":27.687052088825897,"longitude":85.30439019937253}},"status":null,"email_verified_at":null,"last_logged_in":null,"no_of_logins":null,"avatar":null,"device_token":null,"deleted_at":null,"last_updated_by":null,"last_deleted_by":null,"created_at":"2021-11-25T12:16:44.000000Z","updated_at":"2021-11-25T12:31:46.000000Z","name":"Sasuke Uchiha","thumbnail_path":"assets\/media\/user_placeholder.png","image_path":"assets\/media\/user_placeholder.png"}}},"first_page_url":"http:\/\/127.0.0.1:8000\/api\/rider\/booking\/history?page=1","from":1,"last_page":1,"last_page_url":"http:\/\/127.0.0.1:8000\/api\/rider\/booking\/history?page=1","links":{{"url":null,"label":"« Previous","active":false},{"url":"http:\/\/127.0.0.1:8000\/api\/rider\/booking\/history?page=1","label":"1","active":true},{"url":null,"label":"Next »","active":false}},"next_page_url":null,"path":"http:\/\/127.0.0.1:8000\/api\/rider\/booking\/history","per_page":5,"prev_page_url":null,"to":1,"total":1}}
    *                 )
    *      )
    *   ),
    *   @OA\Response(
    *      response=422,
    *       description="Vehicle Type Not Found! OR Invalid Booking Status!",
    *   ),
    *   @OA\Response(
    *      response=403,
    *       description="Forbidden Access!",
    *   )
    *)
    **/
    public function getUserVehicleTypeBookingHistory($vehicle_type_id, $booking_status)
    {

        //Validate vehicle type id and booking status
        if( !in_array($vehicle_type_id, VehicleType::pluck('id')->toArray()  ) )
        {
            $response = ['message' => 'Vehicle Type Not Found!'];
            return response($response, 422);
        }
        if( !in_array($booking_status, ['completed','cancelled']  ) )
        {
            $response = ['message' => 'Invalid Booking Status! Select one from "completed" or "cancelled"!'];
            return response($response, 422);
        }

        $user = Auth::user();

        //ROLE CHECK FOR CUSTOMER
        if( ! $this->user_service->hasRole($user, 'customer') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
          

        //Fetch booking history/ completed trips
        $completed_trips = CompletedTrip::where('user_id',$user->id)
                                        ->where('status',$booking_status)
                                        ->whereRelation('booking','vehicle_type_id',$vehicle_type_id)
                                        ->with('location')->with('rider')->paginate(5);

        $response = ['message' => 'Success!',  "completed_trips"=>$completed_trips];
        return response($response, 200);
    }



    /**
    * @OA\Get(
    *   path="/api/rider/vehicle_type/{vehicle_type_id}/booking/{booking_status}/history",
    *   tags={"Booking"},
    *   summary="Completed/Cancelled Rider Booking History for Specific Vehicle Type",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="vehicle_type_id",
    *         in="path",
    *         description="Vehicle Type ID [Accepted Value: Valid Vehicle Type's id]",
    *         required=true,
    *      ),
    *      @OA\Parameter(
    *         name="booking_status",
    *         in="path",
    *         description="Booking Status [Accepted Values: 'completed', 'cancelled' ]",
    *         required=true,
    *      ),
    *
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *                @OA\Schema(      
    *                   example={"message":"Success!","completed_trips":{"current_page":1,"data":{{"id":13,"user_id":2,"rider_id":null,"booking_id":2,"location_id":2,"start_time":"2021-11-27 16:36:23","end_time":"2021-11-27 16:36:23","origin":"Sanepa, Lalitpur","destination":"New Baneshwor, Kathmandu","stoppage":{{"name":"Sanepa, Lalitpur","latitude":27.1234,"longitude":85.3434},{"name":"New Baneshwor, Kathmandu","latitude":28.3454,"longitude":"87.1234"}},"distance":12,"duration":20,"passenger_number":1,"profile_img_user":null,"profile_img_rider":null,"status":"cancelled","price":160,"payment_type":"CASH","cancelled_by_type":null,"cancelled_by_id":null,"cancel_message":null,"deleted_at":null,"created_at":"2021-11-26T01:04:14.000000Z","updated_at":"2021-11-27T10:51:23.000000Z","location":{"id":2,"longitude_origin":27.123456,"latitude_origin":85.123423,"longitude_destination":27.234325,"latitude_destination":86.12313,"deleted_at":null,"created_at":"2021-11-26T01:04:14.000000Z","updated_at":"2021-11-26T01:04:14.000000Z"},"user":{"id":2,"slug":"sasuke-uchiha","first_name":"Sasuke","middle_name":"","last_name":"Uchiha","image":null,"dob":null,"gender":null,"google_id":null,"facebook_id":"amit@facebook.com","username":"sasuke","phone":"9816810976","email":"sasuke@gmail.com","location":{"home":{"name":"New Baneshwor, Kathmandu","latitude":27.691153232923103,"longitude":85.33177163310808},"work":{"name":"Sanepa, Lalitpur","latitude":27.687052088825897,"longitude":85.30439019937253}},"status":null,"email_verified_at":null,"last_logged_in":null,"no_of_logins":null,"avatar":null,"device_token":null,"deleted_at":null,"last_updated_by":null,"last_deleted_by":null,"created_at":"2021-11-25T12:16:44.000000Z","updated_at":"2021-11-25T12:31:46.000000Z","name":"Sasuke Uchiha","thumbnail_path":"assets\/media\/user_placeholder.png","image_path":"assets\/media\/user_placeholder.png"}}},"first_page_url":"http:\/\/127.0.0.1:8000\/api\/rider\/booking\/history?page=1","from":1,"last_page":1,"last_page_url":"http:\/\/127.0.0.1:8000\/api\/rider\/booking\/history?page=1","links":{{"url":null,"label":"« Previous","active":false},{"url":"http:\/\/127.0.0.1:8000\/api\/rider\/booking\/history?page=1","label":"1","active":true},{"url":null,"label":"Next »","active":false}},"next_page_url":null,"path":"http:\/\/127.0.0.1:8000\/api\/rider\/booking\/history","per_page":5,"prev_page_url":null,"to":1,"total":1}}
    *                 )
    *      )
    *   ),
    *   @OA\Response(
    *      response=422,
    *       description="Vehicle Type Not Found! OR Invalid Booking Status!"
    *   ),
    *   @OA\Response(
    *      response=403,
    *       description="Forbidden Access!"
    *   )
    *)
    **/
    public function getRiderVehicleTypeBookingHistory($vehicle_type_id, $booking_status)
    {

        //Validate vehicle type id and booking status
        if( !in_array($vehicle_type_id, VehicleType::pluck('id')->toArray()  ) )
        {
            $response = ['message' => 'Vehicle Type Not Found!'];
            return response($response, 422);
        }
        if( !in_array($booking_status, ['completed','cancelled']  ) )
        {
            $response = ['message' => 'Invalid Booking Status! Select one from "completed" or "cancelled"!'];
            return response($response, 422);
        }

        $user = Auth::user();

        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') || !$user->rider )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
            
        //   dd($user->rider->id);

        //Fetch booking history/ completed trips
        $completed_trips = CompletedTrip::where('rider_id',$user->rider->id)
                                        ->where('status',$booking_status)
                                        ->whereRelation('booking','vehicle_type_id',$vehicle_type_id)
                                        ->with('location')->with('rider')->paginate(5);

        $response = ['message' => 'Success!',  "completed_trips"=>$completed_trips];
        return response($response, 200);
    }





    public function getTotalDistance($user_type)
    {
        $allowed_user_types = Roles::pluck('name')->toArray();
    }




}
