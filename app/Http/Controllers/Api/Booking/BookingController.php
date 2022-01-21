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
use App\Modules\Models\RiderLocation;

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
    *                  "voucher":"#9816810976C",
    *                  "distance":9520,
    *                   "price": 160,
    *                  "duration":20,
    *                   "stoppage":{
    *                       {"name":"Sanepa, Lalitpur", "latitude":27.1234,"longitude":85.3434},
    *                       {"name":"New Baneshwor, Kathmandu", "latitude":28.3454,"longitude":87.1234},
    *                   },
    *                   "location": {"origin":{"latitude":27.7278388,"longitude":85.37820680000002,"name":"Jorpati, Nepal"},"destination":{"latitude":27.6515356,"longitude":85.327837,"name":"Satdobato, Lalitpur, Nepal"}}
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
    *                       "message": "Booking Successful!",
    *                       "booking": {
    *                           "origin": "Sanepa, Lalitpur",
    *                           "destination": "New Baneshwor, Kathmandu",
    *                           "passenger_number": 2,
    *                           "vehicle_type_id": 1,
    *                           "distance": 4500,
    *                           "price": 160,
    *                           "duration": 20,
    *                           "stoppage": {
    *                               {
    *                                   "name": "Sanepa, Lalitpur",
    *                                   "latitude": 27.1234,
    *                                   "longitude": 85.3434
    *                               },
    *                               {
    *                                   "name": "New Baneshwor, Kathmandu",
    *                                   "latitude": 28.3454,
    *                                   "longitude": 87.1234
    *                               }
    *                           },
    *                           "user_id": 2,
    *                           "status": "pending",
    *                           "rider_id": null,
    *                           "trip_id": "#Q78A8LU",
    *                           "updated_at": "2021-12-27T05:40:03.000000Z",
    *                           "created_at": "2021-12-27T05:40:03.000000Z",
    *                           "id": 2,
    *                            "location": {"origin":{"latitude":27.7278388,"longitude":85.37820680000002,"name":"Jorpati, Nepal"},"destination":{"latitude":27.6515356,"longitude":85.327837,"name":"Satdobato, Lalitpur, Nepal"}},
    *                           "status_text": "Pending"
    *                       }
    *                   }
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
        $active_bookings = Booking::where('user_id', $user->id)->where(function ($query) {
            $query->where('status', 'pending')
                ->orWhere('status', 'accepted')
                ->orWhere('status', 'running');
        })->count();

        if ($active_bookings > 0) {
            $response = ['message' => 'You already have existing active bookings!'];
            return response($response, 400);
        }


        //dd("Booking DATA: ", $request->all());
        if ($user) {

            //ROLE CHECK FOR CUSTOMER
            if (!$this->user_service->hasRole($user, 'customer')) {
                $response = ['message' => 'Forbidden Access!'];
                return response($response, 403);
            }

            //BOOKING STORE
            return DB::transaction(function () use ($request, $user) {
                $createdBooking = $this->booking->create($user->id, $request->all());
                if($createdBooking)
                {
                    $booking = Booking::where('id',$createdBooking->id)->with('price_detail')->with('user:id,first_name,last_name,image')->first();
                    $response = ['message' => 'Booking Successful!',  "booking"=>$booking];
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
    *   summary="Change Status (Needs location data and distance for completed status)",
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
    *                       "location": {"origin":{"latitude":27.7278388,"longitude":85.37820680000002,"name":"Jorpati, Nepal"},"destination":{"latitude":27.6515356,"longitude":85.327837,"name":"Satdobato, Lalitpur, Nepal"}}, 
    *                       "distance":5600
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
    *                         "message": "Booking Status Updated Successfully! Created Completed Booking History",
    *                         "completed_trip": {
    *                           "id": 3,
    *                           "user_id": 2,
    *                           "rider_id": 1,
    *                           "trip_id": "#Q78A8LU",
    *                           "booking_id": 16,
    *                           "start_time": "2021-12-23 14:21:58",
    *                           "end_time": "2021-12-23 14:22:04",
    *                           "origin": "Sanepa, Lalitpur",
    *                           "destination": "New Baneshwor, Kathmandu",
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
    *                           "distance": 5600,
    *                            "location": {"origin":{"latitude":27.7278388,"longitude":85.37820680000002,"name":"Jorpati, Nepal"},"destination":{"latitude":27.6515356,"longitude":85.327837,"name":"Satdobato, Lalitpur, Nepal"}},
    *                           "duration": 6,
    *                           "passenger_number": 1,
    *                           "profile_img_user": null,
    *                           "profile_img_rider": null,
    *                           "status": "completed",
    *                           "price": 159,
    *                           "payment_type": "CASH",
    *                           "cancelled_by_type": null,
    *                           "cancelled_by_id": null,
    *                           "cancel_message": null,
    *                           "deleted_at": null,
    *                           "created_at": "2021-12-23T02:51:15.000000Z",
    *                           "updated_at": "2021-12-23T02:52:04.000000Z",
    *                           "payment": {
    *                             "id": 3,
    *                             "completed_trip_id": 3,
    *                             "commission_amount": 24,
    *                             "payment_status": "unpaid",
    *                             "commission_payment_status": "unpaid",
    *                             "deleted_at": null,
    *                             "created_at": "2021-12-23T08:37:04.000000Z",
    *                             "updated_at": "2021-12-23T08:37:04.000000Z"
    *                           },
    *                           "user": {
    *                             "id": 2,
    *                             "first_name": "Sasuke",
    *                             "last_name": "Uchiha",
    *                             "image": null,
    *                             "name": "Sasuke Uchiha",
    *                             "status_text": "",
    *                             "thumbnail_path": "assets/media/user_placeholder.png",
    *                             "image_path": "assets/media/user_placeholder.png"
    *                           },
    *                           "price_detail": {
    *                             "id": 6,
    *                             "booking_id": null,
    *                             "completed_trip_id": 3,
    *                             "minimum_charge": 50,
    *                             "price_per_km": 15,
    *                             "price_after_distance": 0.18,
    *                             "surge_rate": 1,
    *                             "surge": 0,
    *                             "price_after_surge": 0.18,
    *                             "app_charge_percent": 10,
    *                             "app_charge": 0.02,
    *                             "price_after_app_charge": 0.18,
    *                             "price_per_min": 5,
    *                             "duration_charge": 0.5,
    *                             "price_after_duration": 0.68,
    *                             "total_price": 50,
    *                             "deleted_at": null,
    *                             "created_at": "2021-12-23T08:37:04.000000Z",
    *                             "updated_at": "2021-12-23T08:37:04.000000Z"
    *                           }
    *                         }
    *                       }
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
     *         description="No Active booking found!"
     *      ),
     *)
     **/
    public function change_status(BookingStatusRequest $request)
    {

        //AUTHENTICATION CHECK
        $user = null;
        try {
            $user = Auth::user();
        } catch (Exception $e) {
            $response = ['message' => 'Unauthorized: Login Required!'];
            return response($response, 401);
        }
        if (!$user) {
            $response = ['message' => 'Unauthorized: Login Required!'];
            return response($response, 401);
        }
        // dd($request->all());

        $booking = Booking::find($request->booking_id);
        if ($booking  && $booking->vehicle_type_id != 4) {
            if ($request->new_status == 'accepted') {
                if ($booking->status != 'pending') {
                    $response = ['message' => 'The booking is no longer available!'];
                    return response($response, 400);
                }
            }

            if( $request->new_status == "running")
            {
                if($booking->status != 'accepted')
                {
                    $response = ['message' => 'The booking is not accepted yet or is already running/completed!'];
                    return response($response, 400);
                }
                if ($request['optional_data']['rider_id'] != $booking->rider_id) {
                    $response = ['message' => 'The rider for the booking did not match!'];
                    return response($response, 400);
                }
            }

            if( $request->new_status == "completed")
            {
                if($request->vehicle_type_id != 4 &&  ( !isset($request['optional_data']['location']) || !isset($request['optional_data']['distance']) ))
                {
                    $response = ['message' => 'The new location data and distance is required to complete the ride!'];
                    return response($response, 422);
                }

                if ($booking->status != 'running') {
                    $response = ['message' => 'The booking has not been started yet!'];
                    return response($response, 400);
                }
                if ($request['optional_data']['rider_id'] != $booking->rider_id) {
                    $response = ['message' => 'The rider for the booking did not match!'];
                    return response($response, 400);
                }
            }

            if ($booking->status == "completed" || $booking->status == "cancelled") {
                $response = ['message' => 'No active booking found!'];
                return response($response, 404);
            }
        } 
        else if ($booking && $booking->vehicle_type_id == 4) //IF AMBULANCE
        {
            if ($request->new_status == 'accepted') {
                if ($booking->status != 'pending') {
                    $response = ['message' => 'The booking is no longer available!'];
                    return response($response, 400);
                }
            }
            if ($request['new_status'] == 'running') {
                $request['new_status'] = 'completed';
                $booking->rider_id = $request['optional_data']['rider_id'];
                $booking->save();
            }
        } 
        else {
        }

        //UPDATE STATUS
        return DB::transaction(function () use ($request, $user) {
            //Check if the rider has active pending bookings while accepting or running a rider
            $new_status = $request->new_status;
            if ($new_status == "accepted") // || $new_status == "running")
            {
                $rider_id = $request->optional_data['rider_id'];
                $pending_rider_booking = null;
                
                // if($new_status == "accepted")
                // {
                    // $pending_rider_booking = Booking::where('rider_id', $rider_id)->where(function($query) {
                    //     $query->where('status','accepted')
                    //     ->orWhere('status','running');
                    // })->first();
                    //check availability of the rider
                    $rider_location = RiderLocation::where('rider_id',$rider_id)->first();
                    if(isset($rider_location))
                    {
                        if(! ( ($rider_location->status == "active") && ($rider_location->availability == "available") ) )
                        {
                            $response = ['message' => 'The rider is currently unavailable!'];
                            return response($response, 422);
                        }
                        if($rider_location->rider->status != "active")
                        {
                            $response = ['message' => 'The rider is currently inactive!'];
                            return response($response, 422);
                        }
                       
                    }
                    else{
                        $response = ['message' => 'The rider is currently unavailable!'];
                        return response($response, 422);
                    }
                    // $rider = Rider::select('id','status')->where('id',$rider_id)->first();
                    // if($rider->status != "active")
                    // {
                       
                    // }

                    // if($pending_rider_booking)
                    // {
                    //     $response = ['message' => 'The rider already have an active booking!'];
                    //     return response($response, 422);
                    // }
                    
              //  }

            }



            $updatedBooking = $this->booking->update_status($request->all());
            if($updatedBooking)
            {
                if($updatedBooking->status == "completed")
                {
                    $completed_trip = CompletedTrip::where('booking_id',$updatedBooking->id)->with('payment')
                    ->with('user:id,first_name,last_name,image')
                    ->with('booking:id,status,trip_id')
                    ->with('price_detail')
                    ->with('payment')
                    ->first();
                    $response = ['message' => 'Booking Status Updated Successfully! Created Completed Booking History', "completed_trip"=>$completed_trip];
                    return response($response, 201);
                }
                else if($updatedBooking->status == "cancelled")
                {
                    $completed_trip = CompletedTrip::where('booking_id',$updatedBooking->id)
                    ->with('user:id,first_name,last_name,image')
                    ->with('booking:id,status,trip_id')
                    ->with('price_detail')
                    ->with('payment')
                    ->first();
                    $response = ['message' => 'Booking Status Updated Successfully! Created Cancelled Booking History', "completed_trip"=>$completed_trip];
                    return response($response, 201);
                } else {
                    $response = ['message' => 'Booking Status Updated Successfully!'];
                    return response($response, 200);
                }
            }
            return response("Internal Server Error!", 500);
        });
    }


 /**
    * @OA\Get(
    *   path="/api/booking/{booking_id}",
    *   tags={"Booking"},
    *   summary="Get Booking from ID",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="booking_id",
    *         in="path",
    *         description="Booking ID",
    *         required=true,
    *      ),
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                       "message": "Success!",
    *                       "booking": {
    *                           "id": 24,
    *                           "stoppage": {
    *                               {
    *                                   "name": "Sanepa, Lalitpur",
    *                                   "latitude": 27.1234,
    *                                   "longitude": 85.3434
    *                               },
    *                               {
    *                                   "name": "New Baneshwor, Kathmandu",
    *                                   "latitude": 28.3454,
    *                                   "longitude": 87.1234
    *                               }
    *                           },
    *                           "user_id": 3,
    *                           "vehicle_type_id": 1,
    *                           "rider_id": 1,
    *                           "trip_id": "#Q78A8LU",
    *                           "start_time": "2021-12-14 14:24:25",
    *                           "end_time": "2021-12-14 14:37:35",
    *                           "origin": "Sanepa, Lalitpur",
    *                           "destination": "New Baneshwor, Kathmandu",
    *                           "distance": 12,
    *                           "duration": 20,
    *                           "passenger_number": 2,
    *                           "status": "completed",
    *                           "price": 160,
    *                           "payment_type": "CASH",
    *                           "deleted_at": null,
    *                           "created_at": "2021-12-14T08:12:21.000000Z",
    *                           "updated_at": "2021-12-14T08:52:35.000000Z",
    *                            "location": {"origin":{"latitude":27.7278388,"longitude":85.37820680000002,"name":"Jorpati, Nepal"},"destination":{"latitude":27.6515356,"longitude":85.327837,"name":"Satdobato, Lalitpur, Nepal"}},
    *                           "price_detail": {
    *                               "id": 7,
    *                               "booking_id": 24,
    *                               "completed_trip_id": null,
    *                               "minimum_charge": 50,
    *                               "price_per_km": 15,
    *                               "price_after_distance": 0.18,
    *                               "surge_rate": 1,
    *                               "surge": 0,
    *                               "price_after_surge": 0.18,
    *                               "app_charge_percent": 10,
    *                               "app_charge": 0.02,
    *                               "price_after_app_charge": 0.18,
    *                               "price_per_min": 5,
    *                               "duration_charge": 1.67,
    *                               "price_after_duration": 1.85,
    *                               "total_price": 50,
    *                               "deleted_at": null,
    *                               "created_at": "2021-12-14T08:12:21.000000Z",
    *                               "updated_at": "2021-12-14T08:12:21.000000Z"
    *                           }
    *                       }
    *                   }
    *                 )
    *           )
    *      ),
    *   @OA\Response(
    *      response=404,
    *       description="Booking Not Found!",
    *   )
    *)
    **/
    public function getBooking($booking_id)
    {
        $booking = Booking::where('id',$booking_id)->with('price_detail')->first();
        if($booking) {
            $response = ['message' => 'Success!', 'booking'=>$booking];
            return response($response, 200);
        } else {
            $response = ['message' => 'Booking Not Found!'];
            return response($response, 404);
        }
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
     *                       "message": "Success!",
     *                       "booking": {
     *                           "id": 24,
     *                           "stoppage": {
     *                               {
     *                                   "name": "Sanepa, Lalitpur",
     *                                   "latitude": 27.1234,
     *                                   "longitude": 85.3434
     *                               },
     *                               {
     *                                   "name": "New Baneshwor, Kathmandu",
     *                                   "latitude": 28.3454,
     *                                   "longitude": 87.1234
     *                               }
     *                           },
     *                           "user_id": 3,
     *                           "vehicle_type_id": 1,
     *                           "rider_id": 1,
     *                           "trip_id": "#Q78A8LU",
     *                           "start_time": "2021-12-14 14:24:25",
     *                           "end_time": "2021-12-14 14:37:35",
     *                           "origin": "Sanepa, Lalitpur",
     *                           "destination": "New Baneshwor, Kathmandu",
     *                           "distance": 12,
     *                           "duration": 20,
     *                           "passenger_number": 2,
     *                           "status": "completed",
     *                           "price": 160,
     *                           "payment_type": "CASH",
     *                           "deleted_at": null,
     *                           "created_at": "2021-12-14T08:12:21.000000Z",
     *                           "updated_at": "2021-12-14T08:52:35.000000Z",
     *                            "location": {"origin":{"latitude":27.7278388,"longitude":85.37820680000002,"name":"Jorpati, Nepal"},"destination":{"latitude":27.6515356,"longitude":85.327837,"name":"Satdobato, Lalitpur, Nepal"}},
     *                           "price_detail": {
     *                               "id": 7,
     *                               "booking_id": 24,
     *                               "completed_trip_id": null,
     *                               "minimum_charge": 50,
     *                               "price_per_km": 15,
     *                               "price_after_distance": 0.18,
     *                               "surge_rate": 1,
     *                               "surge": 0,
     *                               "price_after_surge": 0.18,
     *                               "app_charge_percent": 10,
     *                               "app_charge": 0.02,
     *                               "price_after_app_charge": 0.18,
     *                               "price_per_min": 5,
     *                               "duration_charge": 1.67,
     *                               "price_after_duration": 1.85,
     *                               "total_price": 50,
     *                               "deleted_at": null,
     *                               "created_at": "2021-12-14T08:12:21.000000Z",
     *                               "updated_at": "2021-12-14T08:12:21.000000Z"
     *                           }
     *                       }
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
        if (!$this->user_service->hasRole($user, 'customer')) {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        $booking =  $this->booking->active_user_booking($user->id);
        if ($booking) {
            $response = ['message' => 'Success!', "booking" => $booking];
            return response($response, 200);
        } else {
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
     *                               "trip_id": "#Q78A8LU",
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
     *                               "location": {"origin":{"latitude":27.7278388,"longitude":85.37820680000002,"name":"Jorpati, Nepal"},"destination":{"latitude":27.6515356,"longitude":85.327837,"name":"Satdobato, Lalitpur, Nepal"}},
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
        if (!$this->user_service->hasRole($user, 'rider')) {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        $booking = $this->booking->active_rider_booking($user->rider->id);
        if ($booking) {
            $response = ['message' => 'Success!', "booking" => $booking];
            return response($response, 200);
        } else {
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
     *                 "origin_latitude": 27.665898, 
     *                 "origin_longitude":85.325540,
     *                 "distance":2000,
     *                  "duration":300,
     *                  "voucher":"#9816810976C",
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
     *                   example=
     *                       {
     *                         "message": "Success!",
     *                         "estimated_price": {
     *                           {
     *                             "vehicle_type_id": 1,
     *                             "vehicle_type_name": "bike",
     *                             "shift": "shift_surge",
     *                             "price_breakdown": {
     *                               "base_fare": 30,
     *                               "base_covered_km": 2,
     *                               "minimum_charge": 50,
     *                               "price_per_km": 17,
     *                               "charged_km": 0,
     *                               "price_after_distance": 0,
     *                               "shift_surge": 0,
     *                               "density_surge": 0,
     *                               "surge_rate": 1,
     *                               "price_per_km_after_surge": 17,
     *                               "surge": 0,
     *                               "price_after_surge": 0,
     *                               "app_charge_percent": 10,
     *                               "app_charge": 0,
     *                               "price_after_app_charge": 0,
     *                               "price_per_min": 0,
     *                               "price_per_min_after_base": 1,
     *                               "duration_charge": 0,
     *                               "price_after_duration": 0,
     *                               "price_after_base_fare": 30,
     *                               "total_price": 50
     *                             }
     *                           },
     *                           {
     *                             "vehicle_type_id": 2,
     *                             "vehicle_type_name": "car",
     *                             "shift": "shift_surge",
     *                             "price_breakdown": {
     *                               "base_fare": 100,
     *                               "base_covered_km": 2,
     *                               "minimum_charge": 150,
     *                               "price_per_km": 42,
     *                               "charged_km": 0,
     *                               "price_after_distance": 0,
     *                               "shift_surge": 0,
     *                               "density_surge": 0,
     *                               "surge_rate": 1,
     *                               "price_per_km_after_surge": 42,
     *                               "surge": 0,
     *                               "price_after_surge": 0,
     *                               "app_charge_percent": 10,
     *                               "app_charge": 0,
     *                               "price_after_app_charge": 0,
     *                               "price_per_min": 2,
     *                               "price_per_min_after_base": 2,
     *                               "duration_charge": 10,
     *                               "price_after_duration": 10,
     *                               "price_after_base_fare": 110,
     *                               "total_price": 150
     *                             }
     *                           },
     *                           {
     *                             "vehicle_type_id": 3,
     *                             "vehicle_type_name": "city_safari",
     *                             "shift": "shift_surge",
     *                             "price_breakdown": {
     *                               "base_fare": 10,
     *                               "base_covered_km": 1,
     *                               "minimum_charge": 20,
     *                               "price_per_km": 10,
     *                               "charged_km": 1,
     *                               "price_after_distance": 10,
     *                               "shift_surge": 0,
     *                               "density_surge": 0,
     *                               "surge_rate": 1,
     *                               "price_per_km_after_surge": 10,
     *                               "surge": 0,
     *                               "price_after_surge": 10,
     *                               "app_charge_percent": 10,
     *                               "app_charge": 1,
     *                               "price_after_app_charge": 11,
     *                               "price_per_min": 2,
     *                               "price_per_min_after_base": 2,
     *                               "duration_charge": 10,
     *                               "price_after_duration": 21,
     *                               "price_after_base_fare": 31,
     *                               "total_price": 31
     *                             }
     *                           }
     *                         }
     *                       }
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

        try {
            $request['user_id'] = Auth::user()->id;
            $estimated_price = $this->booking->get_estimated_price($request->all());
            if ($estimated_price) {
                $response = ['message' => 'Success!',  "estimated_price" => $estimated_price,];
                return response($response, 200);
            }
        } catch (Exception $e) {
            $response = ['message' => 'Internal Server Error!'];
            return response($response, 500);
        }
    }





    /**
    * @OA\Get(
    *   path="/api/booking/{booking_id}/timed_out",
    *   tags={"Booking"},
    *   summary="Booking Timed Out",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="booking_id",
    *         in="path",
    *         description="Booking ID",
    *         required=true,
    *      ),
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                     "message": "Admin notified successfully! Wait for the response! "
    *                   }
    *                 )
    *           )
    *      ),
        *
    *      @OA\Response(
    *          response=422,
    *          description="Booking not found!",
    *             @OA\MediaType(
     *           mediaType="application/json",
     *      )
    *      ),
    *
    *)
    **/
    function timed_out($booking_id)
    {
        $booking = Booking::find($booking_id);
        if(!$booking)
        { 
            $response = ['message' => 'Booking not found!'];;
            return response($response, 422);
        }
        if($booking->status != "pending")
        {
            $response = ['message' => 'Booking is no longer pending!'];;
            return response($response, 422);
        }

        // if($booking->status != 'pending')
        // {
        //     $response = ['message' => 'Booking is not longer pending!'];;
        //     return response($response, 422);
        // }


        $adminNotified = $this->booking->notify_booking_timed_out($booking_id);
        if($adminNotified)
        {
            $response = ['message' => 'Admin Notified Successfully!'];;
            return response($response, 200);
        }

    }





    
    /**
    * @OA\Get(
    *   path="/api/user/{user_type}/services/used",
    *   tags={"Booking"},
    *   summary="Used Services",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="user_type",
    *         in="path",
    *         description="User Type (Allowed values: customer or rider)",
    *         required=true,
    *      ),
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                     "message": "Admin notified successfully! Wait for the response! "
    *                   }
    *                 )
    *           )
    *      ),
    *
    *         @OA\Response(
    *             response=403,
    *             description="Forbidden Access!"
    *         ),
    *      @OA\Response(
    *          response=422,
    *          description="Invalid User Type!",
    *             @OA\MediaType(
     *           mediaType="application/json",
     *      )
    *      ),
    *
    *)
    **/
    function check_used_service($user_type)
    {
      
        // if(!($user_type=="customer" || $user_type=="rider"))
        // {
        //     $response = ['message' => 'Invalid User Type!'];;
        //     return response($response, 422);
        // }

        $user = Auth::user();

        //ROLE CHECK FOR RIDER
        if (!$this->user_service->hasRole($user, 'rider') && $user_type=="rider") {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        $result = [];
        $vehicle_types = VehicleType::select('id','name')->get();
        if($user_type=="rider") {
            foreach($vehicle_types as $vehicle_type){
                $data['id'] = $vehicle_type->id;
                $data['name'] = $vehicle_type->name;

                $completed = CompletedTrip::where('rider_id',$user->rider->id)->whereRelation('booking','vehicle_type_id',$vehicle_type->id)->where('status','completed')->get()->count();
                $cancelled = CompletedTrip::where('rider_id',$user->rider->id)->whereRelation('booking','vehicle_type_id',$vehicle_type->id)->where('status','cancelled')->get()->count();

                $data['completed'] = ($completed>0)?true:false;
                $data['cancelled'] = ($cancelled>0)?true:false;
                $data['used'] = ($completed || $cancelled)?true:false;

                $result[] = $data;
            }
        }
        else if($user_type=="customer"){
            foreach($vehicle_types as $vehicle_type){
                $data['id'] = $vehicle_type->id;
                $data['name'] = $vehicle_type->name;

                $completed = CompletedTrip::where('user_id',$user->id)->whereRelation('booking','vehicle_type_id',$vehicle_type->id)->where('status','completed')->get()->count();
                $cancelled = CompletedTrip::where('user_id',$user->id)->whereRelation('booking','vehicle_type_id',$vehicle_type->id)->where('status','cancelled')->get()->count();

                $data['completed'] = ($completed>0)?true:false;
                $data['cancelled'] = ($cancelled>0)?true:false;
                $data['used'] = ($completed || $cancelled)?true:false;

                $result[] = $data;
            }
        }
        else{
            $response = ['message' => 'Invalid User Type!'];;
            return response($response, 422);
        }

      
        $response = ['message' => 'Success!','data'=>$result];;
        return response($response, 200);
        

    }


    public function testFunction()
    {
        dd(\App\Modules\Models\PriceDetail::all()->toArray());
    }
}
