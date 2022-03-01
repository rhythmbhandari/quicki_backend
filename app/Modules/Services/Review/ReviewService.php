<?php

namespace App\Modules\Services\Review;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\DB;

//models
use App\Modules\Models\Review;
use App\Modules\Models\Booking;

class ReviewService extends Service
{
    protected $review;

    function __construct(Review $review)
    {
        $this->review = $review;
    }

    function getReview(){
        return $this->review;
    }


    function create(array $data)
    {
        try{
            $booking = Booking::find($data['booking_id']);
            $data['ride_date'] = isset($data['ride_date']) ? $data['ride_date'] : $booking->updated_at ;
            $data['user_id'] = isset($data['user_id']) ? intval($data['user_id']) : $booking->user_id ;
            $data['rider_id'] = isset($data['rider_id']) ? intval($data['rider_id']) : $booking->rider_id ;
            $data['booking_id'] = intval($booking->id);
            $data['rate']   = isset($data['rate']) ? floatval($data['rate']) : 3 ;
            //default status = pending
            
            $createdReview = $this->review->create($data);
            if($createdReview)
            {
                return $createdReview;
            }
            return NULL;
        }
        catch(Exception $e)
        {
            return NULL;
        }
    }

}
