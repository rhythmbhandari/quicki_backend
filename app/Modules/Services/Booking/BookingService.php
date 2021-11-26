<?php

namespace App\Modules\Services\Booking;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;


//services
use App\Modules\Services\Location\LocationService;
use App\Modules\Services\Location\RiderLocationService;
use App\Modules\Services\Booking\CompletedTripService;

//models
use App\Modules\Models\Booking;
use App\Modules\Models\CompletedTrip;
use App\Modules\Models\VehicleType;
use App\Modules\Models\Shift;

class BookingService extends Service
{
    protected $booking, $location_service;

    function __construct(Booking $booking, LocationService $location_service, CompletedTripService $completed_trip_service, RiderLocationService $rider_location_service)
    {
        $this->booking = $booking;
        $this->location_service = $location_service;
        $this->completed_trip_service = $completed_trip_service;
        $this->rider_location_service = $rider_location_service;
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

            //Parse the possible string values of latitudes and longitudes to double/float
            $data['location']['latitude_origin'] = floatval( $data['location']['latitude_origin'] );
            $data['location']['longitude_origin'] = floatval( $data['location']['longitude_origin'] );
            $data['location']['latitude_destination'] = floatval( $data['location']['latitude_destination'] );
            $data['location']['longitude_destination'] = floatval( $data['location']['longitude_destination'] );

            if(isset($data['stoppage']) && count($data['stoppage'])>0 )
            {
                for($i=0; $i < count($data['stoppage']); $i++ )
                {
                    $data['stoppage'][$i]['latitude'] = floatval( $data['stoppage'][$i]['latitude'] );
                }
            }

            //parse distance and duration to integer
            $data['distance'] = intval( $data['distance'] );
            $data['duration'] = intval( $data['duration'] );
            $data['price'] = intval( $data['price'] );
           // $data['location_id'] =intval( $data['location_id'] );
            $data['user_id'] =intval( $data['user_id'] );
            $data['rider_id'] = isset($data['rider_id']) ? intval( $data['rider_id'] ) : null  ;

           // dd("booking data", $data);


            $createdBooking = $this->booking->create($data);

            if($createdBooking)
            {
                //CREATE LOCATION
                $createdLocation = $this->location_service->create($data['location']);
                if($createdLocation)
                {   
                    $createdBooking->location_id = intval($createdLocation->id);
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
            $booking_id = intval($data['booking_id']);
            $new_status = $data['new_status'];

            $booking = Booking::findOrFail($booking_id);
            $booking->status = $new_status;

            if($booking->save())
            {    
                if($new_status == "accepted")
                {
                    $booking->rider_id = intval($data['optional_data']['rider_id']);
                    if($booking->save())
                        return $booking;
                }
                else if($new_status == "running")
                {
                    $booking->start_time = Carbon::now();
                    $booking->save();
                    return $booking;
                }
                else if($new_status == "completed")
                {
                    if(!$booking->start_time)
                    {
                        $booking->start_time = Carbon::now();
                        $booking->end_time = Carbon::now();
                    }
                    else {
                        $booking->end_time = Carbon::now();
                    }
                    $booking->save();

                    //CREATE COMPLTED TRIP RECORD for COMPLETED STATUS
                    $cancelled_trip_data = $booking->toArray();
                    $cancelled_trip_data['booking_id'] = intval($booking->id);
                    
                    $booking->createdCompletedTrip = $this->completed_trip_service->create($cancelled_trip_data);
                    return $booking;
                }
                else if($new_status == "cancelled")
                {
                  
                    if(!$booking->start_time)
                    {
                        $booking->start_time = Carbon::now();
                        $booking->end_time = Carbon::now();
                    }
                    else {
                        $booking->end_time = Carbon::now();
                    }
                    $booking->save();

                    //CREATE COMPLTED TRIP RECORD for CANCELLED STATUS
                    $cancelled_trip_data = $booking->toArray();
                    $cancelled_trip_data['booking_id'] = intval($booking->id);
                    

                    // $cancelled_trip_data['rider_id'] = isset($cancelled_trip_data['rider_id']) ? intval($cancelled_trip_data['rider_id']) : null;
                    // $cancelled_trip_data['location_id'] = isset($cancelled_trip_data['location_id']) ? intval($cancelled_trip_data['location_id']) : null;
                    // $cancelled_trip_data['price'] = isset($cancelled_trip_data['price']) ? intval($cancelled_trip_data['price']) : null;
                    // $cancelled_trip_data['distance'] = isset($cancelled_trip_data['distance']) ? intval($cancelled_trip_data['distance']) : null;
                    // $cancelled_trip_data['duration'] = isset($cancelled_trip_data['duration']) ? intval($cancelled_trip_data['duration']) : null;
                   
                    // $cancelled_trip_data['cancelled_by_id'] = 
                    // isset($data['optional_data']['cancelled_by_id']) ? intval($data['optional_data']['cancelled_by_id']) : intval(Auth::user()->id) ;
                    // $cancelled_trip_data['cancelled_by_type'] =
                    // isset($data['optional_data']['cancelled_by_type']) ? $data['optional_data']['cancelled_by_type'] : "customer";
                    // $cancelled_trip_data['cancel_message'] = 
                    // isset($data['optional_data']['cancel_message']) ? $data['optional_data']['cancel_message'] : "" ;

                    
                    // dd("cancelled",$cancelled_trip_data);

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
            })->with('location')->first();
            return $booking;
        }
        catch(Exception $e)
        {
            return null;
        }
    }


    public function active_rider_booking($riderId)
    {
        try{
            $booking = $this->booking->where('rider_id',$riderId)->where(function($query){
                $query->where('status','accepted')
                ->orWhere('status','running');
            })->with('location')->first();
            return $booking;
        }
        catch(Exception $e)
        {
            return null;
        }
    }
    

    //Takes distance in km and duration in seconds
    public function get_estimated_price(array $data)
    {   
        
        $estimated_prices = []; //estimated price objects for every vehicle types
        
        //DEDUCING SHIFT SURGE
        $vehicle_types = VehicleType::where('status','active')->get();
        foreach($vehicle_types as $vehicle_type)
        {
            $estimated_prices[] = $this->calculateEstimatedPrice($data['origin_latitude'], $data['origin_longitude'], $vehicle_type->id, $data['distance'], $data['duration']);  
        }

        return $estimated_prices;

    }


    

    public function calculateEstimatedPrice($origin_latitude, $origin_longitude, $vehicle_type_id, $distance, $duration)
    {
        $distance = $distance/1000 ;        //convert the distance in meters to kilometers

        $estimated_price = [];
        $vehicle_type = VehicleType::find($vehicle_type_id);
       
        /*****REQUIRED DATA FOR CALCULATION*****
        *-ORIGIN_LATITUDE
        *-ORIGIN_LONGITUDE
        *-DISTANCE
        *-DURATION
        */


        //TO BE CALCULATED
        $density_surge = 0;
        $total_surge = 0;
        $shift_surge = 0;
        
        //DEDUCTING DENSITY SURGE
        //1 rider for 3 pending bookings ; for >1:4, the surge applies::TO BE FETCHED from DB SETTINGS 
        $permissable_density = 1/3; 
        //The density surge applies only if the current pending bookings is >= this value::TO BE FETCHED from DB SETTINGS 
        $threshold_pending_booking = 3; 
        $nearbyRiders = $this->get_available_riders_within_radius($origin_latitude, $origin_longitude,$vehicle_type->id);
        $nearbyRiders = ($nearbyRiders > 0) ? $nearbyRiders : 1;
        $nearbyPendingBookings = $this->get_nearby_pending_bookings($origin_latitude, $origin_longitude,$vehicle_type->id);
        $nearbyPendingBookings = ($nearbyPendingBookings > 0) ? $nearbyPendingBookings : 1;
        
        $current_density = 1;
        if(!$nearbyRiders || !$nearbyRiders) 
            $current_density = 1;
        else
            $current_density = count($nearbyRiders)/count($nearbyPendingBookings); 

        if( ($current_density < $permissable_density ) &&  ($current_pending_booking >= $threshold_pending_booking  )  )
            $density_surge = 30;    //CONSTANT TO BE FETCHED from DB SETTINGS 
        
        //DEDUCTING SHIFT SURGE
        $currentTime = Carbon::now();
        // $shift = 
        $shifts = Shift::where('vehicle_type_id',$vehicle_type_id)->where('status','active')->get();
    
        $shift = $shifts->filter(function($shift) {
                    $startTime = Carbon::createFromFormat('H', $shift->time_from);
                    $endTime = Carbon::createFromFormat('H',  $shift->time_to);
                    if($currentTime->between($startTime, $endTime, true))   return true;
                    else return false;
                }); 
        
        $shift_rate = isset($shift->rate)?$shift->rate:1;
       

        $estimated_price['vehicle_type_id'] = $vehicle_type->id;
        $estimated_price['vehicle_type_name'] = $vehicle_type->name;
        $estimated_price['shift'] = isset($shift->title)?$shift->title:1;
        $estimated_price['price_breakdown'] = [];
        //Provided
        $estimated_price['price_breakdown']['minimum_charge'] = 150;
        //PRICE AFTER DISTANCE
        $estimated_price['price_breakdown']['price_per_km'] = $vehicle_type->price_per_km;
        $estimated_price['price_breakdown']['price_after_distance']  = ($vehicle_type->price_per_km * $distance);
        
        //PRICE AFTER SURGE
        $estimated_price['price_breakdown']['shift_rate'] = $shift_rate;    //Default::TO BE FETCHED from DB SHIFTS 
        $estimated_price['price_breakdown']['density_surge'] = $density_surge;
        $estimated_price['price_breakdown']['shift_surge'] 
        = ($estimated_price['price_breakdown']['price_after_distance'] * $shift_rate) - $estimated_price['price_breakdown']['price_after_distance'] ;
        $estimated_price['price_breakdown']['surge']  = $shift_surge + $density_surge;
        $estimated_price['price_breakdown']['price_after_surge']  =  $estimated_price['price_breakdown']['price_after_distance'] + $estimated_price['price_breakdown']['surge'] ;

        //PRICE AFTER APP CHARGE
        $estimated_price['price_breakdown']['app_charge_percent'] = 10;   //Default::TO BE FETCHED from DB SETTINGS 
        $estimated_price['price_breakdown']['app_charge'] =  $estimated_price['price_breakdown']['app_charge_percent']/100 * $estimated_price['price_breakdown']['price_after_surge'];
        $estimated_price['price_breakdown']['price_after_app_charge']  =  $estimated_price['price_breakdown']['price_after_surge'] + $estimated_price['price_breakdown']['surge'] ;

        //PRICE AFTER DURATION CHARGE
        $estimated_price['price_breakdown']['price_per_min'] = $vehicle_type->price_per_min;
        $estimated_price['price_breakdown']['duration_charge'] = ($vehicle_type->price_per_min * $duration/60); 
        $estimated_price['price_breakdown']['price_after_duration']  =  $estimated_price['price_breakdown']['price_after_app_charge'] + $estimated_price['price_breakdown']['duration_charge'] ;

        $estimated_price['price_breakdown']['total_price'] = 
        ($estimated_price['price_breakdown']['price_after_duration'] < $estimated_price['price_breakdown']['minimum_charge'])
        ? $estimated_price['price_breakdown']['minimum_charge'] : $estimated_price['price_breakdown']['price_after_duration'];

        $estimated_price['price_breakdown']['total_price'] = round( $estimated_price['price_breakdown']['total_price']);

        return $estimated_price;
    }



    /**
     * Fetches the rider locations with riders within the radius circle of certain origin latitude and longitude
     */
    public function get_available_riders_within_radius($origin_latitude, $origin_longitude, $vehicle_type_id,$radius=null)
    {
        $radius_distance = 5; //Radius in kilometers within origin center::To be fetched from DB SETTINGS
        return $this->rider_location_service->getNearbyAvailableRiders($origin_latitude, $origin_longitude, $vehicle_type_id);
    }
    
    /**
     * Fetches the pending bookings within the radius circle of certain origin latitude and longitude
     */
    public function get_nearby_pending_bookings($origin_latitude, $origin_longitude, $vehicle_type_id=null,$radius=null)
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
            $pending_bookings = [];
            if($vehicle_type_id != null)
                $pending_bookings = Booking::where('status','pending')->whereDate('created_at', Carbon::today())->get();
            else
                $pending_bookings = Booking::where('vehicle_type_id',$vehicle_type_id)->where('status','pending')->whereDate('created_at', Carbon::today())->get();

            $nearby_pending_bookings = [];
            foreach($pending_bookings as $booking)
            {
                $distance_from_origin = calcuateDistance( 
                    floatval($origin_latitude), 
                    floatval($origin_longitude), 
                    floatval($booking->location->origin_latitude), 
                    floatval($booking->location->origin_longitude)
                );
                
                if( $distance_from_origin <= $radius )
                {
                    $nearby_pending_bookings[] = $booking;
                }
            }
            //dd('ERROR: ',$nearby_pending_bookings);
            return $nearby_pending_bookings;
        }
        catch(Exception $e)
        {
            return NULL;
        }
    }



      
  



}
