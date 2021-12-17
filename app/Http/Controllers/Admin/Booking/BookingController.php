<?php

namespace App\Http\Controllers\Admin\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//services
use App\Modules\Services\Booking\BookingService;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\Booking;
use App\Modules\Models\User;
use App\Modules\Models\Rider;
use App\Modules\Models\VehicleType;
use App\Modules\Models\CompletedTrip;

class BookingController extends Controller
{
    protected $booking, $user_service;

    public function __construct(BookingService $booking, UserService $user_service)
    {
        $this->booking = $booking;
        $this->user_service = $user_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.booking.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.booking.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $booking = $this->booking->find($id);
        return view('admin.booking.edit', compact('booking'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //AUTHENTICATION CHECK
        $user = null;
        try {
            $user = Auth::user();
        } catch (Exception $e) {
            $response = ['message' => 'Unauthorized: Login Required!'];
            return response($response, 401);
        }
        if (!$user) {
            $response = ['message' => 'Unauthorized: Login Required!'];
            return response($response, 401);
        }

        //CHECK IF USER HAVE EXISTING ACTIVE BOOKINGS
        $active_bookings = Booking::where('user_id', $user->id)->where(function ($query) {
            $query->where('status', 'pending')
                ->orWhere('status', 'accepted')
                ->orWhere('status', 'running');
        })->count();

        if ($active_bookings > 0) {
            $response = ['message' => 'You already have existing active bookings!'];
            return response($response, 400);
        }

        //VALIDATIONS
        $validator = Validator::make($request->all(), [

            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'vehicle_type_id' =>  ['required', function ($attribute, $value, $fail) {
                $vehicle_type = VehicleType::find($value);

                if (!$vehicle_type) {
                    $fail('The vehicle type does not exist!');
                }
            },],
            'distance' => 'required|numeric',
            'duration' => 'required|numeric',
            'passenger_number' => 'nullable|integer',

            //Location
            'location.latitude_origin' => 'required|numeric',
            'location.longitude_origin' => 'required|numeric',
            'location.latitude_destination' => 'required|numeric',
            'location.longitude_destination' => 'required|numeric',


        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }


        //dd("Booking DATA: ", $request->all());
        if ($user) {

            //ROLE CHECK FOR CUSTOMER
            if (!$this->user_service->hasRole($user, 'customer')) {
                $response = ['message' => 'Forbidden Access!'];
                return response($response, 403);
            }

            //BOOKING STORE
            return DB::transaction(function () use ($request, $user) {
                $createdBooking = $this->booking->create($user->id, $request->all());
                if ($createdBooking) {
                    $response = ['message' => 'Booking Successful!',  "booking" => $createdBooking,];
                    return response($response, 201);
                }
                return response("Internal Server Error!", 500);
            });
        }
    }


    public function change_status(Request $request)
    {
        Booking::findOrFail(10);

        //AUTHENTICATION CHECK
        $user = null;
        try {
            $user = Auth::user();
        } catch (Exception $e) {
            $response = ['message' => 'Unauthorized: Login Required!'];
            return response($response, 401);
        }
        if (!$user) {
            $response = ['message' => 'Unauthorized: Login Required!'];
            return response($response, 401);
        }
        // dd($request->all());

        //VALIDATIONS
        $validator = Validator::make($request->all(), [
            'booking_id' => ['required', function ($attribute, $value, $fail) {
                $booking = Booking::find($value);
                if (!$booking) {
                    $fail('Booking not found!');
                }
            },],
            'new_status' => 'required|string',
            'optional_data.rider_id'  =>
            ['nullable', function ($attribute, $value, $fail) {
                $rider = Rider::find($value);
                if (!$rider) {
                    $fail('Rider not found!');
                }
            },],
            'optional_data.cancelled_by_id'  =>
            ['nullable', function ($attribute, $value, $fail) {
                $user = User::find($value);
                if (!$user) {
                    $fail('User not found!');
                }
            },],
            'optional_data.cancelled_by_type'  =>
            ['nullable', function ($attribute, $value, $fail) {
                if (!($value == "customer" || $value == "rider")) {
                    $fail('The booking can only be cancelled by "customer" or "rider"!');
                }
            },],
            'optional_data.cancel_message'  => 'nullable|string',


        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        //UPDATE STATUS
        return DB::transaction(function () use ($request, $user) {
            $updatedBooking = $this->booking->update_status($request->all());
            if ($updatedBooking) {
                if ($updatedBooking->status == "completed") {
                    $completed_trip = CompletedTrip::where('booking_id', $updatedBooking->id)->first();
                    $response = ['message' => 'Booking Status Updated Successfully! Created Completed Booking History', "completed_trip" => $updatedBooking->completed_trip];
                    return response($response, 201);
                } else if ($updatedBooking->status == "cancelled") {
                    $completed_trip = CompletedTrip::where('booking_id', $updatedBooking->id)->first();
                    $response = ['message' => 'Booking Status Updated Successfully! Created Cancelled Booking History', "completed_trip" => $updatedBooking->completed_trip];
                    return response($response, 201);
                } else {
                    $response = ['message' => 'Booking Status Updated Successfully!'];
                    return response($response, 200);
                }
            }
            return response("Internal Server Error!", 500);
        });
    }

    public function getActiveUserBooking()
    {
        $user = Auth::user();

        //ROLE CHECK FOR CUSTOMER
        if (!$this->user_service->hasRole($user, 'customer')) {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        return $this->booking->active_user_booking($user->id);
    }

    public function getActiveRiderBooking()
    {
        $user = Auth::user();

        //ROLE CHECK FOR RIDER
        if (!$this->user_service->hasRole($user, 'rider')) {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        return $this->booking->active_rider_booking($user->rider->id);
    }
}
