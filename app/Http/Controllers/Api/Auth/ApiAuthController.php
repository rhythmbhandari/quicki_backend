<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Traits\SparrowSms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


//requests
use App\Http\Requests\Api\User\UserRequest;
use App\Http\Requests\Api\User\RiderRequest;
use App\Http\Requests\Api\User\UserToRiderRequest;

//services
use App\Modules\Services\User\UserService;
use App\Modules\Services\User\RiderService;
use App\Modules\Services\Vehicle\VehicleService;
use App\Modules\Services\Vehicle\VehicleTypeService;
use App\Modules\Services\Document\DocumentService;

//models
use App\Modules\Models\Otp;
use App\Modules\Models\Rider;
use App\Modules\Models\User;
use App\Modules\Models\Role;

class ApiAuthController extends Controller
{

    use SparrowSms;

    protected $user, $rider, $vehicle, $vehicle_type, $document;

    public function __construct(UserService $user, RiderService $rider, VehicleService $vehicle, VehicleTypeService $vehicle_type, DocumentService $document )
    {
        $this->user = $user;
        $this->rider = $rider;
        $this->vehicle = $vehicle;
        $this->vehicle_type = $vehicle_type;
        $this->document = $document;
    }   

    /**
    * @OA\Post(
    *   path="/api/user/register",
    *   tags={"Register and Authentication"},
    *   summary="Register User",
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
    *                 "username": "luffy",
    *                 "image": "file()",
    *                 "email": "luffy@gmail.com",
    *                 "phone": "9816810976",
    *                 "gender": "male",
    *                 "password": "password",
    *                 "password_confirmation": "password",
    *                 "dob": "2000/01/01",
    *                  "facebook_id" : "",
    *                  "google_id" : "",
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
    *                           "access_token":"123sfsdr234sdfs",
    *                           "user":"{created_user}",
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
    public function register(UserRequest $request)
    {

        return DB::transaction(function () use ($request)
        {
            $createdUser = $this->user->create($request->all());

            if($createdUser)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $createdUser, $this->user);
                }
                $accessToken = $createdUser->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['message' => 'User Registration Successful!', 'access_token' => $accessToken, "user"=>$createdUser];
                return response($response, 201);
            }
            return response("Internal Server Error!", 500);
        });

      
    }




        /**
    * @OA\Post(
    *   path="/api/rider/register",
    *   tags={"Register and Authentication"},
    *   summary="Register Rider",
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "first_name": "Monkey",
    *                  "middle_name": "D.",
    *                  "last_name": "Luffy",
    *                 "username": "luffy",
    *                  "image": "file()",
    *                  "email": "luffy@gmail.com",
    *                  "phone": "9816810976",
    *                  "gender": "male",
    *                  "password": "password",
    *                  "password_confirmation": "password",
    *                  "dob": "2000/01/01",
    *                   "facebook_id" : "",
    *                   "google_id" : "",                       
    *                 "rider":{
    *                     "experience":"5",
    *                     "trained":"YES",
    *                 },
    *                 "document": {
    *                        "document_number": "L12345345234",
    *                        "type": "license",
    *                        "issue_date": "2018/01/01",
    *                        "expiry_date": "2018/01/01",
    *                        "image": "file()",
    *                  },
    *                 "vehicle": {
    *                         "vehicle_type_id":"1",
    *                         "vehicle_number":"BA 99 PA 5544",
    *                         "brand":"TVS",
    *                         "model":"Apache 160R",
    *                         "vehicle_color":"black",
    *                         "make_year":"2016",
    *                  }
    *                   
    *             }
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
    *                           "message":"Rider Registration Successful!",
    *                           "access_token":"123sfsdr234sdfs",
    *                           "rider":"{created_rider}",
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
     *          )
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    public function rider_register(RiderRequest $request)
    {


        //dd("RIDER DATA:",$request->all(), $request->all()['document']);
        return DB::transaction(function () use ($request)
        {
            $createdRider = $this->rider->create($request->all());

            if($createdRider)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $createdRider->user, $this->user);
                }
                if ($request->hasFile('document.image')) {
                    $this->uploadFile($request, $createdRider->latest_document, $this->document);
                }
                if ($request->hasFile('vehicle.image')) {
                    $this->uploadFile($request, $createdRider->vehicle, $this->vehicle);
                }
            
                $accessToken = $createdRider->user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['message' => 'Rider Registration Successful!', 'access_token' => $accessToken, "rider"=>$createdRider, "user"=>$createdRider->user,];
                return response($response, 200);
            }
            return response("Internal Server Error!", 500);
        });

       
      
    }


    /**
    * @OA\Post(
    *   path="/api/sms/send",
    *   tags={"Send and Verify SMS/OTP"},
    *   summary="Send SMS",
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "phone": "9816810976",
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
    *                           "message":"SMS sent successfully!",
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
     *          )
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    public function send_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' =>  ['required'],
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        $code = rand(10000, 99999); //generate random code
        $request['code'] = $code; //add code in $request body
        $request['phone'] = $request->phone;
        $otp = Otp::where('phone', '=', $request->phone)->first();
        if ($otp) {
            $otp->update(['code' => $code]);
            return $this->sendSms($request); // send and return its response
        } else {
            Otp::create($request->all()); //call store method of model
            return $this->sendSms($request); // send and return its response
        }
    }


    /**
    * @OA\Post(
    *   path="/api/sms/verify_user",
    *   tags={"Send and Verify SMS/OTP"},
    *   summary="Verify User OTP",
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "phone": "9816810976",
    *                   "code": "223305",
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
    *                     "message": "User exits and verified! Login Successful!",
    *                     "data": {
    *                       "id": 2,
    *                       "slug": "sasuke-uchiha",
    *                       "first_name": "Sasuke",
    *                       "middle_name": "",
    *                       "last_name": "Uchiha",
    *                       "image": null,
    *                       "dob": null,
    *                       "gender": null,
    *                        "location": {
    *                             "home": {
    *                               "name": "Chapagaun, Kathmandu",
    *                               "latitude": 27.691153232923103,
    *                               "longitude": 86.33177163310808
    *                             },
    *                             "work": {
    *                               "name": "Thapagaun, Lalitpur",
    *                               "latitude": 28.687052088825897,
    *                               "longitude": 85.30439019937253
    *                             }
    *                           },
    *                       "google_id": null,
    *                       "facebook_id": null,
    *                       "username": "sasuke",
    *                       "phone": "9869191572",
    *                       "email": "sasuke@gmail.com",
    *                       "status": null,
    *                       "email_verified_at": null,
    *                       "last_logged_in": null,
    *                       "no_of_logins": null,
    *                       "avatar": null,
    *                       "deleted_at": null,
    *                       "last_updated_by": null,
    *                       "last_deleted_by": null,
    *                       "created_at": "2021-11-22T05:49:34.000000Z",
    *                       "updated_at": "2021-11-22T05:49:34.000000Z",
    *                       "name": "Sasuke  Uchiha"
    *                     },
    *                     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYTUxMDFkNDM4ODdiZDIwM2E4N2E2MTdkYWNiM2Y0MjI2ODBhYjllMmI0MzMyY2E3NWI1Nzk2M2Y0ZDZhNTIzODMxNmZmNmYyOTA3MDg1Y2YiLCJpYXQiOjE2Mzc3NDE1NDIuMTAwMjEzLCJuYmYiOjE2Mzc3NDE1NDIuMTAwMjIsImV4cCI6MTY2OTI3NzU0Mi4wODk3MTEsInN1YiI6IjIiLCJzY29wZXMiOltdfQ.kyW7XGw_jCLk_C-4IefPfu0FEgVXtYk9ti8HkO5A1KlqKK2BzcN50UiktnA1TgC2V4R8zNEt5X0OndCoQzaky-aEwOMtohFrVrkOkQRr6X59kUK3xmOahUd2Qajt4GgIFUXU-kMCPghA8ntt6kNtCKD_Zj7P8UVlQJ3lMkvWthK-gedZOrTbW-_ZYTBomcflnzltxU7DA4HAYNuQhzLLCLX1l2u14CY0eBNcJfhJnGzdGyqK_h0So5yp2prNOsgqXvT7hSzTv81otLWdMnvWrzRVHBdAtOnCfWJWhBte0Ny60d_UzroPMZkYw8XFc8DAN0Mipy9tzpoppZ04H1-nG9f2WILRvRYHlsSGTXXF8CIW5aT9xx0sufK3Ai5lkz2S1mnS42_smpAlDi_5NKT15uZvOmgf-yHt6VjHNJRWwdT_BVlBS3sEByqeQ_jZajnue2TFP0ITbs3XDvQmsLcVbTj4_79XDqhRJ6dYkcVHsRV8a6YLTtUM3XrpD9M9UGWKHNd-HbfPro6fZtmp8t82IzkbeIaKaB9nSTPjn0wAa25wvRBeygb4ylBZgYZCuBdXR8WOyabCrtzTQchRaE3e3Np2ykIELggLkVW2zWV2nwROr2GuAZpg1lbSunBglD1kE1l-OFyd5GjS4oHDoOKu9OFgwGo4daaHmj8swh7mvXY"
    *                   }
    *                 )
    *           )
    *      ),
    *       @OA\Response(
    *             response=401,
    *             description="Otp verified but user does not exist!"
    *         ),
    *      @OA\Response(
    *          response=422,
    *          description="Validation Fail",
    *             @OA\MediaType(
     *           mediaType="application/json",
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
    *         description="Incorrect Otp!"
    *      ),
    *)
    **/
    public function verify_user_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' =>  ['required'],
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        $phone = $request->phone;
        $otp = Otp::where('phone', $phone)->first();

      
        if ($otp && $request->code == $otp->code) {
            $otp->update(['code_status' => 'verified']);
            $user = User::where('phone', '=', $otp->phone)->first();
            if ($user) {
                $accessToken = $user->createToken('authToken')->accessToken;
                $response = ['message' => 'User exits and verified! Login Successful!', 'data' => $user, 'access_token' => $accessToken];
                return response($response, 200);
            } else {
                $response = ['message' => 'Unauthorized: Otp verified but user does not exist!'];
                return response($response, 401);
            }
        } else {
            $response = ['message' => 'Incorrect Otp!'];
            return response($response, 404);
        }
    }


    /**
    * @OA\Post(
    *   path="/api/sms/verify_rider",
    *   tags={"Send and Verify SMS/OTP"},
    *   summary="Verify Rider OTP",
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "phone": "9816810976",
    *                   "code": "223305",
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
    *                     "message": "Rider exits and verified! Login Successful!",
    *                     "user": {
    *                       "id": 2,
    *                       "slug": "sasuke-uchiha",
    *                       "first_name": "Sasuke",
    *                       "middle_name": "",
    *                       "last_name": "Uchiha",
    *                       "image": null,
    *                       "dob": null,
    *                       "gender": null,
    *                       "location": {
    *                              "home": {
    *                                "name": "Chapagaun, Kathmandu",
    *                                "latitude": 27.691153232923103,
    *                                "longitude": 86.33177163310808
    *                              },
    *                              "work": {
    *                                "name": "Thapagaun, Lalitpur",
    *                                "latitude": 28.687052088825897,
    *                                "longitude": 85.30439019937253
    *                              }
    *                            },
    *                       "google_id": null,
    *                       "facebook_id": null,
    *                       "username": "sasuke",
    *                       "phone": "9869191572",
    *                       "email": "sasuke@gmail.com",
    *                       "status": null,
    *                       "email_verified_at": null,
    *                       "last_logged_in": null,
    *                       "no_of_logins": null,
    *                       "avatar": null,
    *                       "deleted_at": null,
    *                       "last_updated_by": null,
    *                       "last_deleted_by": null,
    *                       "created_at": "2021-11-22T05:49:34.000000Z",
    *                       "updated_at": "2021-11-22T05:49:34.000000Z",
    *                       "name": "Sasuke  Uchiha",
    *                       "roles": {
    *                         {
    *                           "id": 2,
    *                           "slug": "rider",
    *                           "name": "rider",
    *                           "guard_name": "rider",
    *                           "deleted_at": null,
    *                           "created_at": "2021-11-22T05:49:34.000000Z",
    *                           "updated_at": "2021-11-22T05:49:34.000000Z",
    *                           "pivot": {
    *                             "user_id": 2,
    *                             "role_id": 2,
    *                             "created_at": "2021-11-22T05:49:34.000000Z",
    *                             "updated_at": "2021-11-22T05:49:34.000000Z"
    *                           }
    *                         },
    *                         {
    *                           "id": 3,
    *                           "slug": "customer",
    *                           "name": "customer",
    *                           "guard_name": "customer",
    *                           "deleted_at": null,
    *                           "created_at": "2021-11-22T05:49:34.000000Z",
    *                           "updated_at": "2021-11-22T05:49:34.000000Z",
    *                           "pivot": {
    *                             "user_id": 2,
    *                             "role_id": 3,
    *                             "created_at": "2021-11-22T05:49:34.000000Z",
    *                             "updated_at": "2021-11-22T05:49:34.000000Z"
    *                           }
    *                         }
    *                       },
    *                       "rider": {
    *                         "id": 1,
    *                         "user_id": 2,
    *                         "experience": 3,
    *                         "trained": "YES",
    *                         "status": "active",
    *                         "approved_at": "2021-11-22 05:49:34",
    *                         "deleted_at": null,
    *                         "last_deleted_by": null,
    *                         "last_updated_by": null,
    *                         "created_at": "2021-11-22T05:49:34.000000Z",
    *                         "updated_at": "2021-11-22T05:49:34.000000Z",
    *                         "vehicle": null,
    *                         "documents": {}
    *                       }
    *                     },
    *                     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNWU1NDM3Y2NlNGQxZjA5MzUyMzk5MjE4NDk1MDdjNDBkMzRjNzdkZjk1YzhjY2IyMTM3M2M2ZjdjOGVhOTU0MmNmOGU2MGQ1M2JjZTlhZTUiLCJpYXQiOjE2Mzc3NDE0MTYuNDU2MjU5LCJuYmYiOjE2Mzc3NDE0MTYuNDU2MjYzLCJleHAiOjE2NjkyNzc0MTYuNDQ5OTU2LCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.ZC6uwA-w5JZwygJnRE9hm8dWjEn0t-huweU_cgZnwI_aLqV4TR_-Pau5g9GN5znBXcsUT6wfZKK4BpHCtLAe3rkQpTwSlKOp9opx-E2Kl5ZesFG0lulnqlphN3bqkhpMwM9wlR6PPuQOXuLeGqWhvQM6xca9LD1YTHMQ80S9o3JVJ-gEEXdspyEfNs_xF7HK-oJ8ZLWqvmgE_5fSlZ5vWidun2W4c7gRfUFvXKyMxzc2D7hKhYtjIgmzY0NkWr3XM74tBH4g9nzuEZajhD3w8zo3jnUa-Wvkh2p9qWudDWdxyph1OKVoYfzfdEhcu2eEPYgdOs7BmSnT6wXXGqfHeNqkN6cIn4Pshi-hdI1WYvxVTImeseNbTgRIZhJUzdUrN4clRvAEpBSb2kqa5BDcO7iIMa3VTRgDj8EXFdyfhDIVaNuTbHSFJcNnJTN4sMLvYCmJJjtOdReCH9B4Ii1m4-2SMdbVFymLa1-oB4onCbL-AlwVVxTKTIbzzTOkJTS0ye4j1YhThYV1EHxRLA2944R3HrVcFixjt07JPZ2_j-YkEeNiVc_CBnXwKTCG84E9vfU47GC4sLTlnuZwipRzjBb2SQXY4w6zvSm0jjiBLmCOhpH8mbPhDgFXaLt5kWezCfa0QecpA3fTHY0TkDlI_fQQ9K0NrGstqEBueTPe5e8"
    *                   }
    *                 )
    *           )
    *      ),
    *       @OA\Response(
    *             response=401,
    *             description="Unauthorized: Otp verified but rider does not Exist!"
    *         ),
    *         @OA\Response(
    *             response=400,
    *             description="Forbidden Access: User exists but is not registered as a rider!"
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
    *         description="Incorrect Otp!"
    *      ),
    *)
    **/
    public function verify_rider_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' =>  'required',
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }


        $phone = $request->phone;
        // echo $phone;
        // dd($phone);
        $otp = Otp::where('phone', $phone)->first();
        //dd("OTP:",$otp);
        if ($otp && $request->code == $otp->code) {
            $otp->update(['code_status' => 'verified']);
            $user = User::where('phone', '=', $otp->phone)->first();
            if ($user) {
                if( $this->user->hasRole($user, 'rider'))
                {
                    $rider = $user->rider;
                   // dd($rider);
                    $accessToken = $user->createToken('authToken')->accessToken;
                    $response = ['message' => 'Rider exits and verified! Login Successful!','user'=>$user,  'access_token' => $accessToken];
                    return response($response, 200);
                }
                $accessToken = $user->createToken('User Token!')->accessToken;
                $response = ['message' => 'Forbidden Access: User exists but is not registered as a rider!','access_token'=>$accessToken,'user'=>$user];
                return response($response, 400);
            } else {
                $response = ['message' => 'Unauthorized: Otp verified but rider does not Exist!'];
                return response($response, 401);
            }
        } else {
            $response = ['message' => 'Incorrect Otp!'];
            return response($response, 404);
        }
    }



       


/**
    * @OA\Post(
    *   path="/api/user/upgrade_to_rider",
    *   tags={"Register and Authentication"},
    *   summary="Upgrade To Rider",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={                     
    *                 "rider":{
    *                     "experience":"5",
    *                     "trained":"YES",
    *                 },
    *                 "document": {
    *                        "document_number": "L12345345234",
    *                        "type": "license",
    *                        "issue_date": "2018/01/01",
    *                        "expiry_date": "2018/01/01",
    *                        "image": "file()",
    *                  },
    *                 "vehicle": {
    *                         "vehicle_type_id":"1",
    *                         "vehicle_number":"BA 99 PA 5544",
    *                         "brand":"TVS",
    *                         "model":"Apache 160R",
    *                         "vehicle_color":"black",
    *                         "make_year":"2016",
    *                  }
    *                   
    *             }
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
    *                           "message":"Rider Registration Successful!",
    *                           "access_token":"123sfsdr234sdfs",
    *                           "rider":"{created_rider}",
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
     *          )
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    public function upgrade_to_rider(UserToRider $request)  //Authentication token required of user
    {
        
        $user = Auth::user();
    

        //dd("RIDER DATA:",$request->all(), $request->all()['document']);
        return DB::transaction(function () use ($request, $user)
        { 
            $createdRider = $this->rider->create($request->all(),$user);
           // dd("rider creating: ",$createdRider,$createdRider->user);
            if($createdRider)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $createdRider->user, $this->user);
                }
                if ($request->hasFile('document.image')) {
                    $this->uploadFile($request, $createdRider->latest_document, $this->document);
                }
                if ($request->hasFile('vehicle.image')) {
                    $this->uploadFile($request, $createdRider->vehicle, $this->vehicle);
                }
            
                $accessToken = $createdRider->user->createToken('Laravel Password Grant Client')->accessToken;
              
                $response = ['message' => 'Rider Registration Successful!', 'tokaccess_tokenen' => $accessToken, "rider"=>$createdRider, "user"=>$createdRider->user,];
                return response($response, 200);
            }
            return response("Internal Server Error!", 500);
        });


    }




    /**
    * @OA\Post(
    *   path="/api/logout",
    *   tags={"Logout"},
    *   summary="Logout",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *   )
    *)
    **/
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }


    function uploadFile(Request $request, $model_object, $service=null)
    {
        try{
            $file = $request->file('image');
            $fileName = $service->uploadFile($file);
            if (!empty($model_object->image))
                $service->__deleteImages($model_object);
    
            $data['image'] = $fileName;
            $service->updateImage($model_object->id, $data);
        }
        catch(Exception $e)
        {
            //do nothing
        }
            
       
    }










    // function test(Request $request)
    // {
    //     //dd($request->all());
    //     $user = User::find($request->id);
    //     if($user)
    //     {
    //         dd($this->user->hasRole($user, $request->role));
    //     }
        
    // }

}
