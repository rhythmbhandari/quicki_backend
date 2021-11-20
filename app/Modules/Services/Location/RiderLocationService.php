<?php

namespace App\Modules\Services\Location;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\RiderLocation;

class RiderLocationService extends Service
{
    protected $rider_location;

    function __construct(RiderLocation $rider_location)
    {
        $this->rider_location = $rider_location;
    }

    function getRiderLocation(){
        return $this->rider_location;
    }
    
    function getAvailableRiders()
    {
        $rider_locations = RiderLocation::where('status','active')->get();
        $available_riders = [];
        foreach($rider_locations as $rider_location)
        {
            if($rider_location->availability == "available")
            {
                
                $available_riders[] = $rider_location->rider;
            }
        }
        return $available_riders;
    }

    function getNearbyAvailableRiders($latLng)
    {
        $rider_locations = RiderLocation::where('status','active')->get();
        $available_riders = [];
        foreach($rider_locations as $rider_location)
        {
            //Check if the rider's is within the certain range of given (customer) location 

            if($rider_location->availability == "available")
            {
                
                $available_riders[] = $rider_location->rider;
            }
        }
        return $available_riders;
    }

    function create($data)
    {
        try {
            $data['status'] = isset($data['status'])?$data['status'] : 'active';
            $createdRiderLocation = $this->rider_location->create($data);
            if($createdRiderLocation)
                return $createdRiderLocation;
        }
        catch(Exception $e){
            return NULL;
        }
        return NULL;
    }

    function update($riderLocationId, $data)
    {
        try {
        
            $data['status'] = isset($data['status'])?$data['status'] : 'active';
            $rider_location= RiderLocation::findOrFail($riderLocationId);
            $updatedRiderLocation = $rider_location->update($data);
            return $updatedRiderLocation;

        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return null;
        }
    }


}
