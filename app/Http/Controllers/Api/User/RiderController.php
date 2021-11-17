<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

//services
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\Rider;
use App\Modules\Models\User;

class RiderController extends Controller
{
    
    
    protected $rider, $user_service;

    public function __construct(Rider $rider, UserService $user_service)
    {
        $this->rider = $rider;
        $this->user_service = $user_service;
    }
    

    /**
    * @OA\Get(
    *   path="/api/rider/details",
    *   tags={"Details"},
    *   summary="Rider Details",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(      
    *             example=	{
    *               "message": "Success!",
    *               "user": {
    *                   "id": 4,
    *                   "slug": "gintama-d-luffy",
    *                   "first_name": "Gintama",
    *                   "middle_name": "D.",
    *                   "last_name": "Luffy",
    *                   "image": "file()",
    *                   "dob": "2000-01-01",
    *                   "gender": null,
    *                   "google_id": null,
    *                   "facebook_id": null,
    *                   "username": null,
    *                   "phone": "9816810976",
    *                   "email": "gintama@gmail.com",
    *                   "status": null,
    *                   "email_verified_at": null,
    *                   "last_logged_in": null,
    *                   "no_of_logins": null,
    *                   "avatar": null,
    *                   "deleted_at": null,
    *                   "last_updated_by": null,
    *                   "last_deleted_by": null,
    *                   "created_at": "2021-11-16T08:09:03.000000Z",
    *                   "updated_at": "2021-11-16T08:09:03.000000Z",
    *                   "name": "Gintama D. Luffy",
    *                   "rider": {
    *                     "id": 2,
    *                     "user_id": 4,
    *                     "experience": 5,
    *                     "trained": "YES",
    *                     "status": "in_active",
    *                     "approved_at": null,
    *                     "deleted_at": null,
    *                     "last_deleted_by": null,
    *                     "last_updated_by": null,
    *                     "created_at": "2021-11-16T08:09:03.000000Z",
    *                     "updated_at": "2021-11-16T08:09:03.000000Z",
    *                     "vehicle": {
    *                       "id": 1,
    *                       "slug": "ba-99-pa-5544",
    *                       "rider_id": 2,
    *                       "vehicle_type_id": 1,
    *                       "vehicle_number": "BA 99 PA 5544",
    *                       "image": null,
    *                       "make_year": "2016",
    *                       "vehicle_color": "black",
    *                       "brand": "CF MOTO",
    *                       "model": "NK 250",
    *                       "status": "in_active",
    *                       "deleted_at": null,
    *                       "last_deleted_by": null,
    *                       "last_updated_by": null,
    *                       "created_at": "2021-11-16T08:09:03.000000Z",
    *                       "updated_at": "2021-11-16T08:09:03.000000Z",
    *                       "thumbnail_path": "assets/media/noimage.png",
    *                       "image_path": "assets/media/noimage.png",
    *                       "documents": "[]"
    *                     },
    *                     "documents": {
    *                       {
    *                         "id": 1,
    *                         "documentable_type": "App\\Modules\\Models\\Rider",
    *                         "documentable_id": 2,
    *                         "type": "license",
    *                         "document_number": "L12345345234",
    *                         "issue_date": "2018-01-01",
    *                         "expire_date": null,
    *                         "image": "file()",
    *                         "deleted_at": null,
    *                         "created_at": "2021-11-16T08:09:03.000000Z",
    *                         "updated_at": "2021-11-16T08:09:03.000000Z",
    *                         "thumbnail_path": "uploads/document/license/thumb/file()",
    *                         "image_path": "uploads/document/license/file()"
    *                       }
    *                     }
    *                   },
    *                   "documents": "[]"
    *                 }
    *             }             
    *           )
    *      )
    *   ),
    *   @OA\Response(
    *      response=403,
    *       description="Forbidden Access!",
    *   )
    *)
    **/
    public function getDetails(){
        $user = Auth::user();
       
        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
        
        $user = User::where('id',$user->id)->with('rider')->with('documents')->first();

        $response = ['message' => 'Success!',  "user"=>$user];
        return response($response, 200);
    }




}
