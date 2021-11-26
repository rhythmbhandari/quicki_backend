<?php

namespace App\Http\Controllers\Api\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

//requests
use App\Http\Requests\Api\Booking\BookingRequest;
use App\Http\Requests\Api\Booking\EstimatedPriceRequest;
use App\Http\Requests\Api\Booking\BookingStatusRequest;

//services
use App\Modules\Services\Booking\BookingService;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\Booking;
use App\Modules\Models\User;
use App\Modules\Models\Rider;
use App\Modules\Models\VehicleType;
use App\Modules\Models\Shift;
use App\Modules\Models\CompletedTrip;

class BookingController extends Controller
{
    protected $booking, $user_service;

    public function __construct(BookingService $booking, UserService $user_service)
    {
        $this->booking = $booking;
        $this->user_service = $user_service;
    }
  

    /**
    * @OA\Post(
    *   path="/api/booking/create",
    *   tags={"Booking"},
    *   summary="Create Booking",
    *   security={{"bearerAuth":{}}},
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *         mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "origin":"Sanepa, Lalitpur",
    *                  "destination":"New Baneshwor, Kathmandu",
    *                  "passenger_number":2,
    *                  "vehicle_type_id":1,
    *                  "distance":12,
    *                   "price": 160,
    *                  "duration":20,
    *                   "stoppage":{
    *                       {"name":"Sanepa, Lalitpur", "latitude":27.1234,"longitude":85.3434},
    *                       {"name":"New Baneshwor, Kathmandu", "latitude":28.3454,"longitude":87.1234},
    *                   },
    *                  "location":{
    *                       "latitude_origin":85.123423,
    *                       "longitude_origin":27.123456,
    *                       "latitude_destination":86.12313,
    *                       "longitude_destination":27.234325,
    *                   },
    *               }
    *         )
    *     )
    *   ),
    *
    *      @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                         "message": "Booking Successful!",
    *                         "booking": {
    *                           "origin": "Sanepa, Lalitpur",
    *                           "destination": "New Baneshwor, Kathmandu",
    *                           "passenger_number": 2,
    *                           "vehicle_type_id": 1,
    *                           "distance": 12,
    *                           "duration": 20,
    *                           "price": 160,
    *                           "stoppage": {
    *                             {
    *                               "name": "Sanepa, Lalitpur",
    *                               "latitude": 27.1234,
    *                               "longitude": 85.3434
    *                             },
    *                             {
    *                               "name": "New Baneshwor, Kathmandu",
    *                               "latitude": 28.3454,
    *                               "longitude": 87.1234
    *                             }
    *                           },
    *                           "user_id": 3,
    *                           "status": "pending",
    *                           "updated_at": "2021-11-21T07:42:14.000000Z",
    *                           "created_at": "2021-11-21T07:42:14.000000Z",
    *                           "id": 1,
    *                           "location_id": 1,
    *                           "location": {
    *                             "latitude_origin": 85.123423,
    *                             "longitude_origin": 27.123456,
    *                             "latitude_destination": 86.12313,
    *                             "longitude_destination": 27.234325,
    *                             "updated_at": "2021-11-21T07:42:14.000000Z",
    *                             "created_at": "2021-11-21T07:42:14.000000Z",
    *                             "id": 1
    *                           }
    *                         }
    *                       }
    *                 )
    *           )
    *      ),
    *       @OA\Response(
    *             response=400,
    *             description="You already have existing active bookings!"
    *         ),
    *       @OA\Response(
    *             response=401,
    *             description="Unauthenticated"
    *         ),
    *         @OA\Response(
    *             response=403,
    *             description="Forbidden Access: Booking is off limits!"
    *         ),
    *      @OA\Response(
    *          response=422,
    *          description="The given data was invalid!",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
    *              mediaType="application/json",
    *          )
    *      ),
    *       @OA\Response(
    *         response=404,
    *         description="No Record found!"
    *      ),
    *)
    **/
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookingRequest $request)
    {
       
        $user = Auth::user();


        //CHECK IF USER HAVE EXISTING ACTIVE BOOKINGS
        $active_bookings = Booking::where('user_id',$user->id)->where(function($query){
            $query->where('status','pending')
            ->orWhere('status','accepted')
            ->orWhere('status','running');
        })->count();

        if($active_bookings > 0)
        {
            $response = ['message' => 'You already have existing active bookings!'];
            return response($response, 400);
        }


        //dd("Booking DATA: ", $request->all());
        if ($user) {

            //ROLE CHECK FOR CUSTOMER
            if( ! $this->user_service->hasRole($user, 'customer') )
            {
                $response = ['message' => 'Forbidden Access!'];
                return response($response, 403);
            }

            //BOOKING STORE
            return DB::transaction(function () use ($request, $user)
            {
                $createdBooking = $this->booking->create($user->id, $request->all());
                if($createdBooking)
                {
                    $response = ['message' => 'Booking Successful!',  "booking"=>$createdBooking,];
                    return response($response, 201);
                }
                return response("Internal Server Error!", 500);
            });

        } 
    }


        /**
    * @OA\Post(
    *   path="/api/booking/change_status",
    *   tags={"Booking"},
    *   summary="Change Status",
    *   security={{"bearerAuth":{}}},
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *         mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                 "booking_id":1,
    *                  "new_status":"accepted",
    *                  "optional_data":{
    *                       "rider_id":1,
    *                       "cancelled_by_id":1,
    *                       "cancelled_by_type":"customer",
    *                       "cancel_message":"Timeout or somethin!",
    *                   },
    *               }
    *         )
    *     )
    *   ),
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                           "message":"Status Updated!",
    *                   }
    *                 )
    *           )
    *      ),
    *      @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                           "message":"Booking Status Updated Successfully! Created Completed Booking History",
    *                           "completed_trip":
    *                               {
    *                               "id": 4,
    *                               "user_id": 3,
    *                               "rider_id": null,
    *                               "booking_id": 2,
    *                               "location_id": 2,
    *                               "origin": "Sanepa, Lalitpur",
    *                               "destination": "New Baneshwor, Kathmandu",
    *                               "stoppage": {
    *                                 {
    *                                   "name": "Sanepa, Lalitpur",
    *                                   "latitude": 27.1234,
    *                                   "longitude": 85.3434
    *                                 },
    *                                 {
    *                                   "name": "New Baneshwor, Kathmandu",
    *                                   "latitude": 28.3454,
    *                                   "longitude": 87.1234
    *                                 }
    *                               },
    *                               "distance": "12",
    *                               "duration": "20",
    *                               "passenger_number": 1,
    *                               "profile_img_user": null,
    *                               "profile_img_rider": null,
    *                               "status": "completed",
    *                               "cancelled_by_type": null,
    *                               "cancelled_by_id": null,
    *                               "cancel_message": null,
    *                               "deleted_at": null,
    *                               "created_at": "2021-11-17T06:43:25.000000Z",
    *                               "updated_at": "2021-11-17T06:43:58.000000Z"
    *                             },
    *                   }
    *                 )
    *           )
    *      ),
    *       @OA\Response(
    *             response=401,
    *             description="Unauthenticated"
    *         ),
    *         @OA\Response(
    *             response=403,
    *             description="Forbidden Access: Booking is off limits!"
    *         ),
    *      @OA\Response(
    *          response=422,
    *          description="The given data was invalid!",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
    *              mediaType="application/json",
    *          )
    *      ),
    *       @OA\Response(
    *         response=404,
    *         description="No Record found!"
    *      ),
    *)
    **/
    public function change_status(BookingStatusRequest $request)
    {

        //AUTHENTICATION CHECK
        $user = null;
        try{
            $user = Auth::user();
        }
        catch(Exception $e)
        {
            $response = ['message' => 'Unauthorized: Login Required!'];
            return response($response, 401);
        }
        if(!$user)
        {
            $response = ['message' => 'Unauthorized: Login Required!'];
            return response($response, 401);
        }
       // dd($request->all());

     

        //UPDATE STATUS
        return DB::transaction(function () use ($request, $user)
        {
            $updatedBooking = $this->booking->update_status($request->all());
            if($updatedBooking)
            {
                if($updatedBooking->status == "completed")
                {
                    $completed_trip = CompletedTrip::where('booking_id',$updatedBooking->id)->first();
                    $response = ['message' => 'Booking Status Updated Successfully! Created Completed Booking History', "completed_trip"=>$updatedBooking->completed_trip];
                    return response($response, 201);
                }
                else if($updatedBooking->status == "cancelled")
                {
                    $completed_trip = CompletedTrip::where('booking_id',$updatedBooking->id)->first();
                    $response = ['message' => 'Booking Status Updated Successfully! Created Cancelled Booking History', "completed_trip"=>$updatedBooking->completed_trip];
                    return response($response, 201);
                }
                else{
                    $response = ['message' => 'Booking Status Updated Successfully!'];
                    return response($response, 200);
                }
                
            }
            return response("Internal Server Error!", 500);
        });
        

    }


    /**
    * @OA\Get(
    *   path="/api/user/booking/active",
    *   tags={"Booking"},
    *   summary="Active User Booking",
    *   security={{"bearerAuth":{}}},
    *
   *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                     "message": "Success!",
    *                     "booking": {
    *                       "id": 1,
    *                       "stoppage": {
    *                         {
    *                           "name": "Sanepa, Lalitpur",
    *                           "latitude": 27.1234,
    *                           "longitude": "85.3434"
    *                         },
    *                         {
    *                           "name": "New Baneshwor, Kathmandu",
    *                           "latitude": 28.3454,
    *                           "longitude": 87.1234
    *                         }
    *                       },
    *                       "user_id": 3,
    *                       "vehicle_type_id": 1,
    *                       "rider_id": null,
    *                       "location_id": 1,
    *                       "start_time": null,
    *                       "end_time": null,
    *                       "origin": "Sanepa, Lalitpur",
    *                       "destination": "New Baneshwor, Kathmandu",
    *                       "distance": 12,
    *                       "duration": 20,
    *                       "passenger_number": 2,
    *                       "status": "pending",
    *                       "price": 160,
    *                       "payment_type": "CASH",
    *                       "deleted_at": null,
    *                       "created_at": "2021-11-25T19:07:34.000000Z",
    *                       "updated_at": "2021-11-25T19:07:34.000000Z",
    *                       "location": {
    *                         "id": 1,
    *                         "longitude_origin": 27.123456,
    *                         "latitude_origin": 85.123423,
    *                         "longitude_destination": 27.234325,
    *                         "latitude_destination": 86.12313,
    *                         "created_at": "2021-11-25T19:07:34.000000Z",
    *                         "updated_at": "2021-11-25T19:07:34.000000Z"
    *                       }
    *                     }
    *                   }
    *                 )
    *           )
    *      ),
    *   @OA\Response(
    *      response=403,
    *       description="Forbidden Access!",
    *   ),
    *   @OA\Response(
    *      response=404,
    *       description="No active bookings found!",
    *   )
    *)
    **/
    public function getActiveUserBooking()
    {
        $user = Auth::user();

        //ROLE CHECK FOR CUSTOMER
        if( ! $this->user_service->hasRole($user, 'customer') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        $booking =  $this->booking->active_user_booking($user->id);
        if($booking)
        {
            $response = ['message' => 'Success!', "booking"=>$booking];
            return response($response, 200);
        }
        else{
            $response = ['message' => 'No active bookings found!'];
            return response($response, 404);
        }
    }

    /**
    * @OA\Get(
    *   path="/api/rider/booking/active",
    *   tags={"Booking"},
    *   summary="Active Rider Booking",
    *   security={{"bearerAuth":{}}},
    *
   *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                           "message":"Booking Successful!",
    *                           "booking":{
    *                             "message": "Booking Successful!",
    *                             "booking": {
    *                               "origin": "Patan, Lalitpur",
    *                               "destination": "Kirtipur, Kathmandu",
    *                               "passenger_number": 2,
    *                               "vehicle_type_id": 1,
    *                               "distance": 12,
    *                               "duration": 20,
    *                               "stoppage": {
    *                                 {
    *                                   "name": "Sanepa, Lalitpur",
    *                                   "latitude": 27.1234,
    *                                   "longitude": 85.3434
    *                                 },
    *                                 {
    *                                   "name": "New Baneshwor, Kathmandu",
    *                                   "latitude": 28.3454,
    *                                   "longitude": 87.1234
    *                                 }
    *                               },
    *                               "user_id": 3,
    *                               "updated_at": "2021-11-17T06:46:13.000000Z",
    *                               "created_at": "2021-11-17T06:46:13.000000Z",
    *                               "id": 3,
    *                               "location_id": 3,
    *                               "location": {
    *                                 "latitude_origin": 85.123423,
    *                                 "longitude_origin": 27.123456,
    *                                 "latitude_destination": 86.12313,
    *                                 "longitude_destination": 27.234325,
    *                                 "updated_at": "2021-11-17T06:46:13.000000Z",
    *                                 "created_at": "2021-11-17T06:46:13.000000Z",
    *                                 "id": 3
    *                               }
    *                             }
    *                           },
    *                   }
    *                 )
    *           )
    *      ),
    *   @OA\Response(
    *      response=403,
    *       description="Forbidden Access!",
    *   ),
    *   @OA\Response(
    *      response=404,
    *       description="No active bookings found!",
    *   )
    *)
    **/
    public function getActiveRiderBooking()
    {
        $user = Auth::user();

        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        $booking = $this->booking->active_rider_booking($user->rider->id);
        if($booking)
        {
            $response = ['message' => 'Success!', "booking"=>$booking];
            return response($response, 200);
        }
        else{
            $response = ['message' => 'No active bookings found!'];
            return response($response, 404);
        }
    }


    /**
    * @OA\Post(
    *   path="/api/booking/estimated_price",
    *   tags={"Booking"},
    *   summary="Estimated Price (Takes => Distance in meters, Duration in seconds and Origin lat and lng values)  ",
    *   security={{"bearerAuth":{}}},
    *    @OA\RequestBody(
    *      @OA\MediaType(
    *         mediaType="application/json",
    *         @OA\Schema(
    *             example={
    *                 "origin_latitude":27.68716909705845, 
    *                 "origin_longitude":85.3042190788061,
    *                 "distance":2000,
    *                  "duration":300,
    *               }
    *         )
    *     )
    *   ),
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *               @OA\Schema(
    *                   example={"message":"Success!","estimated_price":{{"vehicle_type_id":1,"vehicle_type_name":"bike","shift":1,"price_breakdown":{"minimum_charge":150,"price_per_km":15,"price_after_distance":300,"shift_rate":1,"density_surge":0,"shift_surge":0,"surge":0,"price_after_surge":300,"app_charge_percent":10,"app_charge":30,"price_after_app_charge":300,"price_per_min":5,"duration_charge":25,"price_after_duration":325,"total_price":325}},{"vehicle_type_id":2,"vehicle_type_name":"car","shift":1,"price_breakdown":{"minimum_charge":150,"price_per_km":40,"price_after_distance":800,"shift_rate":1,"density_surge":0,"shift_surge":800,"surge":0,"price_after_surge":800,"app_charge_percent":10,"app_charge":80,"price_after_app_charge":800,"price_per_min":15,"duration_charge":75,"price_after_duration":875,"total_price":875}},{"vehicle_type_id":3,"vehicle_type_name":"city_safari","shift":1,"price_breakdown":{"minimum_charge":150,"price_per_km":25,"price_after_distance":500,"shift_rate":1,"density_surge":0,"shift_surge":500,"surge":0,"price_after_surge":500,"app_charge_percent":10,"app_charge":50,"price_after_app_charge":500,"price_per_min":10,"duration_charge":50,"price_after_duration":550,"total_price":550}}}}
    *               )
    *      )
    *   ),
    *      @OA\Response(
    *          response=422,
    *          description="The given data was invalid!",
    *             @OA\MediaType(
     *           mediaType="application/json",
     *      )
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
    public function getEstimatedPrice(EstimatedPriceRequest $request)
    {
       
        try{
            $estimated_price = $this->booking->get_estimated_price($request->all());
            if($estimated_price)
            {
                $response = ['message' => 'Success!',  "estimated_price"=>$estimated_price,];
                return response($response, 200);
            }
        }
        catch(Exception $e)
        {
            $response = ['message' => 'Internal Server Error!'];
            return response($response, 500);
        }
        
    }






   
}
