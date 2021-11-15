<?php

namespace App\Modules\Services\Booking;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\Booking;

class BookingService extends Service
{
    protected $booking;

    function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    function getBooking(){
        return $this->booking;
    }
    
}
