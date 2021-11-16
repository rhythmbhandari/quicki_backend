<?php

namespace App\Http\Controllers\Api\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//services
use App\Modules\Services\Booking\BookingService;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\Booking;

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
    *   security={
    *   {
    *       "passport": {}},
    *   },
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
    *                  "duration":20,
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
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                           "message":"Booking Successful!",
    *                   }
    *                 )
    *           )
    *      ),
    *       @OA\Response(
    *             response=401,
    *             description="Unauthorized: User does not Exist!"
    *         ),
    *         @OA\Response(
    *             response=403,
    *             description="Forbidden Access: Booking is blocked for now!"
    *         ),
    *      @OA\Response(
    *          response=422,
    *          description="Validation Fail",
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
    public function store(Request $request)
    {
        //VALIDATIONS
        $validator = Validator::make($request->all(), [
           
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'vehicle_type_id' =>  ['required', function ($attribute, $value, $fail) {
                $vehicle_type = VehicleType::find($value);

                if ( !$vehicle_type) {
                    $fail('The vehicle type does not exist!');
                }
            },],
            'distance' => 'required|numeric',
            'duration' => 'required|numeric',
            'passenger_number' => 'nullable|integer',
            
            //Location
            'location.latitude_origin'=>'required|numeric',
            'location.longitude_origin'=>'required|numeric',
            'location.latitude_destination'=>'required|numeric',
            'location.longitude_destination'=>'required|numeric',

           
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }


        dd("Booking DATA: ", $request->all());
        //ROLE CHECK FOR CUSTOMER


        //BOOKING STORE

    }

   
}
