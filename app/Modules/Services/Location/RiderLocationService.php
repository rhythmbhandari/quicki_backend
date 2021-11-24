<?php

namespace App\Modules\Services\Location;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\RiderLocation;

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

    /**
     * Fetches the rider locations with riders within the radius circle of certain origin latitude and longitude
     */
    function getNearbyAvailableRiders($origin_lat, $origin_lng, $vehicle_type_id = null,  $radius = null)
    {
        try{
            try{
                if($radius==null)
                {
                    $radius = !empty( config('settings.scan_radius') ) ? floatval( config('settings.scan_radius') ) : 5.0;
                }
                else{
                    $radius = floatval($radius);
                }
            }
            catch(Exception $e)
            {
                $radius = 5.0;
            }
            $rider_locations = [];  
            if( $vehicle_type_id != null)
            {   //dd($vehicle_type_id);
               // $rider_locations = RiderLocation::where('status','active')->get();
               $rider_locations = $this->rider_service->getAllowedRidersQuery()
                                   // ->whereRelation('rider_location','status','!=','in_active')
                                    //->whereRelation('vehicle','vehicle_type_id',$vehicle_type_id)
                                    //->with('rider_location')
                                    ->get();
              //  dd("rider: ",$rider_locations);

            }
            else 
            {
               // $rider_locations = RiderLocation::whereRelation('vehicle','vehicle_type_id',$vehicle_type_id)->where('status','active')->get();
                //$rider_locations = $this->rider_service->getAllowedRidersQuery->whereRelation('rider_location','status','!=','in_active')->with('rider_location')->get();
                $rider_locations = $this->rider_service->getAllowedRidersQuery()
                                    ->whereRelation('rider_location','status','!=','in_active')
                                    ->with('rider_location')
                                    ->get();
            }
               
           // dd($rider_locations);
            
            $nearby_available_riders = [];
            foreach($rider_locations as $rider_location)
            {
                if($rider_location->availability == "available")
                {
                    $distance_from_origin = calcuateDistance( 
                        floatval($origin_lat), 
                        floatval($origin_lng), 
                        floatval($rider_location->latitude), 
                        floatval($rider_location->longitude)
                    );
                   // $str = "DISTANCE: ".$distance_from_origin.", ORIGINLAT: ".$origin_lat.", ORIGINLNG: ". $origin_lng.", RLAT: ".$rider_location->latitude.", RLNG: ".$rider_location->longitude.", RADIUS: ".$radius;
                   /// dd($str );
                    
                    if( $distance_from_origin <= $radius )
                    {
                        $rider_location->rider = $rider_location->rider;
                        $nearby_available_riders[] = $rider_location;//->rider;
                    }
                }
            }
            return $nearby_available_riders;
        }
        catch(Exception $e)
        {
            return NULL;
        }
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
