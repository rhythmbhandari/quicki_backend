<?php

namespace App\Modules\Services\Booking;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\Auth;


//services
use App\Modules\Services\Location\LocationService;
use App\Modules\Services\Booking\CompletedTripService;

//models
use App\Modules\Models\Booking;
use App\Modules\Models\CompletedTrip;

class BookingService extends Service
{
    protected $booking, $location_service;

    function __construct(Booking $booking, LocationService $location_service, CompletedTripService $completed_trip_service)
    {
        $this->booking = $booking;
        $this->location_service = $location_service;
        $this->completed_trip_service = $completed_trip_service;
    }

    function getBooking(){
        return $this->booking;
    }


    function create($userId, array $data)
    {
        

        try{
            $data['user_id'] = $userId;
            //default status = pending
            
            $data['status'] = isset($data['status'])?$data['status']:'pending';
            $createdBooking = $this->booking->create($data);
            if($createdBooking)
            {
                //CREATE LOCATION
                $createdLocation = $this->location_service->create($data['location']);
                if($createdLocation)
                {   
                    $createdBooking->location_id = $createdLocation->id;
                    $createdBooking->save();
                    $createdBooking->location = $createdLocation;
                    return $createdBooking;
                }
            }
            return NULL;
        }
        catch(Exception $e)
        {
            return NULL;
        }

    }


    function update_status(array $data)
    {
       // dd("DATA: ",$data['optional_data']);
        try{
            $booking_id = $data['booking_id'];
            $new_status = $data['new_status'];

            $booking = Booking::findOrFail($booking_id);
            $booking->status = $new_status;

            if($booking->save())
            {    
                if($new_status == "accepted")
                {
                    $booking->rider_id = $data['optional_data']['rider_id'];
                    if($booking->save())
                        return $booking;
                }
                else if($new_status == "running")
                {
                    return $booking;
                }
                else if($new_status == "completed")
                {
                    //CREATE COMPLTED TRIP RECORD for COMPLETED STATUS
                    $cancelled_trip_data = $booking->toArray();
                    $cancelled_trip_data['booking_id'] = $booking->id;
                    // $data['profile_img_rider'] = "";
                    // $data['profile_img_user'] = "";
                    $booking->createdCompletedTrip = $this->completed_trip_service->create($cancelled_trip_data);
                    return $booking;
                }
                else if($new_status == "cancelled")
                {
                    //CREATE COMPLTED TRIP RECORD for CANCELLED STATUS
                    $cancelled_trip_data = $booking->toArray();
                    $cancelled_trip_data['booking_id'] = $booking->id;
                   // $data['profile_img_rider'] = "";
                   // $data['profile_img_user'] = "";
                   
                    $cancelled_trip_data['cancelled_by_id'] = 
                    isset($data['optional_data']['cancelled_by_id']) ? $data['optional_data']['cancelled_by_id'] : Auth::user()->id ;
                    $cancelled_trip_data['cancelled_by_type'] =
                    isset($data['optional_data']['cancelled_by_type']) ? $data['optional_data']['cancelled_by_type'] : "customer";
                    $cancelled_trip_data['cancel_message'] = 
                    isset($data['optional_data']['cancel_message']) ? $data['optional_data']['cancel_message'] : "" ;

                    $booking->createdCompletedTrip = $this->completed_trip_service->create($cancelled_trip_data);
                    return $booking;
                }
            }
            return NULL;
        }
        catch(Exception $e)
        {
            return NULL;
        }
    }



    public function active_user_booking($userId)
    {
        try{
            $booking = $this->booking->where('user_id',$userId)->where(function($query){
                $query->where('status','pending')
                ->orWhere('status','accepted')
                ->orWhere('status','running');
            })->first();
            return $booking;
        }
        catch(Exception $e)
        {
            return NULL;
        }
    }


    public function active_rider_booking($riderId)
    {
        try{
            $booking = $this->booking->where('rider_id',$riderId)->where(function($query){
                $query->where('status','accepted')
                ->orWhere('status','running');
            })->first();
            return $booking;
        }
        catch(Exception $e)
        {
            return NULL;
        }
    }
    
}
