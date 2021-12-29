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

    public function bookingHeatMap()
    {
        return view('admin.heatmap.booking');
    }

    public function getBookingInfo($booking_id)
    {
        $booking = Booking::finOrFail($booking_id);
        dd("booking info with booking location will be fetched here!");
        return view('admin.heatmap.booking', compact('booking'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        //
    }
}
