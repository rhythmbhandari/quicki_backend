<?php

namespace App\Http\Controllers\Api\Review;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//services
use App\Modules\Services\Review\ReviewService;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\Review;
use App\Modules\Models\Booking;

class ReviewController extends Controller
{
    protected $review, $user_service;

    public function __construct(ReviewService $review, UserService $user_service)
    {
        $this->review = $review;
        $this->user_service = $user_service;
    }




        /**
    * @OA\Post(
    *   path="/api/review/create",
    *   tags={"Booking"},
    *   summary="Create Review",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\RequestBody(
    *      @OA\MediaType(
    *         mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                 "booking_id":1,
    *                  "rate":3,
    *                  "comment":"Ride was smooth!",
    *                  "reviewed_by_role":"customer",
    *               }
    *         )
    *     )
    *   ),
    *
    *   @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                           "message": "Ride Review Successful!",
    *                           "review": {
    *                             "booking_id": 1,
    *                             "rate": 3,
    *                             "comment": "Ride was smooth!",
    *                             "reviewed_by_role": "customer",
    *                             "ride_date": "2021-11-17T07:42:19.000000Z",
    *                             "user_id": 3,
    *                             "rider_id": 1,
    *                             "updated_at": "2021-11-17T08:21:19.000000Z",
    *                             "created_at": "2021-11-17T08:21:19.000000Z",
    *                             "id": 1
    *                           }
    *                     }
    *                 )
    *           )
    *      ),
    *   @OA\Response(
    *      response=403,
    *       description="Forbidden Access!",
    *   ),
        *   @OA\Response(
    *      response=500,
    *       description="Internal Server Error!",
    *   )
    *)
    **/
    public function store(Request $request)
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

        //VALIDATIONS
        $validator = Validator::make($request->all(), [
    
            'rate' => 'required|numeric',
            'comment' => 'required|string',
            'reviewed_by_role' =>  ['required', function ($attribute, $value, $fail) {
                if ( ! ($value=="customer" || $value=="rider")  ) {
                    $fail('The review can only be made by either the customer or the rider!');
                }
            },],
            'booking_id' =>  ['required', function ($attribute, $value, $fail) {
                $booking = Booking::find($value);
                if ( ! $booking  ) {
                    $fail('No booking found for the given id!');
                }
                // Check if booking status is completed/cancelled (optional)
                else if( ! ($booking->status == "completed" || $booking->status == "cancelled" ) )
                {
                    $fail('Review cannot be created as the booking is still active!');
                }
                else{}
            },],
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        //ROLE CHECK FOR CUSTOMER
        if( $request->reviewed_by_role=="customer" && !$this->user_service->hasRole($user, 'customer') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
        //ROLE CHECK FOR RIDER
        if( $request->reviewed_by_role=="rider" && !$this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        //REVIEW STORE
        return DB::transaction(function () use ($request, $user)
        {
            $createdReview = $this->review->create($request->all());
            if($createdReview)
            {
                $response = ['message' => 'Ride Review Successful!',  "review"=>$createdReview,];
                return response($response, 201);
            }
            return response("Internal Server Error!", 500);
        });


    }


}
