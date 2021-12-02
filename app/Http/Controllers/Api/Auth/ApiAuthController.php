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

use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;
use Intervention\Image\Facades\Image;


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

    protected $user, $rider, $vehicle, $vehicle_type, $document, $user_service;

    public function __construct(UserService $user, RiderService $rider, VehicleService $vehicle, VehicleTypeService $vehicle_type, DocumentService $document )
    {
        $this->user_service = $user;
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
    *                "emergency_contacts": {"9816810976","987654321","981122345"},
    *                 "phone": "9816810976",
    *                 "gender": "male",
    *                 "password": "password",
    *                 "password_confirmation": "password",
    *                 "dob": "2000/01/01",
    *                  "facebook_id" : "",
    *                  "google_id" : "",
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
    *                           "access_token":"123sfsdr234sdfs",
    *                           "user":{
    *                                 "message": "User Registration Successful!",
    *                                 "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMjFiNDAxNzM2ZWIwZGQwYmYxM2M2MGI1NDM3OWZlMGI0OGRjNjllMTJlZDJhMWNjODc5MjVlMDhiMWI3YzYwZjRlNTg4MDkxYTcxZWI0OTQiLCJpYXQiOjE2Mzg0NDg0NjMuNDg1Mjg3LCJuYmYiOjE2Mzg0NDg0NjMuNDg1MjksImV4cCI6MTY2OTk4NDQ2My40ODE3NDQsInN1YiI6IjU2Iiwic2NvcGVzIjpbXX0.P1DOm1Zxgcs9hvrbEAmI6zUNorSlSzRY5EGdgFxOkENVvHeCRUJvcr9HugEFEJYoDM-ryVgDbjDR5c-L0xNMRMhzPRfokCULoSnInjElCDCJ0xUl0WnXZ5q6mpkz6hW-nwF4E5VHav0mD75pUrHWiffDVYfy8mYzjw2CK3-wTSjH6e1rLHYcl5XZOaCam5BB3BbjsNuNOvegtfHpYnILmxNV7NNv_sXkPopV4eckZY3j_CvvhJuxSPQgYPMLb9OFUIHyanEChIHvZ1hTAG5W8ktlEuS_XFxUfNdATWyJblemzz_lbqGlxSDZRhSpDkXLrKOWhQ67aUgVqWevLVWFwwPl0VFXk6-_VNpf3TlZ0g_GWiHeyP0uTneDo5R9KpwGBAPt1bkgblVFCP1HFw2w6AaesEcgBklfs4bF3O9PHEIDTX0uv79o_bwh745TbS3bXn39nGgYY0WYGgBxGbRN3vqlv7ON8CSNZotnu18JbuADeM0dYlTICh6Pcl-kt6mZAT1eqWycx4Pyzf4oawJviJp81P9c_pvH4HCLNoeRHn-Qpy27Gsj8mHJK3udWQ8twwGxCyktoEZNSPynJz2J8KVQffgpVLG1dpIJ3F0QTPg_ou2_VQCr0xr6mqD_iwO4z4qyCuoXiSGLG4Kzq_JMRtMEUTSrf-hNd_EUMCd9H2K0",
    *                                 "user": {
    *                                   "id": 56,
    *                                   "slug": "monkey-d-luffy-22",
    *                                   "first_name": "Monkey",
    *                                   "middle_name": "D.",
    *                                   "last_name": "Luffy",
    *                                   "image": "4f232ddc57a1b5a7f10b6616de6227493e9560ba.webp",
    *                                   "dob": "2000-01-01",
    *                                   "gender": null,
    *                                   "google_id": null,
    *                                   "facebook_id": null,
    *                                   "social_image_url": "https://images.unsplash.com/photo-1607335614551-3062bf90f30e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80",
    *                                   "username": "monke_@4083",
    *                                   "phone": "981681019761",
    *                                   "email": "luffyaa@gmail.com",
    *                                   "emergency_contacts": {
    *                                     "9816810976",
    *                                     "987654321",
    *                                     "981122345"
    *                                   },
    *                                   "status": null,
    *                                   "email_verified_at": null,
    *                                   "last_logged_in": null,
    *                                   "no_of_logins": null,
    *                                   "avatar": null,
    *                                   "device_token": null,
    *                                   "deleted_at": null,
    *                                   "last_updated_by": null,
    *                                   "last_deleted_by": null,
    *                                   "created_at": "2021-12-02T12:34:22.000000Z",
    *                                   "updated_at": "2021-12-02T12:34:23.000000Z",
    *                                   "name": "Monkey D. Luffy",
    *                                   "thumbnail_path": "uploads/user//thumb/4f232ddc57a1b5a7f10b6616de6227493e9560ba.webp",
    *                                   "image_path": "uploads/user//4f232ddc57a1b5a7f10b6616de6227493e9560ba.webp"
    *                                 }
    *                               }
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
            $createdUser = $this->user->create($request->except('image','username'));

            if($createdUser)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $createdUser, $this->user);
                }
                else if (isset($request->social_image_url) && !is_null($request->social_image_url)) {
                   
                    $url = $request->social_image_url;
                    $this->user->uploadSocialImage($createdUser, $url);

                } else {
                    //$fileNameToStore1 = 'no-image.png';
                }
                $createdUser = User::find($createdUser->id);
                $accessToken = $createdUser->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['message' => 'User Registration Successful!', 'access_token' => $accessToken, "user"=>$createdUser];
                return response($response, 201);
            }
            return response("Internal Server Error!", 500);
        },3);

      
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
    *                   "emergency_contacts": {"9816810976","987654321","981122345"},
    *                  "phone": "9816810976",
    *                  "gender": "male",
    *                  "password": "password",
    *                  "password_confirmation": "password",
    *                  "dob": "2000/01/01",
    *                   "facebook_id" : "",
    *                   "google_id" : "",    
    *                 "social_image_url":"https://images.unsplash.com/photo-1607335614551-3062bf90f30e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80",                   
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
    *                           "message": "Rider Registration Successful!",
    *                           "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZTVkZWVjMjkzMjQ5YmQ5YmE0MzYwODJhY2UwYjI3MmEwNTMwM2E1NTQzYzY5MTI1ZmQzMjgyZjZhOGRhNTA5YzZhZDczYzg1Yjk2ZTY1Y2EiLCJpYXQiOjE2Mzg0NDMzMjYuOTU3MDg3LCJuYmYiOjE2Mzg0NDMzMjYuOTU3MDk1LCJleHAiOjE2Njk5NzkzMjYuOTUxNzc5LCJzdWIiOiI1NCIsInNjb3BlcyI6W119.Az9lZZbqXZLGWwj3IW5-3RF7uIBn6B9IRxbZsO-sPmH0Z13g3BV8IgCyBm4icdf8e7E1DPSPpRm_XnhZ6zgqvTO7yCSlrmA2M5TqJ86fGpOx67SSIO_8az6XsKJRbvN7mKkCpt2viHnwpZDYZOC9AwC3zuXmFiBuaraDLO4GfN4qIJxYZ3OH37Fa4gD8_F47WV1V89O6ueEGTLxBcpvpFshfy5OqfTVTJ79rWrKgR9c2QgO_u547lXrGKWJkOius3GOAaL-2qlsFRhdkkhfrvxkMeRXMCD8lr_VbCm6UOLRBBFpMKDUQp4VTAyuYUnVM0QewR57hpRr8CbdQ49RQ5x60G-6XIoilE4mbhSkZmYSWNngqm9e20KsisAUU-Df74iFqpj_RsPDinWNWYJI87_PWwBGTbsvYBRe5lyiMFKm7T-n2v5rnDOC2GtGsvYc2FQTp6NeUUZeAK3aQ4TmSOu7cOlznstt6CpK64icnzfEAIUWsTD8_Jye0A2lMCRGtlFOuXCvgybs4fbJNX_AzCm-o-sv2Gszo_8Uv47y2SgJS-pU5Uy1uQkb0btzBgdpHMDcB-yCTxRJ7AVsNNnvzWEZtzuECV9_vuGAJhZLfWtInJmWLgULP0MCo37h99tGjvJ8e7_-VUo4DCt2qWNPVUFhaZJAOrnQuxygG8kljRto",
    *                           "user": {
    *                               "id": 54,
    *                               "slug": "monkey-d-lussssffy",
    *                               "first_name": "Monkey",
    *                               "middle_name": "D.",
    *                               "last_name": "Lussssffy",
    *                               "image": "3715c95fddeb91f6aae323d5db57615610ae7b35.webp",
    *                               "dob": "2000-01-01",
    *                               "gender": null,
    *                               "google_id": null,
    *                               "facebook_id": null,
    *                               "social_image_url": "https://lh3.googleusercontent.com/a-/AOh14GjtusXo_7H_oYKbYI-y82mlZfaGyB8LR8ML0bPj=s96-c",
    *                               "username": "lusfssssssddfy",
    *                               "phone": "9811632833412s0976",
    *                               "email": "lusfesessdsdssfy@gmail.com",
    *                               "emergency_contacts": {"9816810976","987654321","981122345"},
    *                               "status": null,
    *                               "email_verified_at": null,
    *                               "last_logged_in": null,
    *                               "no_of_logins": null,
    *                               "avatar": null,
    *                               "device_token": null,
    *                               "deleted_at": null,
    *                               "last_updated_by": null,
    *                               "last_deleted_by": null,
    *                               "created_at": "2021-12-02T11:08:46.000000Z",
    *                               "updated_at": "2021-12-02T11:08:46.000000Z",
    *                               "name": "Monkey D. Lussssffy",
    *                               "thumbnail_path": "uploads/user//thumb/3715c95fddeb91f6aae323d5db57615610ae7b35.webp",
    *                               "image_path": "uploads/user//3715c95fddeb91f6aae323d5db57615610ae7b35.webp",
    *                               "rider": {
    *                                   "id": 36,
    *                                   "user_id": 54,
    *                                   "experience": 5,
    *                                   "trained": "YES",
    *                                   "status": "in_active",
    *                                   "approved_at": null,
    *                                   "device_token": null,
    *                                   "deleted_at": null,
    *                                   "last_deleted_by": null,
    *                                   "last_updated_by": null,
    *                                   "created_at": "2021-12-02T11:08:46.000000Z",
    *                                   "updated_at": "2021-12-02T11:08:46.000000Z",
    *                                   "vehicle": {
    *                                       "id": 35,
    *                                       "slug": "ba-99-pa-5544-20",
    *                                       "rider_id": 36,
    *                                       "vehicle_type_id": 1,
    *                                       "vehicle_number": "BA 99 PA 5544",
    *                                       "image": null,
    *                                       "make_year": "2016",
    *                                       "vehicle_color": "black",
    *                                       "brand": "TVS",
    *                                       "model": "Apache 160R",
    *                                       "status": "active",
    *                                       "deleted_at": null,
    *                                       "last_deleted_by": null,
    *                                       "last_updated_by": null,
    *                                       "created_at": "2021-12-02T11:08:46.000000Z",
    *                                       "updated_at": "2021-12-02T11:08:46.000000Z",
    *                                       "thumbnail_path": "assets/media/noimage.png",
    *                                       "image_path": "assets/media/noimage.png",
    *                                       "documents": {}
    *                                   },
    *                                   "documents": {
    *                                       {
    *                                           "id": 35,
    *                                           "documentable_type": "App\\Modules\\Models\\Rider",
    *                                           "documentable_id": 36,
    *                                           "type": "license",
    *                                           "document_number": "L12345345234",
    *                                           "issue_date": "2018-01-01",
    *                                           "expiry_date": "2018-01-01",
    *                                           "verified_at": null,
    *                                           "reason": "pending",
    *                                           "image": null,
    *                                           "deleted_at": null,
    *                                           "created_at": "2021-12-02T11:08:46.000000Z",
    *                                           "updated_at": "2021-12-02T11:08:46.000000Z",
    *                                           "thumbnail_path": "assets/media/noimage.png",
    *                                           "image_path": "assets/media/noimage.png"
    *                                       }
    *                                   }
    *                               }
    *                           }
    *                       }
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
            $createdRider = $this->rider->create($request->except('image','document.image','vehicle.image','username'));

            if($createdRider)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $createdRider->user, $this->user);
                } 
                else if (isset($request->social_image_url) && !is_null($request->social_image_url)) {
                   
                    $url = $request->social_image_url;
                    $this->user->uploadSocialImage($createdRider->user, $url);

                } else {
                    //$fileNameToStore1 = 'no-image.png';
                }


                if ($request->hasFile('document.image')) {
                    $this->uploadFile($request, $createdRider->latest_document, $this->document);
                }
                if ($request->hasFile('vehicle.image')) {
                    $this->uploadFile($request, $createdRider->vehicle, $this->vehicle);
                }
                
                /// $accessToken = $createdRider->user->createToken('Laravel Password Grant Client')->accessToken;
                //$response = ['message' => 'Rider Registration Successful!', 'access_token' => $accessToken, "rider"=>$createdRider, "user"=>$createdRider->user,];
               
            
                $createdUser = User::where('id',$createdRider->user->id)->with('rider','rider.documents','rider.vehicle')->first();

                $accessToken = $createdUser->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['message' => 'Rider Registration Successful!', 'access_token' => $accessToken, "user"=>$createdUser,];
                return response($response, 200);
            }
            return response("Internal Server Error!", 500);
        },3);

       
      
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
    *                   "code": "11111",
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
    *                        "emergency_contacts": {"9816810976","987654321","981122345"},
    *                       "status": null,
    *                       "email_verified_at": null,
    *                       "last_logged_in": null,
    *                       "no_of_logins": null,
    *                       "avatar": null,
    *                 "social_image_url":"https://images.unsplash.com/photo-1607335614551-3062bf90f30e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80",
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
    *                   "code": "11111",
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
    *                         "message": "Rider exits and verified! Login Successful!",
    *                         "user": {
    *                           "id": 2,
    *                           "slug": "sasuke-uchiha",
    *                           "first_name": "Sasuke",
    *                           "middle_name": "",
    *                           "last_name": "Uchiha",
    *                           "image": null,
    *                           "dob": null,
    *                           "gender": null,
    *                           "google_id": null,
    *                           "facebook_id": null,
    *                           "username": "sasuke",
    *                           "phone": "9816810976",
    *                           "email": "sasuke@gmail.com",
    *                            "emergency_contacts": {"9816810976","987654321","981122345"},
    *                           "location": {
    *                             "home": {
    *                               "name": "New Baneshwor, Kathmandu",
    *                               "latitude": 27.691153232923103,
    *                               "longitude": 85.33177163310808
    *                             },
    *                             "work": {
    *                               "name": "Sanepa, Lalitpur",
    *                               "latitude": 27.68707,
    *                               "longitude": 85.307253
    *                             }
    *                           },
    *                           "status": null,
    *                           "email_verified_at": null,
    *                           "last_logged_in": null,
    *                           "no_of_logins": null,
    *                           "avatar": null,
    *                           "deleted_at": null,
    *                           "last_updated_by": null,
    *                           "last_deleted_by": null,
    *                           "created_at": "2021-11-25T18:01:44.000000Z",
    *                           "updated_at": "2021-11-25T18:16:46.000000Z",
    *                           "name": "Sasuke  Uchiha",
    *                           "roles": {
    *                             {
    *                               "id": 2,
    *                               "slug": "rider",
    *                               "name": "rider",
    *                               "guard_name": "rider",
    *                               "deleted_at": null,
    *                               "created_at": "2021-11-25T18:01:43.000000Z",
    *                               "updated_at": "2021-11-25T18:01:43.000000Z",
    *                               "pivot": {
    *                                 "user_id": 2,
    *                                 "role_id": 2,
    *                                 "created_at": "2021-11-25T18:01:44.000000Z",
    *                                 "updated_at": "2021-11-25T18:01:44.000000Z"
    *                               }
    *                             },
    *                             {
    *                               "id": 3,
    *                               "slug": "customer",
    *                               "name": "customer",
    *                               "guard_name": "customer",
    *                               "deleted_at": null,
    *                               "created_at": "2021-11-25T18:01:43.000000Z",
    *                               "updated_at": "2021-11-25T18:01:43.000000Z",
    *                               "pivot": {
    *                                 "user_id": 2,
    *                                 "role_id": 3,
    *                                 "created_at": "2021-11-25T18:01:44.000000Z",
    *                                 "updated_at": "2021-11-25T18:01:44.000000Z"
    *                               }
    *                             }
    *                           },
    *                           "rider": {
    *                             "id": 1,
    *                             "user_id": 2,
    *                             "experience": 3,
    *                             "trained": "YES",
    *                             "status": "active",
    *                             "approved_at": "2021-11-25 18:01:44",
    *                             "deleted_at": null,
    *                             "last_deleted_by": null,
    *                             "last_updated_by": null,
    *                             "created_at": "2021-11-25T18:01:44.000000Z",
    *                             "updated_at": "2021-11-25T18:01:44.000000Z",
    *                             "vehicle": {
    *                               "id": 1,
    *                               "slug": "ba-12-pa-1234",
    *                               "rider_id": 1,
    *                               "vehicle_type_id": 1,
    *                               "vehicle_number": "BA 99 PA 1234",
    *                               "image": null,
    *                               "make_year": "2010",
    *                               "vehicle_color": null,
    *                               "brand": "HERO",
    *                               "model": "Splender",
    *                 "social_image_url":"https://images.unsplash.com/photo-1607335614551-3062bf90f30e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80",
    *                               "status": "active",
    *                               "deleted_at": null,
    *                               "last_deleted_by": null,
    *                               "last_updated_by": null,
    *                               "created_at": "2021-11-25T19:53:03.000000Z",
    *                               "updated_at": "2021-11-25T19:57:29.000000Z",
    *                               "thumbnail_path": "assets/media/noimage.png",
    *                               "image_path": "assets/media/noimage.png",
    *                               "documents": {}
    *                             },
    *                             "documents": {}
    *                           }
    *                         },
    *                         "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYjhkZTBmMWVkMmM2MDY0MzA3MWU0ZWI2ZjQ4MDQ5N2Q5ZjQ5OWUxNjdkODg2OGM0NDY4ZmJhZmZhZTc3Y2I1YWEwYmU2ZDA4NGI5MWU2MzYiLCJpYXQiOjE2Mzc5MDU5OTEuMTg5NDYzLCJuYmYiOjE2Mzc5MDU5OTEuMTg5NDY2LCJleHAiOjE2Njk0NDE5OTEuMTg0NzQxLCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.FmqOq7ZZ0llQKKrLCuDmhgWGo5FGgQBg0RW7H7aQ7Va0YjwhQ25b-FzHg3WyHH8n_eezgQak0YYidWm05vw4o2RrPUeEgG9jVdx7wwV7_UolALxp5OiMRJ6NTryRhMHLBTmTeXm7cYY3vHWvvugmlogeqJ4ZQY91q-OmwzSff58uUZAphiO1i9nETXtWoU7NmmLGqeHPePRcIEUcQqQynHu1EzOcZTdmLKfvH7jKyr_IiTq_TMS9100MNa0jgJnHED10_8MQro646ju-qk7LkGKSGj6b7nCmXZN7SGVn3fkMisE-2Y6MU9vQEdW4dum-q2UFndoAOnLAZLqxizZRK-YAPuLs6v60_ciV08BoBytTSgQMSo1ooWw1HCisbcgvNXttSQBGVMbgtCxPjS-pkvaPipiYsas4voOv6QZ7KtX6Y_yb5XOx1L4y6zsgTCLLYEk00YLYxk3_ILueaDl8VwzgBtLqXw2pFPqlOcCJ7QP0pv0LbJqpHzBpkvgsdpZ31PSVkEnfc4pZP5qRmEDvRYGZnc79QBGQ2BofGggaVJwJ07djFNPqLle6FMogjimDlkTFWgBtDB-TPyt8Dg6CqsGbRmKREnYtRoiUlHghwHwZD0yaW_cZbrvIRjKPDRiOFZycpWFPu_gWWMWC_9uNk6kDm1zsuOXZe83-n31Uf1w"
    *                       }
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
    *                     "experience":5,
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
    *                         "vehicle_type_id":1,
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
    *                         "message": "Rider registration success! Login Successful!",
    *                         "user": {
    *                           "id": 2,
    *                           "slug": "sasuke-uchiha",
    *                           "first_name": "Sasuke",
    *                           "middle_name": "",
    *                           "last_name": "Uchiha",
    *                           "image": null,
    *                           "dob": null,
    *                           "gender": null,
    *                           "google_id": null,
    *                           "facebook_id": null,
    *                           "username": "sasuke",
    *                           "phone": "9816810976",
    *                           "email": "sasuke@gmail.com",
    *                            "emergency_contacts": {"9816810976","987654321","981122345"},
    *                           "location": {
    *                             "home": {
    *                               "name": "New Baneshwor, Kathmandu",
    *                               "latitude": 27.691153232,
    *                               "longitude": 85.33177163
    *                             },
    *                             "work": {
    *                               "name": "Sanepa, Lalitpur",
    *                               "latitude": 27.687052088825897,
    *                               "longitude": 85.30439019937253
    *                             }
    *                           },
    *                           "status": null,
    *                           "email_verified_at": null,
    *                           "last_logged_in": null,
    *                           "no_of_logins": null,
    *                           "avatar": null,
    *                           "deleted_at": null,
    *                           "last_updated_by": null,
    *                           "last_deleted_by": null,
    *                           "created_at": "2021-11-25T18:01:44.000000Z",
    *                           "updated_at": "2021-11-25T18:16:46.000000Z",
    *                           "name": "Sasuke  Uchiha",
    *                           "roles": {
    *                             {
    *                               "id": 2,
    *                               "slug": "rider",
    *                               "name": "rider",
    *                               "guard_name": "rider",
    *                               "deleted_at": null,
    *                               "created_at": "2021-11-25T18:01:43.000000Z",
    *                               "updated_at": "2021-11-25T18:01:43.000000Z",
    *                               "pivot": {
    *                                 "user_id": 2,
    *                                 "role_id": 2,
    *                                 "created_at": "2021-11-25T18:01:44.000000Z",
    *                                 "updated_at": "2021-11-25T18:01:44.000000Z"
    *                               }
    *                             },
    *                             {
    *                               "id": 3,
    *                               "slug": "customer",
    *                               "name": "customer",
    *                               "guard_name": "customer",
    *                               "deleted_at": null,
    *                               "created_at": "2021-11-25T18:01:43.000000Z",
    *                               "updated_at": "2021-11-25T18:01:43.000000Z",
    *                               "pivot": {
    *                                 "user_id": 2,
    *                                 "role_id": 3,
    *                                 "created_at": "2021-11-25T18:01:44.000000Z",
    *                                 "updated_at": "2021-11-25T18:01:44.000000Z"
    *                               }
    *                             }
    *                           },
    *                           "rider": {
    *                             "id": 1,
    *                             "user_id": 2,
    *                             "experience": 3,
    *                             "trained": "YES",
    *                             "status": "active",
    *                             "approved_at": "2021-11-25 18:01:44",
    *                             "deleted_at": null,
    *                             "last_deleted_by": null,
    *                             "last_updated_by": null,
    *                             "created_at": "2021-11-25T18:01:44.000000Z",
    *                             "updated_at": "2021-11-25T18:01:44.000000Z",
    *                             "vehicle": {
    *                               "id": 1,
    *                               "slug": "ba-12-pa-1234",
    *                               "rider_id": 1,
    *                               "vehicle_type_id": 1,
    *                               "vehicle_number": "BA 99 PA 1234",
    *                               "image": null,
    *                               "make_year": "2010",
    *                               "vehicle_color": null,
    *                               "brand": "HERO",
    *                               "model": "Splender",
    *                               "social_image_url":"https://images.unsplash.com/photo-1607335614551-3062bf90f30e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80",
    *                               "status": "active",
    *                               "deleted_at": null,
    *                               "last_deleted_by": null,
    *                               "last_updated_by": null,
    *                               "created_at": "2021-11-25T19:53:03.000000Z",
    *                               "updated_at": "2021-11-25T19:57:29.000000Z",
    *                               "thumbnail_path": "assets/media/noimage.png",
    *                               "image_path": "assets/media/noimage.png",
    *                               "documents": {}
    *                             },
    *                             "documents": {}
    *                           }
    *                         },
    *                         "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYjhkZTBmMWVkMmM2MDY0MzA3MWU0ZWI2ZjQ4MDQ5N2Q5ZjQ5OWUxNjdkODg2OGM0NDY4ZmJhZmZhZTc3Y2I1YWEwYmU2ZDA4NGI5MWU2MzYiLCJpYXQiOjE2Mzc5MDU5OTEuMTg5NDYzLCJuYmYiOjE2Mzc5MDU5OTEuMTg5NDY2LCJleHAiOjE2Njk0NDE5OTEuMTg0NzQxLCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.FmqOq7ZZ0llQKKrLCuDmhgWGo5FGgQBg0RW7H7aQ7Va0YjwhQ25b-FzHg3WyHH8n_eezgQak0YYidWm05vw4o2RrPUeEgG9jVdx7wwV7_UolALxp5OiMRJ6NTryRhMHLBTmTeXm7cYY3vHWvvugmlogeqJ4ZQY91q-OmwzSff58uUZAphiO1i9nETXtWoU7NmmLGqeHPePRcIEUcQqQynHu1EzOcZTdmLKfvH7jKyr_IiTq_TMS9100MNa0jgJnHED10_8MQro646ju-qk7LkGKSGj6b7nCmXZN7SGVn3fkMisE-2Y6MU9vQEdW4dum-q2UFndoAOnLAZLqxizZRK-YAPuLs6v60_ciV08BoBytTSgQMSo1ooWw1HCisbcgvNXttSQBGVMbgtCxPjS-pkvaPipiYsas4voOv6QZ7KtX6Y_yb5XOx1L4y6zsgTCLLYEk00YLYxk3_ILueaDl8VwzgBtLqXw2pFPqlOcCJ7QP0pv0LbJqpHzBpkvgsdpZ31PSVkEnfc4pZP5qRmEDvRYGZnc79QBGQ2BofGggaVJwJ07djFNPqLle6FMogjimDlkTFWgBtDB-TPyt8Dg6CqsGbRmKREnYtRoiUlHghwHwZD0yaW_cZbrvIRjKPDRiOFZycpWFPu_gWWMWC_9uNk6kDm1zsuOXZe83-n31Uf1w"
    *                       }
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
    *          response=403,
    *          description="Forbidden Access! You are already registered as a rider!",
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
    public function upgrade_to_rider(UserToRiderRequest $request)  //Authentication token required of user
    {
        
        $user = Auth::user();
        
        //ROLE CHECK FOR ALREADY RIDER
        if( $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access! You are already registered as a rider!'];
            return response($response, 403);
        }
    

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
                else if (isset($request->social_image_url) && !is_null($request->social_image_url)) {
                   
                    $url = $request->social_image_url;
                    $this->user->uploadSocialImage($createdRider->user, $url);

                } else {
                    //$fileNameToStore1 = 'no-image.png';
                }

                if ($request->hasFile('document.image')) {
                    $this->uploadFile($request, $createdRider->latest_document, $this->document);
                }
                if ($request->hasFile('vehicle.image')) {
                    $this->uploadFile($request, $createdRider->vehicle, $this->vehicle);
                }
                $rider = $user->rider;
                $accessToken = $createdRider->user->createToken('Laravel Password Grant Client')->accessToken;
              
                $response = ['message' => 'Rider registration success! Login Successful!', 'access_token' => $accessToken, "user"=>$createdRider->user,];
                return response($response, 200);
            }
            return response("Internal Server Error!", 500);
        });


    }

    /**
    * @OA\Post(
    *   path="/api/social/login",
    *   tags={"Register and Authentication"},
    *   summary="Login with google/facebook ID",
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "social_id": "amit.karn.982@facebook.com",
    *                   "social_type": "facebook",
    *                   "login_type": "customer"
    *               }
    *         )
    *     )
    *   ),
    *
    *   @OA\Response(
    *      response=404,
    *       description="Login Failed! User not Found!",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *       )
    *      ),
    *   @OA\Response(
    *      response=422,
    *       description="Validation Fail!",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *   ),
    *   @OA\Response(
    *      response=200,
    *       description="Login Successful!",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *   ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
    *              mediaType="application/json",
    *          )
    *      ),
    *)
    **/
    public function social_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'social_id' =>  ['required'],
            'social_type' => ['required','in:google,facebook'],
            'login_type' => ['required','in:customer,rider']
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        $user = null;

        if($request->social_type == "facebook")
        {
            $user = User::where('facebook_id',$request->social_id)->first();
        }
        else{
            $user = User::where('google_id',$request->social_id)->first();
        }

        if(!$user)
        {  
            $response = ['message' => 'Login Failed! User not Found!'];
            return response($response, 404);
        }
        

        if($request->login_type == "rider")
        {
            //ROLE CHECK FOR RIDER
            if( ! $this->user_service->hasRole($user, 'rider') )
            {
                $response = ['message' => 'Forbidden Access! You are already registered as a rider!'];
                return response($response, 403);
            }
            $rider = $user->rider;
            // dd($rider);
            $accessToken = $user->createToken('authToken')->accessToken;
            $response = ['message' => 'Rider exits and verified! Login Successful!','user'=>$user,  'access_token' => $accessToken];
            return response($response, 200);

        }
        else {
            $accessToken = $user->createToken('authToken')->accessToken;
            $response = ['message' => 'Login Successful!','user'=>$user,  'access_token' => $accessToken];
            return response($response, 200);
        }



        return response("Internal Server Error!", 500);
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
            $file = $request->file('image'); //dd('uploadFileAPICONTROLLER',$file, $request->image);
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
