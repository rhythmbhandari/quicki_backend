<?php

namespace App\Modules\Services\Booking;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Throwable;

//services
use App\Modules\Services\Location\LocationService;
use App\Modules\Services\Location\RiderLocationService;
use App\Modules\Services\Booking\CompletedTripService;
use Yajra\DataTables\Facades\DataTables;

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

    function getBooking()
    {
        return $this->booking;
    }

    /*For DataTable*/
    public function  getAllData($filter = null)
    {
        $query = $this->booking->all();
        // if (Auth::user()->hasRole('Vendor')) {
        //     $query = $this->vehicle->whereVendorId(Auth::user()->vendor->id)->latest()->with(['vendor', 'model', 'model.type', 'model.manufacturer', 'bookings' => function ($query) {
        //         return $query->where('status', 'running');
        //     }]);
        // } else {
        //     $query = $this->vehicle->latest()->with(['vendor', 'model', 'model.type', 'model.manufacturer', 'bookings' => function ($query) {
        //         return $query->where('status', 'running');
        //     }]);
        // }

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('vehicle_type', function (Booking $booking) {
                return $booking->vehicle_type->name;
            })
            ->addColumn('customer', function (Booking $booking) {
                // return "test 1";
                return $booking->user->name;
            })
            ->editColumn('rider', function (Booking $booking) {
                // return "test 2";
                if ($booking->rider_id != null)
                    return $booking->rider->user->name;
                return "N/A";
            })
            ->editColumn('start_time', function (Booking $booking) {
                if ($booking->start_time != null)
                    return $booking->start_time;
                else return "N/A";
            })
            ->editColumn('end_time', function (Booking $booking) {
                if ($booking->end_time != null)
                    return $booking->end_time;
                else return "N/A";
            })
            ->editColumn('origin', function (Booking $booking) {
                return $booking->origin;
            })
            ->editColumn('destination', function (Booking $booking) {
                return $booking->destination;
            })
            ->editColumn('status', function (Booking $booking) {
                return getTableHtml($booking, 'status');
            })
            ->editColumn('actions', function (Booking $booking) {
                $editRoute = route('admin.booking.edit', $booking->id);
                $deleteRoute = '';
                $optionRoute = '';
                $optionRouteText = '';
                return getTableHtml($booking, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText);
            })->rawColumns(['image', 'status', 'actions', 'booking', 'vehicle_type'])
            ->make(true);
    }


    function create($userId, array $data)
    {


        try {
            $data['user_id'] = $userId;
            //default status = pending

            $data['status'] = isset($data['status']) ? $data['status'] : 'pending';

            //Parse the possible string values of latitudes and longitudes to double/float
            $data['location']['latitude_origin'] = floatval($data['location']['latitude_origin']);
            $data['location']['longitude_origin'] = floatval($data['location']['longitude_origin']);
            $data['location']['latitude_destination'] = floatval($data['location']['latitude_destination']);
            $data['location']['longitude_destination'] = floatval($data['location']['longitude_destination']);

            if (isset($data['stoppage']) && count($data['stoppage']) > 0) {
                for ($i = 0; $i < count($data['stoppage']); $i++) {
                    $data['stoppage'][$i]['latitude'] = floatval($data['stoppage'][$i]['latitude']);
                    $data['stoppage'][$i]['longitude'] = floatval($data['stoppage'][$i]['longitude']);
                }
            }

            //parse distance and duration to integer
            $data['distance'] = intval($data['distance']);
            $data['duration'] = intval($data['duration']);
            $data['price'] = intval($data['price']);
            // $data['location_id'] =intval( $data['location_id'] );
            $data['user_id'] = intval($data['user_id']);
            $data['rider_id'] = isset($data['rider_id']) ? intval($data['rider_id']) : null;

            // dd("booking data", $data);


            $createdBooking = $this->booking->create($data);

            if ($createdBooking) {
                //CREATE LOCATION
                $createdLocation = $this->location_service->create($data['location']);
                if ($createdLocation) {
                    $createdBooking->location_id = intval($createdLocation->id);
                    $createdBooking->save();
                    $createdBooking->location = $createdLocation;
                    return $createdBooking;
                }
            }
            return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    function update(array $data, $bookingId)
    {


        try {
            //default status = pending

            $data['status'] = isset($data['status']) ? $data['status'] : 'pending';

            //Parse the possible string values of latitudes and longitudes to double/float
            $data['location']['latitude_origin'] = floatval($data['location']['latitude_origin']);
            $data['location']['longitude_origin'] = floatval($data['location']['longitude_origin']);
            $data['location']['latitude_destination'] = floatval($data['location']['latitude_destination']);
            $data['location']['longitude_destination'] = floatval($data['location']['longitude_destination']);

            if (isset($data['stoppage']) && count($data['stoppage']) > 0) {
                for ($i = 0; $i < count($data['stoppage']); $i++) {
                    $data['stoppage'][$i]['latitude'] = floatval($data['stoppage'][$i]['latitude']);
                    $data['stoppage'][$i]['longitude'] = floatval($data['stoppage'][$i]['longitude']);
                }
            }

            //parse distance and duration to integer
            $data['distance'] = intval($data['distance']);
            $data['duration'] = intval($data['duration']);
            $data['price'] = intval($data['price']);
            // $data['location_id'] =intval( $data['location_id'] );
            $data['user_id'] = intval($data['user_id']);
            $data['rider_id'] = isset($data['rider_id']) ? intval($data['rider_id']) : null;

            // dd("booking data", $data);


            $this->booking->find($bookingId)->update($data);
            $updatedBooking = $this->booking->find($bookingId);
            // dd($updatedBooking);

            if ($updatedBooking) {
                $createdLocation = $this->location_service->update($data['location'], $updatedBooking->location_id);
                return $createdLocation;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }


    function update_status(array $data)
    {
        // dd("DATA: ",$data['optional_data']);
        try {
            $booking_id = intval($data['booking_id']);
            $new_status = $data['new_status'];

            $booking = Booking::findOrFail($booking_id);
            $booking->status = $new_status;

            if ($booking->save()) {
                if ($new_status == "accepted") {
                    $booking->rider_id = intval($data['optional_data']['rider_id']);
                    if ($booking->save())
                        return $booking;
                } else if ($new_status == "running") {
                    $booking->start_time = Carbon::now();
                    $booking->save();
                    return $booking;
                } else if ($new_status == "completed") {
                    if (!$booking->start_time) {
                        $booking->start_time = Carbon::now();
                        $booking->end_time = Carbon::now();
                    } else {
                        $booking->end_time = Carbon::now();
                    }
                    $booking->save();

                    //CREATE COMPLTED TRIP RECORD for COMPLETED STATUS
                    $completed_trip_data = $booking->toArray();
                    $completed_trip_data['booking_id'] = intval($booking->id);



                    //RECALCULATE THE BOOKING PRICE WITH UPDATED DURATION
                    $new_duration = $this->getTimeDiffInSeconds($booking->start_time, $booking->end_time);
                    $completed_trip_data['duration'] = $new_duration;
                    $final_price = 0;
                    $final_price = $this->calculate_final_price(
                        $booking->vehicle_type_id,
                        $booking->price,
                        $booking->duration,
                        $new_duration
                    );
                    $completed_trip_data['price'] = $final_price;

                    $booking->createdCompletedTrip = $this->completed_trip_service->create($completed_trip_data);
                    return $booking;
                } else if ($new_status == "cancelled") {

                    if (!$booking->start_time) {
                        $booking->start_time = Carbon::now();
                        $booking->end_time = Carbon::now();
                    } else {
                        $booking->end_time = Carbon::now();
                    }
                    $booking->save();


                    //CREATE COMPLTED TRIP RECORD for CANCELLED STATUS
                    $cancelled_trip_data = $booking->toArray();
                    $cancelled_trip_data['booking_id'] = intval($booking->id);


                    $booking->createdCompletedTrip = $this->completed_trip_service->create($cancelled_trip_data);
                    return $booking;
                }
            }
            return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }


    public function getTimeDiffInSeconds($start, $end)
    {
        try {
            $start = Carbon::parse($start);
            $end = Carbon::parse($end);
            $duration = intval($end->diffInSeconds($start));

            return $duration;
        } catch (Throwable $e) {
            return 1;
        }
    }


    public function active_user_booking($userId)
    {
        try {
            $booking = $this->booking->where('user_id', $userId)->where(function ($query) {
                $query->where('status', 'pending')
                    ->orWhere('status', 'accepted')
                    ->orWhere('status', 'running');
            })->with('location')->first();
            return $booking;
        } catch (Exception $e) {
            return null;
        }
    }


    public function active_rider_booking($riderId)
    {
        try {
            $booking = $this->booking->where('rider_id', $riderId)->where(function ($query) {
                $query->where('status', 'accepted')
                    ->orWhere('status', 'running');
            })->with('location')->first();
            return $booking;
        } catch (Exception $e) {
            return null;
        }
    }


    //Takes distance in km and duration in seconds
    public function get_estimated_price(array $data)
    {

        $estimated_prices = []; //estimated price objects for every vehicle types

        //DEDUCING SHIFT SURGE
        $vehicle_types = VehicleType::where('status', 'active')->get();
        foreach ($vehicle_types as $vehicle_type) {
            $estimated_prices[] = $this->calculateEstimatedPrice($data['origin_latitude'], $data['origin_longitude'], $vehicle_type->id, $data['distance'], $data['duration']);
        }

        return $estimated_prices;
    }




    public function calculateEstimatedPrice($origin_latitude, $origin_longitude, $vehicle_type_id, $distance, $duration)
    {
        $distance = $distance / 1000;        //convert the distance in meters to kilometers

        $estimated_price = [];
        $vehicle_type = VehicleType::find($vehicle_type_id);

        /*****REQUIRED DATA FOR CALCULATION*****
         *-ORIGIN_LATITUDE
         *-ORIGIN_LONGITUDE
         *-DISTANCE
         *-DURATION
         */


        //TO BE CALCULATED
        $density_surge = false;
        $shift_surge = false;
        $total_surge = 0;
        $surge_rate = 1;

        //DEDUCTING DENSITY SURGE
        //1 rider for 3 pending bookings ; for >1:4, the surge applies::TO BE FETCHED from DB SETTINGS 
        $permissable_density = 1 / 3;
        //The density surge applies only if the current pending bookings is >= this value::TO BE FETCHED from DB SETTINGS 
        $threshold_pending_booking = 3;
        $nearbyRiders = $this->get_available_riders_within_radius($origin_latitude, $origin_longitude, $vehicle_type->id);
        //dd($nearbyRiders->count(), gettype($nearbyRiders));
        $nearbyRiders = ($nearbyRiders->count() > 0) ? $nearbyRiders->count() : 1;
        $nearbyPendingBookings = $this->get_nearby_pending_bookings($origin_latitude, $origin_longitude, $vehicle_type->id);
        // dd($nearbyPendingBookings, gettype($nearbyPendingBookings), count($nearbyPendingBookings));
        $nearbyPendingBookings = (count($nearbyPendingBookings) > 0) ? count($nearbyPendingBookings) : 1;

        $current_density = 1;
        if (!$nearbyRiders || !$nearbyRiders)
            $current_density = 1;
        else
            $current_density = $nearbyRiders / $nearbyPendingBookings;

        if (($current_density < $permissable_density) &&  ($current_pending_booking >= $threshold_pending_booking)) {
            //$density_surge = 30;    //CONSTANT TO BE FETCHED from DB SETTINGS 
            $density_surge = true;
        }


        //DEDUCTING SHIFT SURGE
        $currentTime = Carbon::now();
        // $shift = 
        $shifts = Shift::where('vehicle_type_id', $vehicle_type_id)->where('status', 'active')->get();

        $shift = $shifts->filter(function ($shift) {
            $startTime = Carbon::createFromFormat('H', $shift->time_from);
            $endTime = Carbon::createFromFormat('H',  $shift->time_to);
            if ($currentTime->between($startTime, $endTime, true))   return true;
            else return false;
        });

        if ($shift)
            $shift_surge = true;


        if ($shift_surge || $density_surge) {
            $surge_rate = VehicleType::find($vehicle_type_id)->surge_rate;
            $surge_rate = ($surge_rate > 0) ? $surge_rate : 1;
        }


        //$shift_rate = isset($shift->rate)?$shift->rate:1;


        $estimated_price['vehicle_type_id'] = $vehicle_type->id;
        $estimated_price['vehicle_type_name'] = $vehicle_type->name;
        $estimated_price['shift'] = isset($shift->title) ? $shift->title : 1;
        $estimated_price['price_breakdown'] = [];
        //Provided
        $estimated_price['price_breakdown']['minimum_charge'] = intval($vehicle_type->base_fair);
        //PRICE AFTER DISTANCE
        $estimated_price['price_breakdown']['price_per_km'] = $vehicle_type->price_per_km;
        $estimated_price['price_breakdown']['price_after_distance']  = ($vehicle_type->price_per_km * $distance);

        //PRICE AFTER SURGE
        $estimated_price['price_breakdown']['surge_rate']  =  $surge_rate;
        $estimated_price['price_breakdown']['surge']  = ($surge_rate * $estimated_price['price_breakdown']['price_after_distance']) - $estimated_price['price_breakdown']['price_after_distance'];
        $estimated_price['price_breakdown']['price_after_surge'] = ($surge_rate * $estimated_price['price_breakdown']['price_after_distance']);



        //PRICE AFTER APP CHARGE
        $estimated_price['price_breakdown']['app_charge_percent'] = 10.0;   //Default::TO BE FETCHED from DB SETTINGS 
        $estimated_price['price_breakdown']['app_charge'] = floatval(number_format(($estimated_price['price_breakdown']['app_charge_percent'] / 100.0 * $estimated_price['price_breakdown']['price_after_surge']), 2));
        $estimated_price['price_breakdown']['price_after_app_charge']  =  $estimated_price['price_breakdown']['price_after_surge'] + $estimated_price['price_breakdown']['surge'];

        //PRICE AFTER DURATION CHARGE
        $estimated_price['price_breakdown']['price_per_min'] = $vehicle_type->price_per_min;
        $estimated_price['price_breakdown']['duration_charge'] = ($vehicle_type->price_per_min * $duration / 60);
        $estimated_price['price_breakdown']['price_after_duration']  =  $estimated_price['price_breakdown']['price_after_app_charge'] + $estimated_price['price_breakdown']['duration_charge'];

        $estimated_price['price_breakdown']['total_price'] =
            ($estimated_price['price_breakdown']['price_after_duration'] < $estimated_price['price_breakdown']['minimum_charge'])
            ? $estimated_price['price_breakdown']['minimum_charge'] : $estimated_price['price_breakdown']['price_after_duration'];

        $estimated_price['price_breakdown']['total_price'] = round($estimated_price['price_breakdown']['total_price']);

        return $estimated_price;
    }

    public function calculate_final_price($vehicle_type_id, $old_estimated_price, $old_duration, $new_duration = 600)  //minimum 10 minutes duration in seconds
    {
        try {
            $price_per_min = VehicleType::find($vehicle_type_id)->price_per_min;

            //Remove the old duration's price from the total price
            $old_duration_charge = $price_per_min * $old_duration / 60;
            $new_total_price = $old_estimated_price - $old_duration_charge;

            //Add new duration price to new total price
            $new_duration_charge = $price_per_min * $new_duration / 60;
            $new_total_price = $new_total_price + $new_duration_charge;

            return round($new_total_price);
        } catch (Throwable $e) {
            //dd($e);
            return $old_estimated_price;
        }
    }


    /**
     * Fetches the rider locations with riders within the radius circle of certain origin latitude and longitude
     */
    public function get_available_riders_within_radius($origin_latitude, $origin_longitude, $vehicle_type_id, $radius = null)
    {
        $radius = !empty(config('settings.scan_radius')) ? floatval(config('settings.scan_radius')) : 5.0; //Radius in kilometers within origin center::To be fetched from DB SETTINGS
        return $this->rider_location_service->getNearbyAvailableRiders($origin_latitude, $origin_longitude, $vehicle_type_id);
    }

    /**
     * Fetches the pending bookings within the radius circle of certain origin latitude and longitude
     */
    public function get_nearby_pending_bookings($origin_latitude, $origin_longitude, $vehicle_type_id = null, $radius = null)
    {
        try {
            try {
                if ($radius == null) {
                    $radius = !empty(config('settings.scan_radius')) ? floatval(config('settings.scan_radius')) : 5.0;
                } else {
                    $radius = floatval($radius);
                }
            } catch (Exception $e) {
                $radius = 5.0;
            }
            $pending_bookings = [];
            if ($vehicle_type_id != null)
                $pending_bookings = Booking::where('status', 'pending')->whereDate('created_at', Carbon::today())->get();
            else
                $pending_bookings = Booking::where('vehicle_type_id', $vehicle_type_id)->where('status', 'pending')->whereDate('created_at', Carbon::today())->get();

            $nearby_pending_bookings = [];
            foreach ($pending_bookings as $booking) {
                $distance_from_origin = calcuateDistance(
                    floatval($origin_latitude),
                    floatval($origin_longitude),
                    floatval($booking->location->origin_latitude),
                    floatval($booking->location->origin_longitude)
                );

                if ($distance_from_origin <= $radius) {
                    $nearby_pending_bookings[] = $booking;
                }
            }
            //dd('ERROR: ',$nearby_pending_bookings);
            return $nearby_pending_bookings;
        } catch (Exception $e) {
            return NULL;
        }
    }



    public function find($id)
    {
        return $this->booking->find($id);
    }
}
