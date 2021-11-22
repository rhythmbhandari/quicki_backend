<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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



    /**
    * @OA\Post(
    *   path="/api/user/profile/update",
    *   tags={"Profile"},
    *   summary="Update User Profile",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                 "first_name": "Monkey",
    *                 "middle_name": "D.",
    *                 "last_name": "Luffy",
    *                 "image": "file()",
    *                 "email": "luffy@gmail.com",
    *                 "username": "luffy",
    *                 "dob": "2000/01/01",
    *                 "gender": "male",
    *              }
    *         )
    *     )
    *   ),
    *
    *      @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                           "message":"User Registration Successful!",
    *                           "user":{
    *                                 "id": 4,
    *                                 "slug": "gintama-d-luffy",
    *                                 "first_name": "Gintama",
    *                                 "middle_name": "D.",
    *                                 "last_name": "Luffy",
    *                                 "image": "file()",
    *                                 "dob": "2000-01-01",
    *                                 "gender": null,
    *                                 "google_id": null,
    *                                 "facebook_id": null,
    *                                 "username": "luffy",
    *                                 "phone": "9816810976",
    *                                 "email": "gintama@gmail.com",
    *                                 "status": null,
    *                                 "email_verified_at": null,
    *                                 "last_logged_in": null,
    *                                 "no_of_logins": null,
    *                                 "avatar": null,
    *                                 "deleted_at": null,
    *                                 "last_updated_by": null,
    *                                 "last_deleted_by": null,
    *                                 "created_at": "2021-11-16T08:09:03.000000Z",
    *                                 "updated_at": "2021-11-16T08:09:03.000000Z",
    *                                 "name": "Gintama D. Luffy"
    *                               },
    *                   }
    *                 )
    *           )
    *      ),
    *
    *      @OA\Response(
    *          response=422,
    *          description="Validation Fail",
    *             @OA\MediaType(
     *           mediaType="application/json",
     *      )
    *      ),
     *      @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
    *
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        //VALIDATIONS
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'username' => 'nullable|string|max:255|unique:users,username,'.$user->id,
            'email' => 'nullable|string|email|max:255|unique:users,email,'.$user->id,
            'dob' => 'nullable',
            'gender' => 'nullable',
            //'password' => 'nullable|string|min:6|confirmed',
           // 'phone' => 'nullable|string|min:10|unique:users,phone,'.$user->id,
          //  'dob' => 'nullable',
          //  'gender' => 'nullable',
          //  'google_id' => 'nullable|unique:users',
          //  'facebook_id' => 'nullable|unique:users'
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        //ROLE CHECK FOR CUSTOMER
        if( ! $this->user->hasRole($user, 'customer') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        //UPDATE USER
        return DB::transaction(function () use ($request,$user)
        {
            $updatedUser = $this->user->update($user->id,$request->all());
    
            if($updatedUser)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $updatedUser);
                }
                $response = ['message' => 'User Profile Updated Successful!',  "user"=>Auth::user()];
                return response($response, 200);
            }
            return response("Internal Server Error!", 500);
        });

    }



    function uploadFile(Request $request, $user)
    {
        $file = $request->file('image');
        $fileName = $this->user->uploadFile($file);
        if (!empty($user->image))
            $this->user->__deleteImages($user);

        $data['image'] = $fileName;
        $this->user->updateImage($user->id, $data);
    }


    



}
