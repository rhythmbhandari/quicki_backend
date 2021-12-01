<?php

namespace App\Http\Controllers\Api\Suggestion;

use Illuminate\Http\Request;

//suggestion
use App\Modules\Services\Suggestion\SuggestionService;
use App\Http\Controllers\Controller;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\Suggestion;



class SuggestionController extends Controller
{
    
    protected $suggestion, $user_service;

    public function __construct(SuggestionService $suggestion, UserService $user_service)
    {
        $this->suggestion = $suggestion;
        $this->user_service = $user_service;
    }



    /**
    * @OA\Get(
    *   path="/api/suggestion/{suggestion_type}",
    *   tags={"Suggestions"},
    *   summary="Get Suggestions for specified type/cases",
    *   security={{"bearerAuth":{}}},
    *   @OA\Parameter(
    *         name="suggestion_type",
    *         in="path",
    *         description="Suggestion Type => ['review_by_user','review_by_rider','booking_cancel_by_rider','booking_cancel_by_user', 'all']",
    *         required=true,
    *      ),
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                     "message": "Success!",
    *                     "suggestions": {
    *                       {
    *                         "id": 1,
    *                         "slug": null,
    *                         "text": "Great Ride!",
    *                         "image": null,
    *                         "type": "review_by_user",
    *                         "category": "positive_review",
    *                         "created_at": "2021-11-30T11:54:56.000000Z",
    *                         "updated_at": "2021-11-30T11:54:56.000000Z",
    *                         "thumbnail_path": "assets/media/noimage.png",
    *                         "image_path": "assets/media/noimage.png"
    *                       },
    *                       {
    *                         "id": 2,
    *                         "slug": null,
    *                         "text": "Smooth Ride!",
    *                         "image": null,
    *                         "type": "review_by_user",
    *                         "category": "positive_review",
    *                         "created_at": "2021-11-30T11:54:56.000000Z",
    *                         "updated_at": "2021-11-30T11:54:56.000000Z",
    *                         "thumbnail_path": "assets/media/noimage.png",
    *                         "image_path": "assets/media/noimage.png"
    *                       },
    *                       {
    *                         "id": 3,
    *                         "slug": null,
    *                         "text": "Trash Vehicle!",
    *                         "image": null,
    *                         "type": "review_by_user",
    *                         "category": "negative_review",
    *                         "created_at": "2021-11-30T11:54:56.000000Z",
    *                         "updated_at": "2021-11-30T11:54:56.000000Z",
    *                         "thumbnail_path": "assets/media/noimage.png",
    *                         "image_path": "assets/media/noimage.png"
    *                       },
    *                       {
    *                         "id": 4,
    *                         "slug": null,
    *                         "text": "Great Ride!",
    *                         "image": null,
    *                         "type": "review_by_user",
    *                         "category": "positive_review",
    *                         "created_at": "2021-11-30T11:55:19.000000Z",
    *                         "updated_at": "2021-11-30T11:55:19.000000Z",
    *                         "thumbnail_path": "assets/media/noimage.png",
    *                         "image_path": "assets/media/noimage.png"
    *                       },
    *                       {
    *                         "id": 5,
    *                         "slug": null,
    *                         "text": "Smooth Ride!",
    *                         "image": null,
    *                         "type": "review_by_user",
    *                         "category": "positive_review",
    *                         "created_at": "2021-11-30T11:55:19.000000Z",
    *                         "updated_at": "2021-11-30T11:55:19.000000Z",
    *                         "thumbnail_path": "assets/media/noimage.png",
    *                         "image_path": "assets/media/noimage.png"
    *                       },
    *                       {
    *                         "id": 6,
    *                         "slug": null,
    *                         "text": "Trash Vehicle!",
    *                         "image": null,
    *                         "type": "review_by_user",
    *                         "category": "negative_review",
    *                         "created_at": "2021-11-30T11:55:19.000000Z",
    *                         "updated_at": "2021-11-30T11:55:19.000000Z",
    *                         "thumbnail_path": "assets/media/noimage.png",
    *                         "image_path": "assets/media/noimage.png"
    *                       },
    *                       {
    *                         "id": 7,
    *                         "slug": null,
    *                         "text": "Drunk Customer!",
    *                         "image": null,
    *                         "type": "review_by_rider",
    *                         "category": "negative_review",
    *                         "created_at": "2021-11-30T11:55:19.000000Z",
    *                         "updated_at": "2021-11-30T11:55:19.000000Z",
    *                         "thumbnail_path": "assets/media/noimage.png",
    *                         "image_path": "assets/media/noimage.png"
    *                       },
    *                       {
    *                         "id": 8,
    *                         "slug": null,
    *                         "text": "Great Customer!",
    *                         "image": null,
    *                         "type": "review_by_rider",
    *                         "category": "positive_review",
    *                         "created_at": "2021-11-30T11:55:19.000000Z",
    *                         "updated_at": "2021-11-30T11:55:19.000000Z",
    *                         "thumbnail_path": "assets/media/noimage.png",
    *                         "image_path": "assets/media/noimage.png"
    *                       },
    *                       {
    *                         "id": 9,
    *                         "slug": null,
    *                         "text": "Good Customer!",
    *                         "image": null,
    *                         "type": "review_by_rider",
    *                         "category": "positive_review",
    *                         "created_at": "2021-11-30T11:55:19.000000Z",
    *                         "updated_at": "2021-11-30T11:55:19.000000Z",
    *                         "thumbnail_path": "assets/media/noimage.png",
    *                         "image_path": "assets/media/noimage.png"
    *                       },
    *                       {
    *                         "id": 10,
    *                         "slug": null,
    *                         "text": "Rider was too Late!",
    *                         "image": null,
    *                         "type": "booking_cancel_by_user",
    *                         "category": "",
    *                         "created_at": "2021-11-30T11:55:19.000000Z",
    *                         "updated_at": "2021-11-30T11:55:19.000000Z",
    *                         "thumbnail_path": "assets/media/noimage.png",
    *                         "image_path": "assets/media/noimage.png"
    *                       },
    *                       {
    *                         "id": 11,
    *                         "slug": null,
    *                         "text": "Customer was unreachable!",
    *                         "image": null,
    *                         "type": "booking_cancel_by_rider",
    *                         "category": "",
    *                         "created_at": "2021-11-30T11:55:19.000000Z",
    *                         "updated_at": "2021-11-30T11:55:19.000000Z",
    *                         "thumbnail_path": "assets/media/noimage.png",
    *                         "image_path": "assets/media/noimage.png"
    *                       }
    *                     }
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
    public function getSuggestions($suggestion_type='all'){

     
        //INPUT VALIDATIONS
        $allowed_suggestion_types = ['review_by_user','review_by_rider','booking_cancel_by_rider','booking_cancel_by_user', 'all'];
        if(!in_array( $suggestion_type, $allowed_suggestion_types))
        {
            //422 response
            $response = ['message' => "The suggestion type can only be one of 'review_by_user','review_by_rider','booking_cancel_by_rider','booking_cancel_by_user',or 'all'  !"];
            return response($response, 422);
        }

        
   
        $suggestions = null;

        switch ($suggestion_type) {
            case 'review_by_user':
                $suggestions = $this->suggestion->getReviewbyUserSuggestion();
                break;
            case 'review_by_rider':
                $suggestions = $this->suggestion->getReviewbyRiderSuggestion();
                break;
            case 'booking_cancel_by_rider':
                $suggestions = $this->suggestion->getBookingCancelByRiderSuggestion();
                break;
            case 'booking_cancel_by_user':
                $suggestions = $this->suggestion->getBookingCancelByUserSuggestion();
                break;
            default:
                $suggestions = $this->suggestion->getAllSuggestions();
          } 

          $response = ['message' => "Success!", 'suggestions'=>$suggestions];
          return response($response, 200);


          $response = ['message' => "Something went wrong! Internal Server Error!"];
          return response($response, 500);
    }



}
