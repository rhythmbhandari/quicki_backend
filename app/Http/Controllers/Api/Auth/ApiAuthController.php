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
    *                 "image": "file()",
    *                 "email": "luffy@gmail.com",
    *                 "phone": "9816810976",
    *                 "gender": "male",
    *                 "password": "password",
    *                 "password_confirmation": "password",
    *                 "dob": "2000/01/01",
    *                  "facebook_id" : "",
    *                  "google_id" : ""
    *              }
    *         )
    *     )
    *   ),
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                           "message":"User Registration Successfull!",
    *                           "token":"123sfsdr234sdfs",
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
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'required|string|min:10|unique:users',
            'dob' => 'nullable',
            'gender' => 'nullable',
            'google_id' => 'nullable|unique:users',
            'facebook_id' => 'nullable|unique:users'
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }
       // dd("USER DATA:",$request->all());
        return DB::transaction(function () use ($request)
        {
            $createdUser = $this->user->create($request->all());

            if($createdUser)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $createdUser, $this->user);
                }
                $token = $createdUser->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['message' => 'User Registration Successfull!', 'token' => $token, "user"=>$createdUser];
                return response($response, 200);
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
    *                           "message":"Rider Registration Successfull!",
    *                           "token":"123sfsdr234sdfs",
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
    public function rider_register(Request $request)
    {

       // dd("RIDER DATA:",$request->all());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'required|string|min:10|unique:users',
            'dob' => 'nullable',
            'gender' => 'nullable',
            'google_id' => 'nullable',
            'facebook_id' => 'nullable',

            //Rider's fields
            'rider.experience' => 'required',
            'rider.trained' => 'nullable',

            //Vehicle's fields
            'vehicle.vehicle_type_id' => 'required',
            'vehicle.vehicle_number' => 'required',
            'vehicle.make_year' => 'nullable',
            'vehicle.vehicle_color' => 'nullable',
            'vehicle.brand' => 'nullable',
            'vehicle.model' => 'nullable',

            //Document's fields
            'document.document_number' => 'required',
            'document.type' => 'required',
            'document.image' => 'required',
            'document.issue_date' => 'required',
            'document.expiry_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

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
            
                $token = $createdRider->user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['message' => 'Rider Registration Successfull!', 'token' => $token, "rider"=>$createdRider, "user"=>$createdRider->user,];
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
    *                  "user_role": "customer",
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
    *                           "message":"Otp sent successfully!",
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
            'phone' =>  ['required', function ($attribute, $value, $fail) {
                            $user = User::where('phone',$value)->first();

                            if ( !$user) {
                                $fail('The user does not exist for this phone number.');
                            }
                        },],

            'user_role' => ['nullable', function ($attribute, $value, $fail) {
                            if ( !(
                                $value == 'customer' || 
                                $value == 'rider' 
                            //  || $value == 'driver' 
                            //  || $value == 'admin' 
                            )) {
                                $fail('The '.$attribute.' can only be one of customer or rider.');
                            }
                        },],
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        $user = User::where('phone',$request->phone)->first();
        $role_check = $this->user->hasRole($user, $request->user_role);

        if(!$role_check)
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
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
    *                           "message":"User exits and verified!",
    *                           "token":"abxad5aSDsdfsdfs",
    *                           "user":"{user}",
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
    *             description="Forbidden Access!"
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
    *         description="No Record found!"
    *      ),
    *)
    **/
    public function verify_user_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' =>  ['required', function ($attribute, $value, $fail) {
                $user = User::where('phone',$value)->first();

                if ( !$user) {
                    $fail('The user does not exist for this phone number.');
                }
            },],
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        $phone = $request->phone;
        $otp = Otp::where('phone', $phone)->first();

        if ($request->code == $otp->code) {
            $otp->update(['code_status' => 'verified']);
            $user = User::where('phone', '=', $otp->phone)->first();
            if ($user) {
                $accessToken = $user->createToken('authToken')->accessToken;
                $response = ['message' => 'User exits and verified!', 'data' => $user, 'access_token' => $accessToken];
                return response($response, 200);
            } else {
                $response = ['message' => 'Unauthorized: User does not Exist!'];
                return response($response, 401);
            }
        } else {
            $response = ['message' => 'No Record found'];
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
    *                   "otp": "223305",
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
    *                           "message":"Rider exits and verified!",
    *                           "token":"abxad5aSDsdfsdfs",
    *                           "Rider":"{rider}",
    *                   }
    *                 )
    *           )
    *      ),
    *       @OA\Response(
    *             response=401,
    *             description="Unauthorized: Rider does not Exist!"
    *         ),
    *         @OA\Response(
    *             response=403,
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
    *         description="No Record found!"
    *      ),
    *)
    **/
    public function verify_rider_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' =>  ['required', function ($attribute, $value, $fail) {
                $user = User::where('phone',$value)->first();

                if ( !$user) {
                    $fail('The user does not exist for this phone number.');
                }
            },],
            'code' => 'required',
        ]);

        $phone = $request->phone;
        $otp = Otp::where('phone', $phone)->first();

        if ($request->code == $otp->code) {
            $otp->update(['code_status' => 'verified']);
            $user = User::where('phone', '=', $otp->phone)->first();
            if ($user) {
                if( $this->user->hasRole($user, 'rider'))
                {
                    $rider = $user->rider;
                    $accessToken = $user->createToken('authToken')->accessToken;
                    $response = ['message' => 'Rider exits and verified!','user'=>$user, 'rider' => $rider, 'access_token' => $accessToken];
                    return response($response, 200);
                }
                $accessToken = $user->createToken('User Token!')->accessToken;
                $response = ['message' => 'Forbidden Access: User exists but is not registered as a rider!','token'=>$accessToken,'user'=>$user];
                return response($response, 403);
            } else {
                $response = ['message' => 'Unauthorized: Rider does not Exist!'];
                return response($response, 401);
            }
        } else {
            $response = ['message' => 'No Record found'];
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
    *                           "message":"Rider Registration Successfull!",
    *                           "token":"123sfsdr234sdfs",
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
    public function upgrade_to_rider(Request $request)  //Authentication token required of user
    {
        $user = null;
        try{
            $user = Auth::user();
        }
        catch(Exception $e)
        {
            $response = ['message' => 'Unauthorized: User does not Exist!'];
            return response($response, 401);
        }
        if(!$user)
        {
            
            $response = ['message' => 'Unauthorized: User does not Exist!'];
            return response($response, 401);
        }


        $validator = Validator::make($request->all(), [
            //Rider's fields
            'rider.experience' => 'required',
            'rider.trained' => 'nullable',

            //Vehicle's fields
            'vehicle.vehicle_type_id' => 'required',
            'vehicle.vehicle_number' => 'required',
            'vehicle.make_year' => 'nullable',
            'vehicle.vehicle_color' => 'nullable',
            'vehicle.brand' => 'nullable',
            'vehicle.model' => 'nullable',

            //Document's fields
            'document.document_number' => 'required',
            'document.type' => 'required',
            'document.image' => 'required',
            'document.issue_date' => 'required',
            'document.expiry_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
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
                if ($request->hasFile('document.image')) {
                    $this->uploadFile($request, $createdRider->latest_document, $this->document);
                }
                if ($request->hasFile('vehicle.image')) {
                    $this->uploadFile($request, $createdRider->vehicle, $this->vehicle);
                }
            
                $token = $createdRider->user->createToken('Laravel Password Grant Client')->accessToken;
              
                $response = ['message' => 'Rider Registration Successfull!', 'token' => $token, "rider"=>$createdRider, "user"=>$createdRider->user,];
                return response($response, 200);
            }
            return response("Internal Server Error!", 500);
        });


    }







    // function uploadFile(Request $request, $user)
    // {
    //     $file = $request->file('image');
    //     $fileName = $this->user->uploadFile($file);
    //     if (!empty($user->image))
    //         $this->user->__deleteImages($user);

    //     $data['image'] = $fileName;
    //     $this->user->updateImage($user->id, $data);
    // }
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
       
       
       /* if($model=='user')
        {
            $file = $request->file('image');
            $fileName = $this->user->uploadFile($file);
            if (!empty($user->image))
                $this->user->__deleteImages($user);
    
            $data['image'] = $fileName;
            $this->user->updateImage($user->id, $data);
        }
        else if($model == 'rider')
        {
            $file = $request->file('image');
            $fileName = $this->user->uploadFile($file);
            if (!empty($user->image))
                $this->user->__deleteImages($user);
    
            $data['image'] = $fileName;
            $this->user->updateImage($user->id, $data);
        }
        else if($model == 'vehicle_type')
        {
            $file = $request->file('image');
            $fileName = $this->vehicle_type->uploadFile($file);
            if (!empty($vehicle_type->image))
                $this->vehicle_type->__deleteImages($vehicle_type);
    
            $data['image'] = $fileName;
            $this->vehicle_type->updateImage($vehicle_type->id, $data);
        }
        else if($model == 'vehicle')
        {
            $file = $request->file('image');
            $fileName = $this->vehicle->uploadFile($file);
            if (!empty($vehicle->image))
                $this->vehicle->__deleteImages($vehicle);
    
            $data['image'] = $fileName;
            $this->vehicle->updateImage($vehicle->id, $data);
        }
        else if($model == 'document')
        {
            $file = $request->file('image');
            $fileName = $this->user->uploadFile($file);
            if (!empty($user->image))
                $this->user->__deleteImages($user);
    
            $data['image'] = $fileName;
            $this->user->updateImage($user->id, $data);
        }
        else{} */
      
       
    }










    function test(Request $request)
    {
        //dd($request->all());
        $user = User::find($request->id);
        if($user)
        {
            dd($this->user->hasRole($user, $request->role));
        }
        
    }

}
