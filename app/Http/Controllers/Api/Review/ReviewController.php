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
use App\Modules\Models\User;
use App\Modules\Models\Rider;

//requests
use App\Http\Requests\Api\Review\ReviewRequest;

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
    *   tags={"Reviews"},
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
    *       description="Something went wrong! Internal Server Error!",
    *   )
    *)
    **/
    public function store(ReviewRequest $request)
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
            return response("Something went wrong! Internal Server Error!", 500);
        });


    }


    /**
    * @OA\Get(
    *   path="/api/user/{user_id}/reviews",
    *   tags={"Reviews"},
    *   summary="Get reviews made by and made on this user",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="user_id",
    *         in="path",
    *         description="User ID  [Accepted values: Valid User's ID.]",
    *         required=true,
    *      ),
    *
    *   @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                     "message": "Success!",
    *                     "data": {
    *                       "average_rating": 3,
    *                       "reviews": {
    *                         {
    *                           "id": 1,
    *                           "booking_id": 1,
    *                           "rider_id": null,
    *                           "user_id": 3,
    *                           "reviewed_by_role": "rider",
    *                           "rate": 3,
    *                           "ride_date": "2021-12-01",
    *                           "comment": "Ride was smooth!",
    *                           "deleted_at": null,
    *                           "created_at": "2021-12-01T08:28:47.000000Z",
    *                           "updated_at": "2021-12-01T08:28:47.000000Z"
    *                         }
    *                       }
    *                     }
    *                   }
    *                 )
    *           )
    *      ),
    *   @OA\Response(
    *      response=422,
    *       description="User not found!",
    *   ),
        *   @OA\Response(
    *      response=500,
    *       description="Something went wrong! Internal Server Error!",
    *   )
    *)
    **/
    public function getUserReviews($user_id)
    {
        //Validate User Exists
        $user = User::find($user_id);
        if(!$user)
        {
            $response = ['message' => 'User not found!',];
            return response($response, 422);
        }

        //Get average rating and all reviews for the user
        $result = [];
        $result['average_rating'] = 0;
        $result['reviews'] = [];

        $result['average_rating'] = floatval(Review::where('user_id',$user->id)->where('reviewed_by_role','!=','customer')->avg('rate') )  ;
        $result['reviews'] = Review::where('user_id',$user->id)->where('reviewed_by_role','!=','customer')->get();

        $response = ['message' => 'Success!', 'data'=>$result];
        return response($response, 200);


        return response("Something went wrong! Internal Server Error!", 500);
    }


    

 /**
    * @OA\Get(
    *   path="/api/rider/{rider_id}/reviews",
    *   tags={"Reviews"},
    *   summary="Get reviews made by and made on this rider",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="rider_id",
    *         in="path",
    *         description="Rider ID  [Accepted values: Valid Rider's ID.]",
    *         required=true,
    *      ),
    *
    *   @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                     "message": "Success!",
    *                     "data": {
    *                       "average_rating": 3,
    *                       "reviews": {
    *                         {
    *                           "id": 1,
    *                           "booking_id": 1,
    *                           "rider_id": null,
    *                           "user_id": 3,
    *                           "reviewed_by_role": "customer",
    *                           "rate": 3,
    *                           "ride_date": "2021-12-01",
    *                           "comment": "Ride was smooth!",
    *                           "deleted_at": null,
    *                           "created_at": "2021-12-01T08:28:47.000000Z",
    *                           "updated_at": "2021-12-01T08:28:47.000000Z"
    *                         }
    *                       }
    *                     }
    *                   }
    *                 )
    *           )
    *      ),
    *   @OA\Response(
    *      response=422,
    *       description="User not found!",
    *   ),
        *   @OA\Response(
    *      response=500,
    *       description="Something went wrong! Internal Server Error!",
    *   )
    *)
    **/
    public function getRiderReviews($rider_id)
    {
        //Validate User Exists
        $rider = Rider::find($rider_id);
        if(!$rider)
        {
            $response = ['message' => 'Rider not found!',];
            return response($response, 422);
        }

        //Get average rating and all reviews for the rider
        $result = [];
        $result['average_rating'] = 0;
        $result['reviews'] = [];

        $result['average_rating'] =   floatval(Review::where('rider_id',$rider->id)->where('reviewed_by_role','!=','rider')->avg('rate'))  ;
        $result['reviews'] = Review::where('rider_id',$rider->id)->where('reviewed_by_role','!=','rider')->get();

        $response = ['message' => 'Success!', 'data'=>$result];
        return response($response, 200);


        return response("Something went wrong! Internal Server Error!", 500);
    }


}
