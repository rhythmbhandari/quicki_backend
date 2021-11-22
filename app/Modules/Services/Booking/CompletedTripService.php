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
