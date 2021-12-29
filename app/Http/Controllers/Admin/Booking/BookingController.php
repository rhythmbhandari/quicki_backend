<?php

namespace App\Http\Controllers\Admin\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kamaln7\Toastr\Facades\Toastr;

//services
use App\Modules\Services\Booking\BookingService;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\Booking;
use App\Modules\Models\User;
use App\Modules\Models\Rider;
use App\Modules\Models\VehicleType;
use App\Modules\Models\CompletedTrip;
use App\Http\Requests\Admin\Booking\BookingRequest;

class BookingController extends Controller
{
    protected $booking, $user_service;

    public function __construct(BookingService $booking, UserService $user_service)
    {
        $this->booking = $booking;
        $this->user_service = $user_service;
    }

    public function sanitize(Request $request)
    {

        if ($request->status == "pending") {
            $request->merge(['start_time' => null]);
            $request->merge(['end_time' => null]);
            $request->merge(['rider_id' => null]);
        }

        if ($request->status == "accepted") {
            $request->merge(['start_time' => null]);
            $request->merge(['end_time' => null]);
        }

        if ($request->status == "running") {
            $request->merge(['end_time' => null]);
        }

        return $request;
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

    public function getAllData()
    {
        return $this->booking->getAllData();
    }

    function bookingAjax(Request $request)
    {
        $query = Booking::with(['user' => function ($q) {
            $q->select('id', 'first_name', 'last_name');
        }, 'rider.user' => function ($q) {
            $q->select('id', 'first_name', 'last_name');
        }])->simplePaginate(10);
        // dd($query->toArray());
        $results = array();
        foreach ($query as $object) {
            array_push($results, [
                'id' => $object['id'],
                'text' => 'ID: ' . $object->id . ' / Origin: ' . $object->origin . ' / Destination: ' . $object->destination . ' / Customer: ' . $object->user->first_name . ' ' . $object->user->last_name . ' / Rider: ' . $object->rider->user->first_name . ' ' . $object->rider->user->last_name,
                'booking_id' => $object->id
            ]);
        }
        // $pagination = [
        //     'more' => !is_null($query->toArray()['next_page_url'])
        // ];
        return compact('results');
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
        $request = $this->sanitize($request);
        //voucher
        $data = $request->all();
        
        $data['location']['latitude_origin'] = $data['start_coordinate']['latitude'];
        $data['location']['longitude_origin'] = $data['start_coordinate']['longitude'];
        $data['location']['latitude_destination'] = $data['end_coordinate']['latitude'];
        $data['location']['longitude_destination'] = $data['end_coordinate']['longitude'];
        $data['origin'] = $data['start_location'];
        $data['destination'] = $data['end_location'];

        // $estimatedPrice = $this->booking->calculateEstimatedPrice($data['location']['latitude_origin'], 
        //                                                         $data['location']['latitude_destination'], 
        //                                                         $request->vehicle_type_id, 
        //                                                         $request->distance, 
        //                                                         $request->duration,

        //                                                     );
        $data['price'] = isset($data['price'])?intval($data['price']):0;//$estimatedPrice['price_breakdown']['total_price'];

        // dd($data);
        //BOOKING STORE
        return DB::transaction(function () use ($request, $data) {
            $createdBooking = $this->booking->create($request->only('user_id'), $data);
            if ($createdBooking) {
                Toastr::success('Booking created successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.booking.index');
            }
            Toastr::error('Booking cannot be created.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.booking.index');
        });
    }

    public function estimatedPriceAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type_id' =>  'required',
            'origin_latitude' => 'required',
            'origin_longitude' => 'required',
            'distance' => 'required',
            'duration' => 'required'
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }
        
        // dd($request->all());
        $estimatedPrice = $this->booking->calculateEstimatedPrice(
            $request->origin_latitude, 
            $request->origin_longitude,
            $request->vehicle_type_id, 
            $request->distance, 
            $request->duration,
            null,
            null,
            null
        );
        return response(compact('estimatedPrice'), 200);
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

    /**
     * Update resource
     */
    public function update(BookingRequest $request, $id)
    {
        $request = $this->sanitize($request);
        $booking = Booking::findOrFail($id);
        $voucher = isset($booking->price_detail->promotion_voucher_id) ? $booking->price_detail->promotion_voucher->code : null;

        $data = $request->all();
        $data['location']['latitude_origin'] = $data['start_coordinate']['latitude'];
        $data['location']['longitude_origin'] = $data['start_coordinate']['longitude'];
        $data['location']['latitude_destination'] = $data['end_coordinate']['latitude'];
        $data['location']['longitude_destination'] = $data['end_coordinate']['longitude'];
        $data['origin'] = $data['start_location'];
        $data['destination'] = $data['end_location'];

        $estimatedPrice = $this->booking->calculateEstimatedPrice($data['location']['latitude_origin'], 
                                                                $data['location']['latitude_destination'], 
                                                                $request->vehicle_type_id, 
                                                                $request->distance, 
                                                                $request->duration,
                                                                $booking->user_id,
                                                                $voucher,
                                                                $booking->id
                                                                );
        $data['price'] = $estimatedPrice['price_breakdown']['total_price'];
        //UPDATE STATUS
        return DB::transaction(function () use ($data, $id) {
            $updatedBooking = $this->booking->update($data, $id);
            if ($updatedBooking) {
                // if ($updatedBooking->status == "completed") {
                //     $completed_trip = CompletedTrip::where('booking_id', $updatedBooking->id)->first();
                //     $response = ['message' => 'Booking Status Updated Successfully! Created Completed Booking History', "completed_trip" => $updatedBooking->completed_trip];
                //     return response($response, 201);
                // } else if ($updatedBooking->status == "cancelled") {
                //     $completed_trip = CompletedTrip::where('booking_id', $updatedBooking->id)->first();
                //     $response = ['message' => 'Booking Status Updated Successfully! Created Cancelled Booking History', "completed_trip" => $updatedBooking->completed_trip];
                //     return response($response, 201);
                // } else {
                Toastr::success('Booking updated successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.booking.index');
                // }
            }
            Toastr::success('Booking failed to update.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.booking.index');
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
