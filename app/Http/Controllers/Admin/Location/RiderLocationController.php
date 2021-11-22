<?php

namespace App\Http\Controllers\Admin\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//services
use App\Modules\Services\Location\RiderLocationService;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\Rider;
use App\Modules\Models\RiderLocation;

class RiderLocationController extends Controller
{
    
    protected $rider_location, $user_service;

    public function __construct(RiderLocationService $rider_location, UserService $user_service)
    {
        $this->rider_location = $rider_location;
        $this->user_service = $user_service;
    }

        
    function getRiderOnline(Request $request)
    {
        $user = Auth::user();
        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
        

        //VALIDATIONS
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        //CREATE or UPDATE RIDER LOCATION
        $rider_location = RiderLocation::where('rider_id',$user->rider->id)->first();
        $data = $request->all();
        if($rider_location)
        {
            //UPDATE RIDER LOCATION
            $data['status'] = 'active';
            $updatedRiderLocation = $this->rider_location->update($rider_location->id, $data);
            $response = ['message' => 'Rider got Online Successfully!',  "updatedRiderLocation"=>RiderLocation::findOrFail($rider_location->id)];
            return response($response, 200);
        }
        else{
            //CREATE NEW RIDER LOCATION
            $data['status'] = 'active';
            $data['rider_id'] = $user->rider->id;
            $createdRiderLocation = $this->rider_location->create($data);
            $response = ['message' => 'Rider got Online Successfully for the first time!',  "createdRiderLocation"=>$createdRiderLocation];
            return response($response, 201);
        }
        
        return response("Internal Server Error!", 500);

    }

    function getRiderOffline(Request $request)
    {
        $user = Auth::user();
        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
        //VALIDATIONS
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        //VALIDATIONS
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        //CREATE or UPDATE RIDER LOCATION
        $rider_location = RiderLocation::where('rider_id',$user->rider->id)->first();
        $data = $request->all();
        if($rider_location)
        {
            //UPDATE RIDER LOCATION
            $data['status'] = 'in_active';
            $updatedRiderLocation = $this->rider_location->update($rider_location->id, $data);
            $response = ['message' => 'Rider got Offline Successfully!',  "updatedRiderLocation"=>RiderLocation::findOrFail($rider_location->id)];
            return response($response, 200);
        }
        else{
            //CREATE NEW RIDER LOCATION
            $data['status'] = 'in_active';
            $data['rider_id'] = $user->rider->id;
            $createdRiderLocation = $this->rider_location->create($data);
            $response = ['message' => 'Rider got Offline Successfully for the first time!',  "createdRiderLocation"=>$createdRiderLocation];
            return response($response, 201);
        }
        
        return response("Internal Server Error!", 500);


    }


    function getAvailableRiders()
    {
        $available_riders = $this->rider_location->getAvailableRiders();
        $response = ['message' => 'Success!',  "available_riders"=>$available_riders];
        return response($response, 200);

    }

}
