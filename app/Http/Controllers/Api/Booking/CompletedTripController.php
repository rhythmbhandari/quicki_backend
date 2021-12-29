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
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *                @OA\Schema(      
    *                   example=
    *                           {
    *                             "message": "Success!",
    *                             "completed_trips": {
    *                               "current_page": 1,
    *                               "data": {
    *                                 {
    *                                   "id": 2,
    *                                   "user_id": 2,
    *                                   "rider_id": 1,
    *                                   "booking_id": 15,    
    *                                    "location": {
    *                                        "origin":{
    *                                            "name": "Sanepa, Lalitpur",
    *                                            "latitude": 27.687012,
    *                                            "longitude": 85.304359
    *                                        },
    *                                        "destination":{
    *                                            "name": "New Baneshwor, Kathmandu",
    *                                            "latitude": 28.234325,
    *                                            "longitude": 87.12313
    *                                        }
    *                                    },
    *                                   "start_time": "2021-12-23 13:52:00",
    *                                   "end_time": "2021-12-23 13:52:06",
    *                                   "origin": "Sanepa, Lalitpur",
    *                                   "destination": "New Baneshwor, Kathmandu",
    *                                   "stoppage": {
    *                                     {
    *                                       "name": "Sanepa, Lalitpur",
    *                                       "latitude": 27.1234,
    *                                       "longitude": 85.3434
    *                                     },
    *                                     {
    *                                       "name": "New Baneshwor, Kathmandu",
    *                                       "latitude": 28.3454,
    *                                       "longitude": 87.1234
    *                                     }
    *                                   },
    *                                   "distance": 12,
    *                                   "duration": 6,
    *                                   "passenger_number": 1,
    *                                   "profile_img_user": null,
    *                                   "profile_img_rider": null,
    *                                    "booking": {
    *                                     "id": 3,
    *                                     "status": "cancelled",
    *                                     "trip_id": "#86UQ7F1",
    *                                     "status_text": "Cancelled",
    *                                     "review": null
    *                                   },
    *                                   "status": "completed",
    *                                   "price": 159,
    *                                   "payment_type": "CASH",
    *                                   "cancelled_by_type": null,
    *                                   "cancelled_by_id": null,
    *                                   "cancel_message": null,
    *                                   "deleted_at": null,
    *                                   "created_at": "2021-12-23T01:45:57.000000Z",
    *                                   "updated_at": "2021-12-23T02:22:06.000000Z",
    *                                   "payment": {
    *                                     "id": 2,
    *                                     "completed_trip_id": 2,
    *                                     "commission_amount": 24,
    *                                     "payment_status": "unpaid",
    *                                     "commission_payment_status": "unpaid",
    *                                     "deleted_at": null,
    *                                     "created_at": "2021-12-23T08:07:06.000000Z",
    *                                     "updated_at": "2021-12-23T08:07:06.000000Z"
    *                                   },
    *                                   "rider": {
    *                                     "id": 1,
    *                                     "user_id": 2,
    *                                     "experience": 3,
    *                                     "trained": "YES",
    *                                     "status": "active",
    *                                     "approved_at": "2021-12-22 16:41:53",
    *                                     "device_token": null,
    *                                     "deleted_at": null,
    *                                     "last_deleted_by": null,
    *                                     "last_updated_by": null,
    *                                     "created_at": "2021-12-22T10:56:53.000000Z",
    *                                     "updated_at": "2021-12-22T10:56:53.000000Z",
    *                                     "vehicle": {
    *                                       "id": 1,
    *                                       "slug": "ba-11-pa-1111",
    *                                       "rider_id": 1,
    *                                       "vehicle_type_id": 1,
    *                                       "vehicle_number": "Ba 11 pa 1111",
    *                                       "image": null,
    *                                       "make_year": "2021",
    *                                       "vehicle_color": "black",
    *                                       "brand": null,
    *                                       "model": "Sint aliquip proident fugiat ad velit ex.",
    *                                       "status": "active",
    *                                       "deleted_at": null,
    *                                       "last_deleted_by": null,
    *                                       "last_updated_by": null,
    *                                       "created_at": "2021-12-22T10:56:54.000000Z",
    *                                       "updated_at": "2021-12-22T10:56:54.000000Z",
    *                                       "thumbnail_path": "assets/media/noimage.png",
    *                                       "image_path": "assets/media/noimage.png",
    *                                       "status_text": "Active",
    *                                       "documents": {},
    *                                       "vehicle_type": {
    *                                         "id": 1,
    *                                         "name": "bike",
    *                                         "image": null,
    *                                         "thumbnail_path": "assets/media/noimage.png",
    *                                         "image_path": "assets/media/noimage.png",
    *                                         "price_per_km": null,
    *                                         "price_per_min": null,
    *                                         "status_text": ""
    *                                       }
    *                                     },
    *                                     "user": {
    *                                       "id": 2,
    *                                       "first_name": "Sasuke",
    *                                       "last_name": "Uchiha",
    *                                       "image": null,
    *                                       "name": "Sasuke Uchiha",
    *                                       "status_text": "",
    *                                       "thumbnail_path": "assets/media/user_placeholder.png",
    *                                       "image_path": "assets/media/user_placeholder.png"
    *                                     }
    *                                   },
    *                                   "price_detail": {
    *                                     "id": 4,
    *                                     "booking_id": null,
    *                                     "completed_trip_id": 2,
    *                                     "minimum_charge": 50,
    *                                     "price_per_km": 15,
    *                                     "price_after_distance": 0.18,
    *                                     "surge_rate": 1,
    *                                     "surge": 0,
    *                                     "price_after_surge": 0.18,
    *                                     "app_charge_percent": 10,
    *                                     "app_charge": 0.02,
    *                                     "price_after_app_charge": 0.18,
    *                                     "price_per_min": 5,
    *                                     "duration_charge": 0.5,
    *                                     "price_after_duration": 0.68,
    *                                     "total_price": 50,
    *                                     "deleted_at": null,
    *                                     "created_at": "2021-12-23T08:07:06.000000Z",
    *                                     "updated_at": "2021-12-23T08:07:06.000000Z"
    *                                   }
    *                                 }
    *                               },
    *                               "first_page_url": "http://127.0.0.1:8000/api/user/booking/history?page=1",
    *                               "from": 1,
    *                               "last_page": 1,
    *                               "last_page_url": "http://127.0.0.1:8000/api/user/booking/history?page=1",
    *                               "links": {
    *                                 {
    *                                   "url": null,
    *                                   "label": "&laquo; Previous",
    *                                   "active": false
    *                                 },
    *                                 {
    *                                   "url": "http://127.0.0.1:8000/api/user/booking/history?page=1",
    *                                   "label": "1",
    *                                   "active": true
    *                                 },
    *                                 {
    *                                   "url": null,
    *                                   "label": "Next &raquo;",
    *                                   "active": false
    *                                 }
    *                               },
    *                               "next_page_url": null,
    *                               "path": "http://127.0.0.1:8000/api/user/booking/history",
    *                               "per_page": 5,
    *                               "prev_page_url": null,
    *                               "to": 1,
    *                               "total": 1
    *                             }
    *                           }
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
        $completed_trips = CompletedTrip::where('user_id',$user->id)
        ->with('location')
        ->with('payment')
        ->with('booking:id,status,trip_id')
        ->with('rider.user:id,first_name,last_name,image')
        ->with('price_detail')
        ->with('booking.review')
        ->orderByDesc('created_at')->paginate(5);

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
    *                   example=
    *                       {
    *                         "message": "Success!",
    *                         "completed_trips": {
    *                           "current_page": 1,
    *                           "data": {
    *                             {
    *                               "id": 1,
    *                               "user_id": 3,
    *                               "rider_id": 1,
    *                               "booking_id": 1,
    *                                "location": {
    *                                    "origin":{
    *                                        "name": "Sanepa, Lalitpur",
    *                                        "latitude": 27.687012,
    *                                        "longitude": 85.304359
    *                                    },
    *                                    "destination":{
    *                                        "name": "New Baneshwor, Kathmandu",
    *                                        "latitude": 28.234325,
    *                                        "longitude": 87.12313
    *                                    }
    *                                },
    *                               "start_time": "2021-12-23 13:45:28",
    *                               "end_time": "2021-12-23 13:45:35",
    *                               "origin": "Sanepa, Lalitpur",
    *                               "destination": "New Baneshwor, Kathmandu",
    *                                "booking": {
    *                                     "id": 3,
    *                                     "status": "cancelled",
    *                                     "trip_id": "#86UQ7F1",
    *                                     "status_text": "Cancelled",
    *                                     "review": {
    *                                             "id": 55,
    *                                             "booking_id": 176,
    *                                             "rider_id": 40,
    *                                             "user_id": 108,
    *                                             "reviewed_by_role": "customer",
    *                                             "rate": 4,
    *                                             "ride_date": "2021-12-23",
    *                                             "comment": "10",
    *                                             "deleted_at": null,
    *                                             "created_at": "2021-12-23T07:28:23.000000Z",
    *                                             "updated_at": "2021-12-23T07:28:23.000000Z"
    *                                           }
    *                                   },
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
    *                               "distance": 12,
    *                               "duration": 7,
    *                               "passenger_number": 1,
    *                               "profile_img_user": null,
    *                               "profile_img_rider": null,
    *                               "status": "completed",
    *                               "price": 159,
    *                               "payment_type": "CASH",
    *                               "cancelled_by_type": null,
    *                               "cancelled_by_id": null,
    *                               "cancel_message": null,
    *                               "deleted_at": null,
    *                               "created_at": "2021-12-22T05:14:05.000000Z",
    *                               "updated_at": "2021-12-23T02:15:35.000000Z",
    *                               "location": {
    *                                 "id": 1,
    *                                 "longitude_origin": 85.123423,
    *                                 "latitude_origin": 27.123456,
    *                                 "longitude_destination": 86.12313,
    *                                 "latitude_destination": 27.234325,
    *                                 "deleted_at": null,
    *                                 "created_at": "2021-12-22T10:59:05.000000Z",
    *                                 "updated_at": "2021-12-22T10:59:05.000000Z"
    *                               },
    *                               "user": {
    *                                 "id": 3,
    *                                 "first_name": "Kakashi",
    *                                 "last_name": "Hatake",
    *                                 "image": null,
    *                                 "name": "Kakashi Hatake",
    *                                 "status_text": "",
    *                                 "thumbnail_path": "assets/media/user_placeholder.png",
    *                                 "image_path": "assets/media/user_placeholder.png"
    *                               },
    *                               "price_detail": {
    *                                 "id": 3,
    *                                 "booking_id": null,
    *                                 "completed_trip_id": 1,
    *                                 "minimum_charge": 50,
    *                                 "price_per_km": 15,
    *                                 "price_after_distance": 0.18,
    *                                 "surge_rate": 1,
    *                                 "surge": 0,
    *                                 "price_after_surge": 0.18,
    *                                 "app_charge_percent": 10,
    *                                 "app_charge": 0.02,
    *                                 "price_after_app_charge": 0.18,
    *                                 "price_per_min": 5,
    *                                 "duration_charge": 0.58,
    *                                 "price_after_duration": 0.76,
    *                                 "total_price": 50,
    *                                 "deleted_at": null,
    *                                 "created_at": "2021-12-23T08:00:35.000000Z",
    *                                 "updated_at": "2021-12-23T08:00:35.000000Z"
    *                               },
    *                               "payment": {
    *                                 "id": 1,
    *                                 "completed_trip_id": 1,
    *                                 "commission_amount": 24,
    *                                 "payment_status": "unpaid",
    *                                 "commission_payment_status": "unpaid",
    *                                 "deleted_at": null,
    *                                 "created_at": "2021-12-23T08:00:35.000000Z",
    *                                 "updated_at": "2021-12-23T08:00:35.000000Z"
    *                               }
    *                             },
    *                             {
    *                               "id": 2,
    *                               "user_id": 2,
    *                               "rider_id": 1,
    *                               "booking_id": 15,
    *                               "location": {
    *                                   "origin":{
    *                                       "name": "Sanepa, Lalitpur",
    *                                       "latitude": 27.687012,
    *                                       "longitude": 85.304359
    *                                   },
    *                                   "destination":{
    *                                       "name": "New Baneshwor, Kathmandu",
    *                                       "latitude": 28.234325,
    *                                       "longitude": 87.12313
    *                                   }
    *                               },
    *                               "start_time": "2021-12-23 13:52:00",
    *                               "end_time": "2021-12-23 13:52:06",
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
    *                               "distance": 12,
    *                               "duration": 6,
    *                               "passenger_number": 1,
    *                               "profile_img_user": null,
    *                               "profile_img_rider": null,
    *                               "status": "completed",
    *                               "price": 159,
    *                               "payment_type": "CASH",
    *                               "cancelled_by_type": null,
    *                               "cancelled_by_id": null,
    *                               "cancel_message": null,
    *                               "deleted_at": null,
    *                               "created_at": "2021-12-23T01:45:57.000000Z",
    *                               "updated_at": "2021-12-23T02:22:06.000000Z",
    *                               "user": {
    *                                 "id": 2,
    *                                 "first_name": "Sasuke",
    *                                 "last_name": "Uchiha",
    *                                 "image": null,
    *                                 "name": "Sasuke Uchiha",
    *                                 "status_text": "",
    *                                 "thumbnail_path": "assets/media/user_placeholder.png",
    *                                 "image_path": "assets/media/user_placeholder.png"
    *                               },
    *                               "price_detail": {
    *                                 "id": 4,
    *                                 "booking_id": null,
    *                                 "completed_trip_id": 2,
    *                                 "minimum_charge": 50,
    *                                 "price_per_km": 15,
    *                                 "price_after_distance": 0.18,
    *                                 "surge_rate": 1,
    *                                 "surge": 0,
    *                                 "price_after_surge": 0.18,
    *                                 "app_charge_percent": 10,
    *                                 "app_charge": 0.02,
    *                                 "price_after_app_charge": 0.18,
    *                                 "price_per_min": 5,
    *                                 "duration_charge": 0.5,
    *                                 "price_after_duration": 0.68,
    *                                 "total_price": 50,
    *                                 "deleted_at": null,
    *                                 "created_at": "2021-12-23T08:07:06.000000Z",
    *                                 "updated_at": "2021-12-23T08:07:06.000000Z"
    *                               },
    *                               "payment": {
    *                                 "id": 2,
    *                                 "completed_trip_id": 2,
    *                                 "commission_amount": 24,
    *                                 "payment_status": "unpaid",
    *                                 "commission_payment_status": "unpaid",
    *                                 "deleted_at": null,
    *                                 "created_at": "2021-12-23T08:07:06.000000Z",
    *                                 "updated_at": "2021-12-23T08:07:06.000000Z"
    *                               }
    *                             }
    *                           },
    *                           "first_page_url": "http://127.0.0.1:8000/api/rider/booking/history?page=1",
    *                           "from": 1,
    *                           "last_page": 1,
    *                           "last_page_url": "http://127.0.0.1:8000/api/rider/booking/history?page=1",
    *                           "links": {
    *                             {
    *                               "url": null,
    *                               "label": "&laquo; Previous",
    *                               "active": false
    *                             },
    *                             {
    *                               "url": "http://127.0.0.1:8000/api/rider/booking/history?page=1",
    *                               "label": "1",
    *                               "active": true
    *                             },
    *                             {
    *                               "url": null,
    *                               "label": "Next &raquo;",
    *                               "active": false
    *                             }
    *                           },
    *                           "next_page_url": null,
    *                           "path": "http://127.0.0.1:8000/api/rider/booking/history",
    *                           "per_page": 5,
    *                           "prev_page_url": null,
    *                           "to": 2,
    *                           "total": 2
    *                         }
    *                       }
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
        $completed_trips = CompletedTrip::where('rider_id',$user->rider->id)->with('location')
        ->with('user:id,first_name,last_name,image')
        ->with('price_detail')
        ->with('payment')
        ->with('booking:id,status,trip_id')
        ->with('booking.review')
        ->orderByDesc('created_at')
        ->paginate(5);

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
    *                   example=
    *                       {
    *                         "message": "Success!",
    *                         "completed_trips": {
    *                           "current_page": 1,
    *                           "data": {
    *                             {
    *                               "id": 2,
    *                               "user_id": 2,
    *                               "rider_id": 1,
    *                               "booking_id": 15,
    *                               "location": {
    *                                   "origin":{
    *                                       "name": "Sanepa, Lalitpur",
    *                                       "latitude": 27.687012,
    *                                       "longitude": 85.304359
    *                                   },
    *                                   "destination":{
    *                                       "name": "New Baneshwor, Kathmandu",
    *                                       "latitude": 28.234325,
    *                                       "longitude": 87.12313
    *                                   }
    *                               },
    *                               "start_time": "2021-12-23 13:52:00",
    *                               "end_time": "2021-12-23 13:52:06",
    *                               "origin": "Sanepa, Lalitpur",
    *                                "booking": {
    *                                     "id": 3,
    *                                     "status": "cancelled",
    *                                     "trip_id": "#86UQ7F1",
    *                                     "status_text": "Cancelled",
    *                                     "review": {
    *                                             "id": 55,
    *                                             "booking_id": 176,
    *                                             "rider_id": 40,
    *                                             "user_id": 108,
    *                                             "reviewed_by_role": "customer",
    *                                             "rate": 4,
    *                                             "ride_date": "2021-12-23",
    *                                             "comment": "10",
    *                                             "deleted_at": null,
    *                                             "created_at": "2021-12-23T07:28:23.000000Z",
    *                                             "updated_at": "2021-12-23T07:28:23.000000Z"
    *                                           }
    *                                   },
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
    *                               "distance": 12,
    *                               "duration": 6,
    *                               "passenger_number": 1,
    *                               "profile_img_user": null,
    *                               "profile_img_rider": null,
    *                               "status": "completed",
    *                               "price": 159,
    *                               "payment_type": "CASH",
    *                               "cancelled_by_type": null,
    *                               "cancelled_by_id": null,
    *                               "cancel_message": null,
    *                               "deleted_at": null,
    *                               "created_at": "2021-12-23T01:45:57.000000Z",
    *                               "updated_at": "2021-12-23T02:22:06.000000Z",
    *                               "payment": {
    *                                 "id": 2,
    *                                 "completed_trip_id": 2,
    *                                 "commission_amount": 24,
    *                                 "payment_status": "unpaid",
    *                                 "commission_payment_status": "unpaid",
    *                                 "deleted_at": null,
    *                                 "created_at": "2021-12-23T08:07:06.000000Z",
    *                                 "updated_at": "2021-12-23T08:07:06.000000Z"
    *                               },
    *                               "rider": {
    *                                 "id": 1,
    *                                 "user_id": 2,
    *                                 "experience": 3,
    *                                 "trained": "YES",
    *                                 "status": "active",
    *                                 "approved_at": "2021-12-22 16:41:53",
    *                                 "device_token": null,
    *                                 "deleted_at": null,
    *                                 "last_deleted_by": null,
    *                                 "last_updated_by": null,
    *                                 "created_at": "2021-12-22T10:56:53.000000Z",
    *                                 "updated_at": "2021-12-22T10:56:53.000000Z",
    *                                 "vehicle": {
    *                                   "id": 1,
    *                                   "slug": "ba-11-pa-1111",
    *                                   "rider_id": 1,
    *                                   "vehicle_type_id": 1,
    *                                   "vehicle_number": "Ba 11 pa 1111",
    *                                   "image": null,
    *                                   "make_year": "2021",
    *                                   "vehicle_color": "black",
    *                                   "brand": null,
    *                                   "model": "Sint aliquip proident fugiat ad velit ex.",
    *                                   "status": "active",
    *                                   "deleted_at": null,
    *                                   "last_deleted_by": null,
    *                                   "last_updated_by": null,
    *                                   "created_at": "2021-12-22T10:56:54.000000Z",
    *                                   "updated_at": "2021-12-22T10:56:54.000000Z",
    *                                   "thumbnail_path": "assets/media/noimage.png",
    *                                   "image_path": "assets/media/noimage.png",
    *                                   "status_text": "Active",
    *                                   "documents": {},
    *                                   "vehicle_type": {
    *                                     "id": 1,
    *                                     "name": "bike",
    *                                     "image": null,
    *                                     "thumbnail_path": "assets/media/noimage.png",
    *                                     "image_path": "assets/media/noimage.png",
    *                                     "price_per_km": null,
    *                                     "price_per_min": null,
    *                                     "status_text": ""
    *                                   }
    *                                 }
    *                               }
    *                             }
    *                           },
    *                           "first_page_url": "http://127.0.0.1:8000/api/user/vehicle_type/1/booking/completed/history?page=1",
    *                           "from": 1,
    *                           "last_page": 1,
    *                           "last_page_url": "http://127.0.0.1:8000/api/user/vehicle_type/1/booking/completed/history?page=1",
    *                           "links": {
    *                             {
    *                               "url": null,
    *                               "label": "&laquo; Previous",
    *                               "active": false
    *                             },
    *                             {
    *                               "url": "http://127.0.0.1:8000/api/user/vehicle_type/1/booking/completed/history?page=1",
    *                               "label": "1",
    *                               "active": true
    *                             },
    *                             {
    *                               "url": null,
    *                               "label": "Next &raquo;",
    *                               "active": false
    *                             }
    *                           },
    *                           "next_page_url": null,
    *                           "path": "http://127.0.0.1:8000/api/user/vehicle_type/1/booking/completed/history",
    *                           "per_page": 5,
    *                           "prev_page_url": null,
    *                           "to": 1,
    *                           "total": 1
    *                         }
    *                       }
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
                                        ->with('payment')
                                        ->with('booking.review')
                                        ->orderByDesc('created_at')
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
    *                   example=
    *                           {
    *                             "message": "Success!",
    *                             "completed_trips": {
    *                               "current_page": 1,
    *                               "data": {
    *                                 {
    *                                   "id": 1,
    *                                   "user_id": 3,
    *                                   "rider_id": 1,
    *                                   "booking_id": 1,
    *                                    "location": {
    *                                        "origin":{
    *                                            "name": "Sanepa, Lalitpur",
    *                                            "latitude": 27.687012,
    *                                            "longitude": 85.304359
    *                                        },
    *                                        "destination":{
    *                                            "name": "New Baneshwor, Kathmandu",
    *                                            "latitude": 28.234325,
    *                                            "longitude": 87.12313
    *                                        }
    *                                    },
    *                                   "start_time": "2021-12-23 13:45:28",
    *                                   "end_time": "2021-12-23 13:45:35",
    *                                   "origin": "Sanepa, Lalitpur",
    *                                    "booking": {
    *                                     "id": 3,
    *                                     "status": "cancelled",
    *                                     "trip_id": "#86UQ7F1",
    *                                     "status_text": "Cancelled",
    *                                     "review": {
    *                                             "id": 55,
    *                                             "booking_id": 176,
    *                                             "rider_id": 40,
    *                                             "user_id": 108,
    *                                             "reviewed_by_role": "customer",
    *                                             "rate": 4,
    *                                             "ride_date": "2021-12-23",
    *                                             "comment": "10",
    *                                             "deleted_at": null,
    *                                             "created_at": "2021-12-23T07:28:23.000000Z",
    *                                             "updated_at": "2021-12-23T07:28:23.000000Z"
    *                                           }
    *                                   },
    *                                   "destination": "New Baneshwor, Kathmandu",
    *                                   "stoppage": {
    *                                     {
    *                                       "name": "Sanepa, Lalitpur",
    *                                       "latitude": 27.1234,
    *                                       "longitude": 85.3434
    *                                     },
    *                                     {
    *                                       "name": "New Baneshwor, Kathmandu",
    *                                       "latitude": 28.3454,
    *                                       "longitude": 87.1234
    *                                     }
    *                                   },
    *                                   "distance": 12,
    *                                   "duration": 7,
    *                                   "passenger_number": 1,
    *                                   "profile_img_user": null,
    *                                   "profile_img_rider": null,
    *                                   "status": "completed",
    *                                   "price": 159,
    *                                   "payment_type": "CASH",
    *                                   "cancelled_by_type": null,
    *                                   "cancelled_by_id": null,
    *                                   "cancel_message": null,
    *                                   "deleted_at": null,
    *                                   "created_at": "2021-12-22T05:14:05.000000Z",
    *                                   "updated_at": "2021-12-23T02:15:35.000000Z",
    *                                   "payment": {
    *                                     "id": 1,
    *                                     "completed_trip_id": 1,
    *                                     "commission_amount": 24,
    *                                     "payment_status": "unpaid",
    *                                     "commission_payment_status": "unpaid",
    *                                     "deleted_at": null,
    *                                     "created_at": "2021-12-23T08:00:35.000000Z",
    *                                     "updated_at": "2021-12-23T08:00:35.000000Z"
    *                                   },
    *                                   "rider": {
    *                                     "id": 1,
    *                                     "user_id": 2,
    *                                     "experience": 3,
    *                                     "trained": "YES",
    *                                     "status": "active",
    *                                     "approved_at": "2021-12-22 16:41:53",
    *                                     "device_token": null,
    *                                     "deleted_at": null,
    *                                     "last_deleted_by": null,
    *                                     "last_updated_by": null,
    *                                     "created_at": "2021-12-22T10:56:53.000000Z",
    *                                     "updated_at": "2021-12-22T10:56:53.000000Z",
    *                                     "vehicle": {
    *                                       "id": 1,
    *                                       "slug": "ba-11-pa-1111",
    *                                       "rider_id": 1,
    *                                       "vehicle_type_id": 1,
    *                                       "vehicle_number": "Ba 11 pa 1111",
    *                                       "image": null,
    *                                       "make_year": "2021",
    *                                       "vehicle_color": "black",
    *                                       "brand": null,
    *                                       "model": "Sint aliquip proident fugiat ad velit ex.",
    *                                       "status": "active",
    *                                       "deleted_at": null,
    *                                       "last_deleted_by": null,
    *                                       "last_updated_by": null,
    *                                       "created_at": "2021-12-22T10:56:54.000000Z",
    *                                       "updated_at": "2021-12-22T10:56:54.000000Z",
    *                                       "thumbnail_path": "assets/media/noimage.png",
    *                                       "image_path": "assets/media/noimage.png",
    *                                       "status_text": "Active",
    *                                       "documents": {},
    *                                       "vehicle_type": {
    *                                         "id": 1,
    *                                         "name": "bike",
    *                                         "image": null,
    *                                         "thumbnail_path": "assets/media/noimage.png",
    *                                         "image_path": "assets/media/noimage.png",
    *                                         "price_per_km": null,
    *                                         "price_per_min": null,
    *                                         "status_text": ""
    *                                       }
    *                                     }
    *                                   }
    *                                 },
    *                                 {
    *                                   "id": 2,
    *                                   "user_id": 2,
    *                                   "rider_id": 1,
    *                                   "booking_id": 15,
    *                                   "location_id": 15,
    *                                   "start_time": "2021-12-23 13:52:00",
    *                                   "end_time": "2021-12-23 13:52:06",
    *                                   "origin": "Sanepa, Lalitpur",
    *                                   "destination": "New Baneshwor, Kathmandu",
    *                                   "stoppage": {
    *                                     {
    *                                       "name": "Sanepa, Lalitpur",
    *                                       "latitude": 27.1234,
    *                                       "longitude": 85.3434
    *                                     },
    *                                     {
    *                                       "name": "New Baneshwor, Kathmandu",
    *                                       "latitude": 28.3454,
    *                                       "longitude": 87.1234
    *                                     }
    *                                   },
    *                                   "distance": 12,
    *                                   "duration": 6,
    *                                   "passenger_number": 1,
    *                                   "profile_img_user": null,
    *                                   "profile_img_rider": null,
    *                                   "status": "completed",
    *                                   "price": 159,
    *                                   "payment_type": "CASH",
    *                                   "cancelled_by_type": null,
    *                                   "cancelled_by_id": null,
    *                                   "cancel_message": null,
    *                                   "deleted_at": null,
    *                                   "created_at": "2021-12-23T01:45:57.000000Z",
    *                                   "updated_at": "2021-12-23T02:22:06.000000Z",
    *                                   "payment": {
    *                                     "id": 2,
    *                                     "completed_trip_id": 2,
    *                                     "commission_amount": 24,
    *                                     "payment_status": "unpaid",
    *                                     "commission_payment_status": "unpaid",
    *                                     "deleted_at": null,
    *                                     "created_at": "2021-12-23T08:07:06.000000Z",
    *                                     "updated_at": "2021-12-23T08:07:06.000000Z"
    *                                   },
    *                                   "location": {
    *                                     "id": 15,
    *                                     "longitude_origin": 85.123423,
    *                                     "latitude_origin": 27.123456,
    *                                     "longitude_destination": 86.12313,
    *                                     "latitude_destination": 27.234325,
    *                                     "deleted_at": null,
    *                                     "created_at": "2021-12-23T07:30:57.000000Z",
    *                                     "updated_at": "2021-12-23T07:30:57.000000Z"
    *                                   },
    *                                   "rider": {
    *                                     "id": 1,
    *                                     "user_id": 2,
    *                                     "experience": 3,
    *                                     "trained": "YES",
    *                                     "status": "active",
    *                                     "approved_at": "2021-12-22 16:41:53",
    *                                     "device_token": null,
    *                                     "deleted_at": null,
    *                                     "last_deleted_by": null,
    *                                     "last_updated_by": null,
    *                                     "created_at": "2021-12-22T10:56:53.000000Z",
    *                                     "updated_at": "2021-12-22T10:56:53.000000Z",
    *                                     "vehicle": {
    *                                       "id": 1,
    *                                       "slug": "ba-11-pa-1111",
    *                                       "rider_id": 1,
    *                                       "vehicle_type_id": 1,
    *                                       "vehicle_number": "Ba 11 pa 1111",
    *                                       "image": null,
    *                                       "make_year": "2021",
    *                                       "vehicle_color": "black",
    *                                       "brand": null,
    *                                       "model": "Sint aliquip proident fugiat ad velit ex.",
    *                                       "status": "active",
    *                                       "deleted_at": null,
    *                                       "last_deleted_by": null,
    *                                       "last_updated_by": null,
    *                                       "created_at": "2021-12-22T10:56:54.000000Z",
    *                                       "updated_at": "2021-12-22T10:56:54.000000Z",
    *                                       "thumbnail_path": "assets/media/noimage.png",
    *                                       "image_path": "assets/media/noimage.png",
    *                                       "status_text": "Active",
    *                                       "documents": {},
    *                                       "vehicle_type": {
    *                                         "id": 1,
    *                                         "name": "bike",
    *                                         "image": null,
    *                                         "thumbnail_path": "assets/media/noimage.png",
    *                                         "image_path": "assets/media/noimage.png",
    *                                         "price_per_km": null,
    *                                         "price_per_min": null,
    *                                         "status_text": ""
    *                                       }
    *                                     }
    *                                   }
    *                                 }
    *                               },
    *                               "first_page_url": "http://127.0.0.1:8000/api/rider/vehicle_type/1/booking/completed/history?page=1",
    *                               "from": 1,
    *                               "last_page": 1,
    *                               "last_page_url": "http://127.0.0.1:8000/api/rider/vehicle_type/1/booking/completed/history?page=1",
    *                               "links": {
    *                                 {
    *                                   "url": null,
    *                                   "label": "&laquo; Previous",
    *                                   "active": false
    *                                 },
    *                                 {
    *                                   "url": "http://127.0.0.1:8000/api/rider/vehicle_type/1/booking/completed/history?page=1",
    *                                   "label": "1",
    *                                   "active": true
    *                                 },
    *                                 {
    *                                   "url": null,
    *                                   "label": "Next &raquo;",
    *                                   "active": false
    *                                 }
    *                               },
    *                               "next_page_url": null,
    *                               "path": "http://127.0.0.1:8000/api/rider/vehicle_type/1/booking/completed/history",
    *                               "per_page": 5,
    *                               "prev_page_url": null,
    *                               "to": 2,
    *                               "total": 2
    *                             }
    *                           }        
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
                                        ->with('booking:id,status,trip_id')
                                        ->with('booking.review')
                                        ->with('payment')
                                        ->orderByDesc('created_at')
                                        ->with('location')->with('rider')->paginate(5);

        $response = ['message' => 'Success!',  "completed_trips"=>$completed_trips];
        return response($response, 200);
    }




    /**
    * @OA\Get(
    *   path="/api/{user_type}/total_distance",
    *   tags={"Booking"},
    *   summary="Total distance of completed bookings of User/Rider ",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="user_type",
    *         in="path",
    *         description="User Type [Accepted Values: 'customer', or 'rider']",
    *         required=true,
    *      ),
    *
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                     "message": "Success!",
    *                     "total_distance": 0
    *                   }
    *                 )
    *      )
    *   ),
    *   @OA\Response(
    *      response=422,
    *       description="Invalid User Type! Select one from 'customer' or 'rider'!"
    *   ),
    *   @OA\Response(
    *      response=500,
    *       description="Somehing went wrong! Internal Server Error!"
    *   )
    *)
    **/
    public function getTotalDistance($user_type)
    {
        //Validate User Type
        if( !in_array($user_type, ['customer','rider']  ) )
        {
            $response = ['message' => 'Invalid User Type! Select one from "customer" or "rider"!'];
            return response($response, 422);
        }

        $user = Auth::user();
        $total_distance = 0;

        if($user_type == 'customer')
            $total_distance = CompletedTrip::where('user_id',$user->id)->where('status','completed')->sum('distance');
        else
            $total_distance = CompletedTrip::where('rider_id',$user->rider->id)->where('status','completed')->sum('distance');

        $response = ['message' => 'Success!', 'total_distance'=>$total_distance ];
        return response($response, 200);

        $response = ['Somehing went wrong! Internal Server Error!' ];
        return response($response, 500);

    }

    /**
    * @OA\Get(
    *   path="/api/{user_type}/total_trips",
    *   tags={"Booking"},
    *   summary="Total completed bookings of User/Rider ",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="user_type",
    *         in="path",
    *         description="User Type [Accepted Values: 'customer', or 'rider']",
    *         required=true,
    *      ),
    *
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                     "message": "Success!",
    *                     "total_trips": 0
    *                   }
    *                 )
    *      )
    *   ),
    *   @OA\Response(
    *      response=422,
    *       description="Invalid User Type! Select one from 'customer' or 'rider'!"
    *   ),
    *   @OA\Response(
    *      response=500,
    *       description="Somehing went wrong! Internal Server Error!"
    *   )
    *)
    **/
    public function getTotalTrips($user_type)
    {
          //Validate User Type
          if( !in_array($user_type, ['customer','rider']  ) )
          {
              $response = ['message' => 'Invalid User Type! Select one from "customer" or "rider"!'];
              return response($response, 422);
          }

          $user = Auth::user();
          $total_trips = 0;
          if($user_type == 'customer')
            $total_trips = CompletedTrip::where('user_id',$user->id)->where('status','completed')->count();
          else 
            $total_trips = CompletedTrip::where('rider_id',$user->rider->id)->where('status','completed')->count();
          $response = ['message' => 'Success!', 'total_trips'=>$total_trips ];
          return response($response, 200);
  
          $response = ['Somehing went wrong! Internal Server Error!' ];
          return response($response, 500);

    }









    
 /**
    * @OA\Get(
    *   path="/api/completed_trip/{completed_trip_id}",
    *   tags={"Booking"},
    *   summary="Get Completed Trip from ID",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="completed_trip_id",
    *         in="path",
    *         description="Completed Trip ID",
    *         required=true,
    *      ),
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example=
    *                   {
    *                     "message": "Success!",
    *                     "completed_trip": {
    *                       "id": 5,
    *                       "user_id": 2,
    *                       "rider_id": 1,
    *                       "booking_id": 5,
    *                       "location": {
    *                           "origin":{
    *                               "name": "Sanepa, Lalitpur",
    *                               "latitude": 27.687012,
    *                               "longitude": 85.304359
    *                           },
    *                           "destination":{
    *                               "name": "New Baneshwor, Kathmandu",
    *                               "latitude": 28.234325,
    *                               "longitude": 87.12313
    *                           }
    *                       },
    *                       "start_time": "2021-12-27 13:48:48",
    *                       "end_time": "2021-12-27 13:49:06",
    *                       "origin": "Sanepa, Lalitpur",
    *                       "destination": "New Baneshwor, Kathmandu",
    *                       "stoppage": {
    *                         {
    *                           "name": "Sanepa, Lalitpur",
    *                           "latitude": 27.1234,
    *                           "longitude": 85.3434
    *                         },
    *                         {
    *                           "name": "New Baneshwor, Kathmandu",
    *                           "latitude": 28.3454,
    *                           "longitude": 87.1234
    *                         }
    *                       },
    *                       "distance": 12,
    *                       "duration": 18,
    *                       "passenger_number": 1,
    *                       "profile_img_user": null,
    *                       "profile_img_rider": null,
    *                       "status": "completed",
    *                       "price": 50,
    *                       "payment_type": "CASH",
    *                       "cancelled_by_type": null,
    *                       "cancelled_by_id": null,
    *                       "cancel_message": null,
    *                       "deleted_at": null,
    *                       "created_at": "2021-12-27T02:10:00.000000Z",
    *                       "updated_at": "2021-12-27T02:19:06.000000Z",
    *                       "booking": {
    *                         "id": 5,
    *                         "trip_id": "#GK6655W",
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
    *                         "user_id": 2,
    *                         "vehicle_type_id": 1,
    *                         "rider_id": 1,
    *                          "location": {
    *                              "origin":{
    *                                  "name": "Sanepa, Lalitpur",
    *                                  "latitude": 27.687012,
    *                                  "longitude": 85.304359
    *                              },
    *                              "destination":{
    *                                  "name": "New Baneshwor, Kathmandu",
    *                                  "latitude": 28.234325,
    *                                  "longitude": 87.12313
    *                              }
    *                          },
    *                         "start_time": "2021-12-27 13:48:48",
    *                         "end_time": "2021-12-27 13:49:06",
    *                         "origin": "Sanepa, Lalitpur",
    *                         "destination": "New Baneshwor, Kathmandu",
    *                         "distance": 12,
    *                         "review": {
    *                                 "id": 55,
    *                                 "booking_id": 176,
    *                                 "rider_id": 40,
    *                                 "user_id": 108,
    *                                 "reviewed_by_role": "customer",
    *                                 "rate": 4,
    *                                 "ride_date": "2021-12-23",
    *                                 "comment": "10",
    *                                 "deleted_at": null,
    *                                 "created_at": "2021-12-23T07:28:23.000000Z",
    *                                 "updated_at": "2021-12-23T07:28:23.000000Z"
    *                               },
    *                         "duration": 20,
    *                         "passenger_number": 2,
    *                         "status": "completed",
    *                         "price": 160,
    *                         "payment_type": "CASH",
    *                         "deleted_at": null,
    *                         "created_at": "2021-12-27T07:55:00.000000Z",
    *                         "updated_at": "2021-12-27T08:04:06.000000Z",
    *                         "status_text": "Completed",
    *                         "review": null
    *                       },
    *                       "payment": {
    *                         "id": 5,
    *                         "completed_trip_id": 5,
    *                         "commission_amount": 8,
    *                         "payment_status": "unpaid",
    *                         "commission_payment_status": "unpaid",
    *                         "deleted_at": null,
    *                         "created_at": "2021-12-27T08:04:06.000000Z",
    *                         "updated_at": "2021-12-27T08:04:06.000000Z",
    *                         "customer_payment_status": "unpaid"
    *                       },
    *                       "price_detail": {
    *                         "id": 10,
    *                         "booking_id": null,
    *                         "completed_trip_id": 5,
    *                         "base_fare": 30,
    *                         "base_covered_km": 2,
    *                         "minimum_charge": 50,
    *                         "price_per_km": 17,
    *                         "charged_km": null,
    *                         "price_after_distance": 0,
    *                         "shift_surge": 0,
    *                         "density_surge": 0,
    *                         "surge_rate": 1,
    *                         "price_per_km_after_surge": 17,
    *                         "surge": 0,
    *                         "price_after_surge": 0,
    *                         "app_charge_percent": 10,
    *                         "app_charge": 0,
    *                         "price_after_app_charge": 0,
    *                         "price_per_min": 0,
    *                         "price_per_min_after_base": 1,
    *                         "duration_charge": 0,
    *                         "price_after_duration": 0,
    *                         "price_after_base_fare": 30,
    *                         "total_price": 50,
    *                         "deleted_at": null,
    *                         "created_at": "2021-12-27T08:04:06.000000Z",
    *                         "updated_at": "2021-12-27T08:04:06.000000Z"
    *                       }
    *                     }
    *                   }
    *                 )
    *           )
    *      ),
    *   @OA\Response(
    *      response=404,
    *       description="Completed Trip Not Found!",
    *   )
    *)
    **/
    public function getCompletedTrip($completed_trip_id)
    {
        $completed_trip = 
        CompletedTrip::where('id',$completed_trip_id)
        ->with('location')
        ->with('booking')
        ->with('booking.review')
        ->with('payment')
        ->with('price_detail')
        ->first();
        if($completed_trip) {
            $response = ['message' => 'Success!', 'completed_trip'=>$completed_trip];
            return response($response, 200);
        }
        else{
            $response = ['message' => 'Completed Trip Not Found!'];
            return response($response, 404);
        }
    }


}
