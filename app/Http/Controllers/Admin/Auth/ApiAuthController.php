<?php

namespace App\Http\Controllers\Admin\Auth;

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

   
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
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
                $response = ['message' => 'User Registration Successful!', 'token' => $token, "user"=>$createdUser];
                return response($response, 201);
            }
            return response("Internal Server Error!", 500);
        });

      
    }




     
    public function rider_register(Request $request)
    {

       // dd("RIDER DATA:",$request->all());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
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
                $response = ['message' => 'Rider Registration Successful!', 'token' => $token, "rider"=>$createdRider, "user"=>$createdRider->user,];
                return response($response, 200);
            }
            return response("Internal Server Error!", 500);
        });

       
      
    }


        
    public function send_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' =>  ['required'],

            // 'user_role' => ['nullable', function ($attribute, $value, $fail) {
            //                 if ( !(
            //                     $value == 'customer' || 
            //                     $value == 'rider' 
            //                 //  || $value == 'driver' 
            //                 //  || $value == 'admin' 
            //                 )) {
            //                     $fail('The '.$attribute.' can only be one of customer or rider.');
            //                 }
            //             },],
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        // $user = User::where('phone',$request->phone)->first();
        // $role_check = $this->user->hasRole($user, $request->user_role);

        // if(!$role_check)
        // {
        //     $response = ['message' => 'Forbidden Access!'];
        //     return response($response, 403);
        // }

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

        if ($request->code == $otp->code) {
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
            $response = ['message' => 'No Record found'];
            return response($response, 404);
        }
    }


    
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

        if ($request->code == $otp->code) {
            $otp->update(['code_status' => 'verified']);
            $user = User::where('phone', '=', $otp->phone)->first();
            if ($user) {
                if( $this->user->hasRole($user, 'rider'))
                {
                    $rider = $user->rider;
                    $accessToken = $user->createToken('authToken')->accessToken;
                    $response = ['message' => 'Rider exits and verified! Login Successful!','user'=>$user, 'rider' => $rider, 'access_token' => $accessToken];
                    return response($response, 200);
                }
                $accessToken = $user->createToken('User Token!')->accessToken;
                $response = ['message' => 'Forbidden Access: User exists but is not registered as a rider!','token'=>$accessToken,'user'=>$user];
                return response($response, 403);
            } else {
                $response = ['message' => 'Unauthorized: Otp verified but rider does not Exist!'];
                return response($response, 401);
            }
        } else {
            $response = ['message' => 'No Record found'];
            return response($response, 404);
        }
    }



       


    public function upgrade_to_rider(Request $request)  //Authentication token required of user
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
              
                $response = ['message' => 'Rider Registration Successful!', 'token' => $token, "rider"=>$createdRider, "user"=>$createdRider->user,];
                return response($response, 200);
            }
            return response("Internal Server Error!", 500);
        });


    }



    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
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
