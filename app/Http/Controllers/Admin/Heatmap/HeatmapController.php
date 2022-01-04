<?php

namespace App\Http\Controllers\Admin\Heatmap;

use App\Modules\Models\Location;
use App\Modules\Models\Rider;
use App\Modules\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//services
use App\Modules\Services\Booking\BookingService;
use App\Modules\Services\User\UserService;

class HeatmapController extends Controller
{

    protected $booking, $user_service;

    public function __construct(BookingService $booking, UserService $user_service)
    {
        $this->booking = $booking;
        $this->user_service = $user_service;
    }

    public function dispatcherShow()
    {
        return view('admin.map.dispatcher');
    }

    public function heatmapShow()
    {
        return view('admin.map.heatmap');
    }

    public function getBookingData($booking_id)
    {
        $booking = Booking::findOrFail($booking_id);
        return compact('booking');
    }
}
