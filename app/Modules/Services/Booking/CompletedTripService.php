<?php

namespace App\Modules\Services\Booking;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

//modules
use App\Modules\Models\CompletedTrip;
use App\Modules\Models\BookingTrip;

//services
use App\Modules\Services\Payment\PaymentService;

class CompletedTripService extends Service
{
   
    protected $completed_trip, $payment_service;

    function __construct(CompletedTrip $completed_trip, PaymentService $payment_service)
    {
        $this->completed_trip = $completed_trip;
        $this->payment_service = $payment_service;
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
            $data['location_id'] = intval($data['location_id']);
            $data['passenger_number'] =   ( isset($data['passenger_number']) && !empty($data['passenger_number']) ) ?  intval($data['passenger_number']) : null;
            $data['distance'] = intval($data['distance']);
            $data['duration'] = intval($data['duration']);
            // $data['cancelled_by_id'] =  ( isset($data['cancelled_by_id']) && !empty($data['cancelled_by_id']) ) ? intval($data['cancelled_by_id']) : null;
           
            $data['optional_data']['cancelled_by_id'] = isset($data['optional_data']['cancelled_by_id']) ? intval($data['optional_data']['cancelled_by_id']) : null ;
            
            $data['optional_data']['cancelled_by_type'] = isset($data['optional_data']['cancelled_by_type']) ? $data['optional_data']['cancelled_by_type'] : "customer";
           
            $data['optional_data']['cancel_message'] = isset($data['optional_data']['cancel_message']) ? $data['optional_data']['cancel_message'] : "" ;
            
            $createdCompltedTrip =  $this->completed_trip->create($data);
            if($createdCompltedTrip)
            {
                $payment_data = [];
                $payment_data['completed_trip_id'] = $createdCompltedTrip->id;
                //DEDUCE ADMIN's COMMISSION FOR THE TRIP
                $commission_percent = $createdCompltedTrip->booking->vehicle_type->commission;
                
                $payment_data['commission_amount'] = round( $createdCompltedTrip->price * $commission_percent/100 ) ;
                $payment_data['payment_status'] = 'unpaid' ;
                $payment_data['commission_payment_status'] = 'unpaid' ;

                //CREATE PAYMENT RECORD
                $createdPayment = $this->payment_service->create($payment_data);
                if($createdPayment)
                    return $createdCompltedTrip;
            }
            return NULL;
        }
        catch(Exception $e)
        {
            return NULL;
        }
    }
    
}
