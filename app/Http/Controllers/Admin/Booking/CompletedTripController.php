<?php

namespace App\Http\Controllers\Admin\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//services
use App\Modules\Services\Booking\CompletedTripService;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\User;
use App\Modules\Models\Rider;
use App\Modules\Models\CompletedTrip;


class CompletedTripController extends Controller
{
    
    protected $completed_trip, $user_service;

    public function __construct(CompletedTripService $completed_trip, UserService $user_service)
    {
        $this->completed_trip = $completed_trip;
        $this->user_service = $user_service;
    }

    public function getUserTrips()
    {
        $user = Auth::user();

        //ROLE CHECK FOR CUSTOMER
        if( ! $this->user_service->hasRole($user, 'customer') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
          

        //Fetch booking history/ completed trips
        $completed_trips = CompletedTrip::where('user_id',$user->id)->with('location')->with('rider')->get();

        $response = ['message' => 'Success!',  "completed_trips"=>$completed_trips];
        return response($response, 200);

    }

    public function getRiderTrips()
    {
        $user = Auth::user();

        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
          

        //Fetch booking history/ completed trips
        $completed_trips = CompletedTrip::where('user_id',$user->id)->with('location')->with('user')->get();

        $response = ['message' => 'Success!',  "completed_trips"=>$completed_trips];
        return response($response, 200);

    }

}
