<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

//services
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\User;
use App\Modules\Models\CompletedTrip;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserService $user)
    {
        $this->user = $user;
    }
    
    
    /**
    * @OA\Get(
    *   path="/api/user/details",
    *   tags={"Details"},
    *   summary="User Details",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(      
    *             example=
    *             {
    *               "message": "Success!",
    *               "user": {
    *                 "id": 4,
    *                 "slug": "gintama-d-luffy",
    *                 "first_name": "Gintama",
    *                 "middle_name": "D.",
    *                 "last_name": "Luffy",
    *                 "image": "file()",
    *                 "dob": "2000-01-01",
    *                 "gender": null,
    *                 "google_id": null,
    *                 "facebook_id": null,
    *                 "username": null,
    *                 "phone": "9816810976",
    *                 "email": "gintama@gmail.com",
    *                 "status": null,
    *                 "email_verified_at": null,
    *                 "last_logged_in": null,
    *                 "no_of_logins": null,
    *                 "avatar": null,
    *                 "deleted_at": null,
    *                 "last_updated_by": null,
    *                 "last_deleted_by": null,
    *                 "created_at": "2021-11-16T08:09:03.000000Z",
    *                 "updated_at": "2021-11-16T08:09:03.000000Z",
    *                 "name": "Gintama D. Luffy"
    *               }
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

        //ROLE CHECK FOR CUSTOMER
        // if( ! $this->user->hasRole($user, 'customer') )
        // {
        //     $response = ['message' => 'Forbidden Access!'];
        //     return response($response, 403);
        // }

        $response = ['message' => 'Success!',  "user"=>$user];
        return response($response, 200);
    }





}
