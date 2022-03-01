<?php

namespace App\Modules\Services\Location;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\RiderLocation;
use App\Modules\Models\Booking;

//services
use App\Modules\Services\User\RiderService;
class RiderLocationService extends Service
{
    protected $rider_location;

    function __construct(RiderLocation $rider_location, RiderService $rider_service)
    {
        $this->rider_location = $rider_location;
        $this->rider_service = $rider_service;
    }

    function getRiderLocation(){
        return $this->rider_location;
    }
    
    function getAllAvailableRiders()
    {
        $rider_locations = RiderLocation::where('status','active')->get();
        return $rider_locations;
        /*
        $available_riders = [];
        foreach($rider_locations as $rider_location)
        {
            if($rider_location->availability == "available")
            {
                
                $available_riders[] = $rider_location->rider;
            }
        }
        return $available_riders;
        */
    }

    /**
     * Fetches the rider locations with riders within the radius circle of certain origin latitude and longitude
     */
    function getNearbyAvailableRiders($origin_lat, $origin_lng, $vehicle_type_id = null,  $radius = null)
    {
     
        
        $rider_locations = null;

        if($vehicle_type_id)
        {
            $rider_locations = RiderLocation::whereStatus('active')
            ->whereRelation('rider','status','!=','in_active')
            ->whereRelation('rider','approved_at','!=',NULL)
            ->whereRelation('rider.vehicle', 'vehicle_type_id', $vehicle_type_id)->get();
        }
        else{
            $rider_locations = RiderLocation::whereStatus('active')
            ->whereRelation('rider','status','!=','in_active')
            ->whereRelation('rider','approved_at','!=',NULL)
            ->get();
        }


        try{
            if($radius==null)
            {
                $radius = !empty( config('settings.scan_radius') ) ? floatval( config('settings.scan_radius') ) : 20.0; //20km
            }
            else{
                $radius = floatval($radius);
            }
        }
        catch(Exception $e)
        {
            $radius = 20.0;
        }

        $nearby_available_riders = [];
        foreach($rider_locations as $rider_location)
        {
            // if($rider_location->availability == "available")
            // {
                $distance_from_origin = calcuateDistance( 
                    floatval($origin_lat), 
                    floatval($origin_lng), 
                    floatval($rider_location->latitude), 
                    floatval($rider_location->longitude)
                );
           
                
                if( $distance_from_origin <= $radius )
                {
                    // $rider_location = $rider_location->toArray();//->rider->user;
                    $nearby_available_riders[] = $rider_location->toArray();//->rider;
                }
            // }
        }
        return $nearby_available_riders;


    }




     /**
     * Fetches the pending bookings  within the radius circle of certain origin latitude and longitude from the riders
     */
    function getNearbyAvailableUsers($origin_lat, $origin_lng, $vehicle_type_id = null,  $radius = null)
    {
     
        
        $pending_bookings = null;

        if($vehicle_type_id)
        {
            $pending_bookings = Booking::whereStatus('pending')->where('vehicle_type_id',$vehicle_type_id)->get();
        }
        else{
            $pending_bookings = Booking::whereStatus('pending')->get();
        }


        try{
            if($radius==null)
            {
                $radius = !empty( config('settings.scan_radius') ) ? floatval( config('settings.scan_radius') ) : 20.0; //20km
            }
            else{
                $radius = floatval($radius);
            }
        }
        catch(Exception $e)
        {
            $radius = 20.0;
        }

        $nearby_pending_bookings = [];
        foreach($pending_bookings as $booking)
        {
            // if($rider_location->availability == "available")
            // {
                $distance_from_origin = calcuateDistance( 
                    floatval($origin_lat), 
                    floatval($origin_lng), 
                    // floatval($booking->location->latitude_origin), 
                    // floatval($booking->location->longitude_origin)
                    floatval($booking->location['origin']['latitude']), 
                    floatval($booking->location['origin']['longitude'])
                );
           
                
                if( $distance_from_origin <= $radius )
                {
                    //$booking = $booking->toArray();//->rider->user;
                    $nearby_pending_bookings[] = $booking->toArray();//->rider;
                }
            // }
        }
        return $nearby_pending_bookings;


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
            //dd($updatedRiderLocation);
            return $updatedRiderLocation;

        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return null;
        }
    }


}
