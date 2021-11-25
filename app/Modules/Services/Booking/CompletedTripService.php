<?php

namespace App\Modules\Services\Booking;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\CompletedTrip;

class CompletedTripService extends Service
{
   
    protected $completed_trip;

    function __construct(CompletedTrip $completed_trip)
    {
        $this->completed_trip = $completed_trip;
    }

    function getCompletedTrip(){
        return $this->completed_trip;
    }


    function create(array $data)
    {
        try{
            $data['user_id'] = intval($data['user_id']);
            $data['rider_id'] = ( isset($data['rider_id']) && !empty($data['rider_id']) ) ? intval($data['rider_id']) : null  ;
            $data['booking_id'] = intval($data['booking_id']);
            $data['location_id'] = intval($data['user_id']);
            $data['passenger_number'] =   ( isset($data['passenger_number']) && !empty($data['passenger_number']) ) ?  intval($data['passenger_number']) : null;
            $data['distance'] = intval($data['distance']);
            $data['duration'] = intval($data['duration']);
            $data['cancelled_by_id'] =  ( isset($data['cancelled_by_id']) && !empty($data['cancelled_by_id']) ) ? intval($data['cancelled_by_id']) : null;
            $createdCompltedTrip =  $this->completed_trip->create($data);
            if($createdCompltedTrip)
                return $createdCompltedTrip;
            return NULL;
        }
        catch(Exception $e)
        {
            return NULL;
        }
    }
    
}
