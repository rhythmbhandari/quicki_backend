<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//requests
use App\Http\Requests\Api\User\UserProfileRequest;
use App\Http\Requests\Api\Location\UpdateUserLocationRequest;

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
    *   @OA\Parameter(
    *      name="device_token",
    *      in="header",
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
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
    *                 "emergency_contacts":{"9816810976","987654321","981122345"},
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
        /**
    * @OA\Get(
    *   path="/api/user/{user_id}/details",
    *   tags={"Details"},
    *   summary="User Details",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="user_id",
    *         in="path",
    *         description="User ID",
    *         required=true,
    *      ),
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
    *                "location": {
    *                     "home": {
    *                       "name": "Chapagaun, Kathmandu",
    *                       "latitude": 27.691153232923103,
    *                       "longitude": 86.33177163310808
    *                     },
    *                     "work": {
    *                       "name": "Thapagaun, Lalitpur",
    *                       "latitude": 28.687052088825897,
    *                       "longitude": 85.30439019937253
    *                     }
    *                   },
    *                 "google_id": null,
    *                 "facebook_id": null,
    *                 "username": null,
    *                 "phone": "9816810976",
    *                 "email": "gintama@gmail.com",
    *                  "emergency_contacts":{"9816810976","987654321","981122345"},
    *                 "status": null,
    *                 "email_verified_at": null,
    *                 "last_logged_in": null,
    *                 "no_of_logins": null,
    *                 "avatar": null,
    *                 "social_image_url":"https://images.unsplash.com/photo-1607335614551-3062bf90f30e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80",
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
    *   ),
    *   @OA\Response(
    *      response=404,
    *       description="User Not Found!",
    *   )
    *)
    **/
    public function getDetails(Request $request, $user_id=null)
    {        
        // $user = ($user_id != null) ? User::findOrFail($user_id) : Auth::user();

        $user = null;
        if($user_id == null)
        {
            $user = Auth::user();
            $user->device_token = $request->header('device_token');
            $user->save();
        }
        else{
            $user = User::find($user_id);
            if(!$user)  {
                $response = ['message' => 'User not found!'];
                return response($response, 404);
            }
        }


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
    *                 "social_image_url":"https://images.unsplash.com/photo-1607335614551-3062bf90f30e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80",
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
    *                                  "location": {
    *                                       "home": {
    *                                         "name": "Chapagaun, Kathmandu",
    *                                         "latitude": 27.691153232923103,
    *                                         "longitude": 86.33177163310808
    *                                       },
    *                                       "work": {
    *                                         "name": "Thapagaun, Lalitpur",
    *                                         "latitude": 28.687052088825897,
    *                                         "longitude": 85.30439019937253
    *                                       }
    *                                     },
    *                                 "google_id": null,
    *                                 "facebook_id": null,
    *                                 "username": "luffy",
    *                                 "phone": "9816810976",
    *                                 "email": "gintama@gmail.com",
    *                               "emergency_contacts": {"9816810976","987654321","981122345"},
    *                                 "status": null,
    *                                 "email_verified_at": null,
    *                                 "last_logged_in": null,
    *                                 "no_of_logins": null,
    *                                 "avatar": null,
    *                                 "social_image_url":"https://images.unsplash.com/photo-1607335614551-3062bf90f30e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80",
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
    public function updateProfile(UserProfileRequest $request)
    {
        $user = Auth::user();

        //ROLE CHECK FOR CUSTOMER
        if( ! $this->user->hasRole($user, 'customer') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        //UPDATE USER
        return DB::transaction(function () use ($request,$user)
        {
            $updatedUser = $this->user->update($user->id,$request->except('username'));
    
            if($updatedUser)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $updatedUser);
                }
                else if (isset($request->social_image_url) && !is_null($request->social_image_url)) {
                   
                    $url = $request->social_image_url;
                    $this->user->uploadSocialImage($updatedUser, $url);

                } else {
                    //$fileNameToStore1 = 'no-image.png';
                }

                $response = ['message' => 'User Profile Updated Successful!',  "user"=>$updatedUser];
                return response($response, 200);
            }
            return response("Internal Server Error!", 500);
        });

    }



    /**
    * @OA\Post(
    *   path="/api/user/location/update",
    *   tags={"Profile"},
    *   summary="Update User Location",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                   "location": {
    *                        "home": {
    *                               "name": "New Baneshwor, Kathmandu",
    *                               "latitude": 27.691153232923103,
    *                               "longitude": 85.33177163310808
    *                           },
    *                        "work": {
    *                               "name": "Sanepa, Lalitpur",
    *                               "latitude": 27.687052088825897,
    *                               "longitude": 85.30439019937253
    *                           }
    *                   }
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
    *                           "message":"User Location Updated Successfully!",
    *                           
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
    function update_location(UpdateUserLocationRequest $request)
    {
       // dd('validator', $request->all());
        $user = Auth::user();
     
       // dd('validator', $request->all());

      //
        $updatedUserLocation = $this->user->update_location($user->id,$request->all());
       
        if($updatedUserLocation)
        {
            if ($request->hasFile('image')) {
                $this->uploadFile($request, $updatedUserLocation);
            }
            $response = ['message' => 'User Location Updated Successful!'];
            return response($response, 200);
        }
        return response("Internal Server Error!", 500);
    
    }


 /**
    * @OA\Get(
    *   path="/api/user/emergency_contacts",
    *   tags={"Emergency Contacts"},
    *   summary="Get Emergency Contacts of Authenticated User",
    *   security={{"bearerAuth":{}}},
    *
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                     "message": "Success!",
    *                     "emergency_contacts": {
    *                       "9816810976",
    *                       "987654321",
    *                       "981122345"
    *                     }
    *                   }
    *                 )
    *           )
    *      ),
    *)
    **/
    public function getEmergencyContacts()
    {
        $user = Auth::user();

        $emergency_contacts = $user->emergency_contacts;
        $response = ['message' => 'Success!', 'emergency_contacts'=>$emergency_contacts];
        return response($response, 200);

    }



     /**
    * @OA\Get(
    *   path="/api/user/{user_id}/emergency_contacts",
    *   tags={"Emergency Contacts"},
    *   summary="Get Emergency Contacts of Specified User",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="user_id",
    *         in="path",
    *         description="User ID",
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
    *                     "message": "Success!",
    *                     "emergency_contacts": {
    *                       "9816810976",
    *                       "987654321",
    *                       "981122345"
    *                     }
    *                   }
    *                 )
    *           )
    *      ),
        *
    *      @OA\Response(
    *          response=422,
    *          description="User not found!",
    *             @OA\MediaType(
     *           mediaType="application/json",
     *      )
    *      ),
    *
    *)
    **/
    public function getUserEmergencyContacts($user_id)
    {
        $user = User::find($user_id);
        if(!$user)
        {
            $response = ['message' => 'User not found!'];
            return response($response, 422);
        }

        $emergency_contacts = $user->emergency_contacts;
        $response = ['message' => 'Success!', 'emergency_contacts'=>$emergency_contacts];
        return response($response, 200);

    }


     /**
    * @OA\Post(
    *   path="/api/user/emergency_contacts/update",
    *   tags={"Emergency Contacts"},
    *   summary="Update Authenticated user's emergency contacts",
    *   security={{"bearerAuth":{}}},
    *
    *    @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                   "emergency_contacts": {
    *                        "9816810976","123456","1234555"
    *                   }
    *              }
    *         )
    *     )
    *   ),
    *      @OA\Response(
    *        response=200,
    *        description="Emergency Contacts Updated Successfully!",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                @OA\Schema(      
    *                   example={
    *                     "message": "Success!",
    *                     "emergency_contacts": {
    *                       "9816810976",
    *                       "987654321",
    *                       "981122345"
    *                     }
    *                   }
    *                 )
    *           )
    *      ),
    *      @OA\Response(
    *          response=422,
    *          description="Validation Error!",
    *             @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *      ),
    *
    *      @OA\Response(
    *          response=500,
    *          description="Something went wrong! Internal Server Error!",
    *             @OA\MediaType(
    *              mediaType="application/json",
    *          )
    *      ),
    *)
    **/
    public function updateEmergencyContacts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emergency_contacts'=>'required'
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        $user = Auth::user();
        $user->emergency_contacts = $request['emergency_contacts'];
        $updated = $user->save();

        if($updated)
        {
            $emergency_contacts = Auth::user()->emergency_contacts;
            $response = ['message' => 'Emergency Contacts Updated Successfully!', 'emergency_contacts'=>$emergency_contacts];
            return response($response, 200);
        }
      
        $response = ['message' => 'Something went wrong! Internal Server Error!'];
        return response($response, 500);
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
