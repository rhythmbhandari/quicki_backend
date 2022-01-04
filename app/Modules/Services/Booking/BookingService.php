<?php

namespace App\Modules\Services\Booking;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Throwable;
use App\Modules\Models\Event;
use App\Events\BookingTimedOut;

//services
// use App\Modules\Services\Location\LocationService;
use App\Modules\Services\Location\RiderLocationService;
use App\Modules\Services\Booking\CompletedTripService;

use Yajra\DataTables\Facades\DataTables;

use App\Modules\Services\Booking\PriceDetailService;
use App\Modules\Services\Notification\NotificationService;

//models
use App\Modules\Models\Booking;
use App\Modules\Models\CompletedTrip;
use App\Modules\Models\VehicleType;
use App\Modules\Models\Shift;
use App\Modules\Models\User;
use App\Modules\Models\PromotionVoucher;
use App\Modules\Models\PriceDetail;
use App\Modules\Models\RiderLocation;
use App\Modules\Models\Rider;

class BookingService extends Service
{
    protected $booking,  $notification_service, $rider_location_service, $price_detail_service, $completed_trip_service;

    function __construct(Booking $booking, 
                        // LocationService $location_service, 
                        CompletedTripService $completed_trip_service, 
                        RiderLocationService $rider_location_service, 
                        PriceDetailService $price_detail_service, 
                        NotificationService $notification_service
                        )
    {
        $this->booking = $booking;
        // $this->location_service = $location_service;
        $this->completed_trip_service = $completed_trip_service;
        $this->rider_location_service = $rider_location_service;
        $this->price_detail_service = $price_detail_service;
        $this->notification_service = $notification_service;
    }

    function getBooking()
    {
        return $this->booking;
    }

    /*For DataTable*/
    public function  getAllData($filter = null)
    {
        $query = $this->booking->all();

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
                return $booking->location['origin']['name'];
            })
            ->editColumn('destination', function (Booking $booking) {
                return $booking->location['destination']['name'];
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
            // $data['location']['latitude_origin'] = floatval($data['location']['latitude_origin']);
            // $data['location']['longitude_origin'] = floatval($data['location']['longitude_origin']);
            // $data['location']['latitude_destination'] = floatval($data['location']['latitude_destination']);
            // $data['location']['longitude_destination'] = floatval($data['location']['longitude_destination']);
            $data['location']['origin']['name'] = $data['location']['origin']['name'];
            $data['location']['origin']['latitude'] = floatval($data['location']['origin']['latitude']);
            $data['location']['origin']['longitude'] = floatval($data['location']['origin']['longitude']);
            $data['location']['destination']['name'] = $data['location']['destination']['name'];
            $data['location']['destination']['latitude'] = floatval($data['location']['destination']['latitude']);
            $data['location']['destination']['longitude'] = floatval($data['location']['destination']['longitude']);


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

            $data['voucher'] = isset($data['voucher']) ? $data['voucher'] : null;

     

            $existing_codes = Booking::pluck('trip_id')->toArray();
            $data['trip_id'] = generateBookingCode($existing_codes);
            // dd($data['trip_id'], $existing_codes);
            // dd("booking data", $data);


            $createdBooking = $this->booking->create($data);

            if ($createdBooking) {
                //CREATE LOCATION
                // $createdLocation = $this->location_service->create($data['location']);
                // dd($createdBooking, $createdLocation);
                // if ($createdLocation) {
                //     $createdBooking->location_id = intval($createdLocation->id);
                //     $createdBooking->save();
                    // return $createdBooking;

                    
                    //CREAT PRICE DETAIL
                    $price_detail_data = $this->calculateEstimatedPrice(
                                                    $data['location']['origin']['latitude'] , 
                                                    $data['location']['origin']['longitude'] , 
                                                    $data['vehicle_type_id'], 
                                                    $data['distance'], 
                                                    $data['duration'],
                                                    $createdBooking->user_id,
                                                    $data['voucher'],
                                                    $createdBooking->id
                                                );  
                    $createdBooking->price = $price_detail_data['price_breakdown']['total_price'];
                    $createdBooking->save();                            
                   // $createdBooking->location = $createdLocation;

                    $price_detail_data = $price_detail_data['price_breakdown'];
                    
                    $price_detail_data['booking_id'] = $createdBooking->id;
                    //dd($createdBooking->toArray(), $price_detail_data);
                    $this->price_detail_service->create($price_detail_data);
                    
                     //Send Notification
                     $this->notification_service->send_firebase_notification( 
                        [
                            ['customer', $createdBooking->user_id ],
                        ],
                        "booking_created",
                        "individual"
                     );


                    return $createdBooking;
                // }



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
            // $data['location']['latitude_origin'] = floatval($data['location']['latitude_origin']);
            // $data['location']['longitude_origin'] = floatval($data['location']['longitude_origin']);
            // $data['location']['latitude_destination'] = floatval($data['location']['latitude_destination']);
            // $data['location']['longitude_destination'] = floatval($data['location']['longitude_destination']);
            $data['location']['origin']['name'] = floatval($data['location']['origin']['name']);
            $data['location']['origin']['latitude'] = floatval($data['location']['origin']['latitude']);
            $data['location']['origin']['longitude'] = floatval($data['location']['origin']['longitude']);
            $data['location']['destination']['name'] = floatval($data['location']['destination']['name']);
            $data['location']['destination']['latitude'] = floatval($data['location']['destination']['latitude']);
            $data['location']['destination']['longitude'] = floatval($data['location']['destination']['longitude']);

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
                // $createdLocation = $this->location_service->update($data['location'], $updatedBooking->location_id);
                return $updatedBooking;
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


            //NOTIFICATION DATA


            if ($booking->save()) {
                if ($new_status == "accepted") {
                    $booking->rider_id = intval($data['optional_data']['rider_id']);

                    $rider_location = RiderLocation::where('rider_id',$booking->rider_id)->first();
                    if(isset($rider_location))
                    {
                        $rider_location->availability = "unavailable";
                        $rider_location->save();
                    }

                    if ($booking->save()) {
                        //Send Notification
                        $this->notification_service->send_firebase_notification(
                            [
                                ['customer', $booking->user_id],
                                ['rider', $booking->rider_id]
                            ],
                            "booking_accepted",
                            "some"
                        );


                        return $booking;
                    }
                } else if ($new_status == "running") {
                    $booking->start_time = Carbon::now();
                    $booking->save();

                    //Send Notification
                    $this->notification_service->send_firebase_notification(
                        [
                            ['customer', $booking->user_id],
                            ['rider', $booking->rider_id]
                        ],
                        "booking_running",
                        "some"
                    );

                    return $booking;
                } else if ($new_status == "completed") {
                    if (!$booking->start_time) {
                        $booking->start_time = Carbon::now();
                        $booking->end_time = Carbon::now();
                    } else {
                        $booking->end_time = Carbon::now();
                    }

                    //Make the rider available again
                    $rider_location = RiderLocation::where('rider_id',$booking->rider_id)->first();
                    if(isset($rider_location))
                    {
                        $rider_location->availability = "unavailable";
                        $rider_location->save();
                    }


                    $booking->save();

                    //CREATE COMPLTED TRIP RECORD for COMPLETED STATUS
                    $completed_trip_data = $booking->toArray();
                    $completed_trip_data['booking_id'] = intval($booking->id);
                  
                    if( isset($data['optional_data']['location']) && isset($data['optional_data']['distance'])  )
                    {

                        $completed_trip_data['location']['origin']['name'] = $data['optional_data']['location']['origin']['name'];
                        $completed_trip_data['location']['origin']['latitude'] = floatval($data['optional_data']['location']['origin']['latitude']);
                        $completed_trip_data['location']['origin']['longitude'] = floatval($data['optional_data']['location']['origin']['longitude']);
                        $completed_trip_data['location']['destination']['name'] = $data['optional_data']['location']['destination']['name'];
                        $completed_trip_data['location']['destination']['latitude'] = floatval($data['optional_data']['location']['destination']['latitude']);
                        $completed_trip_data['location']['destination']['longitude'] = floatval($data['optional_data']['location']['destination']['longitude']);

                        $completed_trip_data['distance'] = $data['optional_data']['distance'];

                        //dd('reached here');
                    }
                    //dd('did',$data);
                    //RECALCULATE THE BOOKING PRICE WITH UPDATED DURATION
                    $new_duration = $this->getTimeDiffInSeconds($booking->start_time, $booking->end_time);
                    $completed_trip_data['duration'] = $new_duration;
                    $voucher = isset($booking->price_detail->promotion_voucher_id) ? $booking->price_detail->promotion_voucher->code : null;
                    $price_detail_data = $this->calculateEstimatedPrice(
                        $completed_trip_data['location']['origin']['latitude'],
                        $completed_trip_data['location']['origin']['longitude'],
                        $booking->vehicle_type_id,
                        $completed_trip_data['distance'],
                        $new_duration,
                        $booking->user_id,
                        $voucher,
                        $booking->id
                    );
                    $final_price = 0;
                    // $final_price = $this->calculate_final_price(
                    //     $booking->vehicle_type_id,
                    //     $booking->price,
                    //     $booking->duration,
                    //     $new_duration
                    // );
                    // dd($price_detail_data);
                    $final_price = $price_detail_data['price_breakdown']['total_price'];
                    $completed_trip_data['price'] = $final_price;

                    $booking->createdCompletedTrip = $this->completed_trip_service->create($completed_trip_data);

                    //CREAT PRICE DETAIL
                    $price_detail_data = $price_detail_data['price_breakdown'];
                    $price_detail_data['completed_trip_id'] =   $booking->createdCompletedTrip->id;
                    $this->price_detail_service->create($price_detail_data);

                    //Send Notification
                    $this->notification_service->send_firebase_notification(
                        [
                            ['customer', $booking->user_id],
                            ['rider', $booking->rider_id]
                        ],
                        "booking_completed",
                        "some"
                    );

                    return $booking;
                } else if ($new_status == "cancelled") {

                    if (!$booking->start_time) {
                        $booking->start_time = Carbon::now();
                        $booking->end_time = Carbon::now();
                    } else {
                        $booking->end_time = Carbon::now();
                    }

                    //Make the rider available again
                    $rider_location = RiderLocation::where('rider_id',$booking->rider_id)->first();
                    if(isset($rider_location))
                    {
                        $rider_location->availability = "unavailable";
                        $rider_location->save();
                    }


                    $booking->save();


                    //CREATE COMPLTED TRIP RECORD for CANCELLED STATUS
                    $cancelled_trip_data = $booking->toArray();
                    $cancelled_trip_data['booking_id'] = intval($booking->id);


                    $booking->createdCompletedTrip = $this->completed_trip_service->create($cancelled_trip_data);

                    $voucher = isset($booking->price_detail->promotion_voucher_id) ? $booking->price_detail->promotion_voucher->code : null;
                    // dd($booking, $voucher);
                    //CREAT PRICE DETAIL
                    $price_detail_data = $this->calculateEstimatedPrice(

                                                    $booking->location['origin']['latitude'], 
                                                    $booking->location['origin']['longitude'], 
                                                    $booking->vehicle_type_id, 
                                                    $booking->distance,  
                                                    $booking->duration, 
                                                    $booking->user_id,  
                                                    $voucher,
                                                    $booking->id
                                                );  
                    $price_detail_data = $price_detail_data['price_breakdown'];
                    $price_detail_data['completed_trip_id'] =   $booking->createdCompletedTrip->id;
                    $this->price_detail_service->create($price_detail_data);

                    //Send Notification
                    $this->notification_service->send_firebase_notification(
                        [
                            ['customer', $booking->user_id],
                            ['rider', $booking->rider_id]
                        ],
                        "booking_cancelled",
                        "some"
                    );

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


        try{
            $booking = $this->booking->where('user_id',$userId)->where(function($query){
                $query->where('status','pending')
                ->orWhere('status','accepted')
                ->orWhere('status','running');
            })->with('price_detail')->first();

            return $booking;
        } catch (Exception $e) {
            return null;
        }
    }


    public function active_rider_booking($riderId)
    {

        try{
            $booking = $this->booking->where('rider_id',$riderId)->where(function($query){
                $query->where('status','accepted')
                ->orWhere('status','running');
            })->with('price_detail')->first();
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
            $estimated_prices[] = $this->calculateEstimatedPrice(
                $data['origin_latitude'],
                $data['origin_longitude'],
                $vehicle_type->id,
                $data['distance'],
                $data['duration'],
                $data['user_id'],
                $data['voucher'],

            );
        }

        return $estimated_prices;
    }



    public function calculateEstimatedPrice($origin_latitude, 
                                            $origin_longitude, 
                                            $vehicle_type_id, 
                                            $distance, 
                                            $duration,
                                            $user_id=null,
                                            $voucher=null, 
                                            $booking_id=null
                                            )
    {
        // dd($booking_id,Booking::find($booking_id));
        $distance = $distance / 1000;        //convert the distance in meters to kilometers

        $estimated_price = [];
        $vehicle_type = VehicleType::find($vehicle_type_id);
        $surge_rates = $vehicle_type->surge_rates;
        $surge_levels = isset($vehicle_type->surge_rates) ? array_keys($surge_rates) : null;

        /*****REQUIRED DATA FOR CALCULATION*****
         *-ORIGIN_LATITUDE
         *-ORIGIN_LONGITUDE
         *-DISTANCE
         *-DURATION
         *-VOUCHER
         */



        //TO BE CALCULATED
        $density_surge = 0;
        $shift_surge = 0;
        $total_surge = 0;
        $surge_rate = 1;

        //DEDUCTING DENSITY SURGE
        //The minimum ratio of pending_bookings:riders after which the surge may apply
        $permissable_density = isset($vehicle_type->surge_rates) ?  min($surge_levels) : 1;

        //The density surge applies only if the current pending bookings is >= this value
        $threshold_pending_booking =  $vehicle_type->min_surge_customers;

        $nearbyRiders = $this->get_available_riders_within_radius($origin_latitude, $origin_longitude, $vehicle_type->id);
        $nearbyPendingBookings = $this->get_nearby_pending_bookings($origin_latitude, $origin_longitude, $vehicle_type->id);
        // dd('Rider COUNT: ',count($nearbyRiders), '  Booking COUNT: ',count($nearbyPendingBookings));

        $nearbyRiders = (count($nearbyRiders) > 0) ? count($nearbyRiders) : 1;
        $nearbyPendingBookings =  (count($nearbyPendingBookings) > 0) ? count($nearbyPendingBookings) : 1;

        // dd('BOOKING RESTRICTED! ', $vehicle_type, $vehicle_type->surge_rates, min(array_keys($vehicle_type->surge_rates)));

        $current_density = 1;
        if (!$nearbyRiders || !$nearbyRiders)
            $current_density = 1;
        else {
            /******
             * HERE THE FLOOR COULD BE CHANGED TO ROUND for applying surges more precisely but would increase the total prices more frequently
             */
            $current_density =  floor($nearbyPendingBookings / $nearbyRiders);
        }


        if (
            isset($vehicle_type->surge_rates) &&
            ($current_density >= $permissable_density) &&
            ($nearbyPendingBookings >= $threshold_pending_booking)
        ) {

            if (in_array($current_density, $surge_levels)) {
                $density_surge = $surge_rates[$current_density];
            } else {
                //Find the next lowest key/level *** use CLOSEST instead of NEXT LOWER IN CASE FOR MORE PRECISE/HIGHER SURGE RATES ***
                sort($surge_levels);
                $temp = min($surge_levels);
                foreach ($surge_levels as $level) {
                    if ($level < $current_density) {
                        $temp = $level;
                    }
                }
                $density_surge = $surge_rates[$temp];
            }
        } else {
            $density_surge = 0;
        }


        //DEDUCTING SHIFT SURGE
        // $currentTime = Carbon::now();
        $booking_time = Carbon::now();
        $old_duration = null;

        if ($booking_id) {
            $booking = Booking::select('created_at', 'duration')->where('id', $booking_id)->first();
            if ($booking) {
                $booking_time = $booking->created_at;
                $old_duration = $booking->duration;
            }
        }

        // $shift = 
        $shifts = Shift::where('vehicle_type_id', $vehicle_type_id)->whereStatus('active')->get();

        $shift = $shifts->filter(function ($shift) use ($booking_time) {
            $startTime = Carbon::createFromFormat('H', $shift->time_from);
            $endTime = Carbon::createFromFormat('H',  $shift->time_to);
            if ($booking_time->between($startTime, $endTime, true))   return true;
            else return false;
        });
        // dd($shifts->toArray(), $shift->toArray(), $booking_time->format('H'), $shift[0]->rate);
        if (isset($shift[0]->rate)) {
            //$shift_surge = $vehicle_type->default_surge_rate;
            $shift_surge = $shift[0]->rate;
        }


        if ($shift_surge || $density_surge) {
            $surge_rate =  ($density_surge > $shift_surge) ? $density_surge : $shift_surge;
        }


        $estimated_price['vehicle_type_id'] = $vehicle_type->id;
        $estimated_price['vehicle_type_name'] = $vehicle_type->name;
        $estimated_price['shift'] = isset($shift[0]->title) ? $shift[0]->title : "shift_surge";
        $estimated_price['price_breakdown'] = [];
        //Provided
        $estimated_price['price_breakdown']['base_fare'] = floatval($vehicle_type->base_fare);
        $estimated_price['price_breakdown']['base_covered_km'] = floatval($vehicle_type->base_covered_km);
        $esitmated_price['price_breakdown']['base_covers_duration'] = $vehicle_type->base_covers_duration;
        $estimated_price['price_breakdown']['minimum_charge'] = floatval($vehicle_type->min_charge);

        //PRICE AFTER DISTANCE AND SURGE
        $estimated_price['price_breakdown']['price_per_km'] = $vehicle_type->price_per_km;
        $distance_charged =   $distance - $vehicle_type->base_covered_km;
        $distance_charged = ($distance_charged > 0) ? $distance_charged : 0;
        // dd($distance_charged, $vehicle_type->base_covered_km, $distance);
        $estimated_price['price_breakdown']['base_covered_km'] = $vehicle_type->base_covered_km;
        $estimated_price['price_breakdown']['charged_km'] = $distance_charged;
        $estimated_price['price_breakdown']['price_after_distance']  = ($vehicle_type->price_per_km * $distance_charged);


        $estimated_price['price_breakdown']['shift_surge']  =  $shift_surge;
        $estimated_price['price_breakdown']['density_surge']  =  $density_surge;
        $estimated_price['price_breakdown']['surge_rate']  =  $surge_rate;
        $estimated_price['price_breakdown']['price_per_km_after_surge'] = $vehicle_type->price_per_km *  $surge_rate;
        $estimated_price['price_breakdown']['surge']  =
            ($distance_charged * $estimated_price['price_breakdown']['price_per_km_after_surge']) -
            $estimated_price['price_breakdown']['price_after_distance'];
        $estimated_price['price_breakdown']['price_after_surge'] =  ($distance_charged  * $estimated_price['price_breakdown']['price_per_km_after_surge']);

        // $estimated_price['price_breakdown']['surge']  = ($surge_rate * $estimated_price['price_breakdown']['price_after_distance']) - $estimated_price['price_breakdown']['price_after_distance'];
        // $estimated_price['price_breakdown']['price_after_surge'] = ($surge_rate * $estimated_price['price_breakdown']['price_after_distance']);



        //PRICE AFTER APP CHARGE
        $estimated_price['price_breakdown']['app_charge_percent'] = 10.0;   //Default::TO BE FETCHED from DB SETTINGS 
        $estimated_price['price_breakdown']['app_charge'] = floatval(number_format(($estimated_price['price_breakdown']['app_charge_percent'] / 100.0 * $estimated_price['price_breakdown']['price_after_surge']), 2));
        $estimated_price['price_breakdown']['price_after_app_charge']  =  $estimated_price['price_breakdown']['price_after_surge'] + $estimated_price['price_breakdown']['app_charge'];

        //PRICE AFTER DURATION CHARGE
        if ($vehicle_type->base_covers_duration == 'yes') {
            if (isset($old_duration) && $duration > $old_duration) {
                $estimated_price['price_breakdown']['price_per_min'] = 0;
                $estimated_price['price_breakdown']['price_per_min_after_base'] = $vehicle_type->price_per_min;
                $estimated_price['price_breakdown']['duration_charge'] =  ($duration - $old_duration)  * $duration / 60;
                $estimated_price['price_breakdown']['price_after_duration']  =  $estimated_price['price_breakdown']['price_after_app_charge'] + $estimated_price['price_breakdown']['duration_charge'];
            } else {
                $estimated_price['price_breakdown']['price_per_min'] = 0;
                $estimated_price['price_breakdown']['price_per_min_after_base'] = $vehicle_type->price_per_min;
                $estimated_price['price_breakdown']['duration_charge'] = 0;
                $estimated_price['price_breakdown']['price_after_duration']  =  $estimated_price['price_breakdown']['price_after_app_charge'];
            }
        } else {


            $estimated_price['price_breakdown']['price_per_min'] = $vehicle_type->price_per_min;
            $estimated_price['price_breakdown']['price_per_min_after_base'] = $vehicle_type->price_per_min;
            $estimated_price['price_breakdown']['duration_charge'] = ($vehicle_type->price_per_min * $duration / 60);
            $estimated_price['price_breakdown']['price_after_duration']  =  $estimated_price['price_breakdown']['price_after_app_charge'] + $estimated_price['price_breakdown']['duration_charge'];
        }

        $estimated_price['price_breakdown']['price_after_base_fare'] =
            $estimated_price['price_breakdown']['price_after_duration'] +  $estimated_price['price_breakdown']['base_fare'];

        $estimated_price['price_breakdown']['total_price'] =
            ($estimated_price['price_breakdown']['price_after_base_fare'] < $estimated_price['price_breakdown']['minimum_charge'])
            ? $estimated_price['price_breakdown']['minimum_charge'] : $estimated_price['price_breakdown']['price_after_base_fare'];

        $estimated_price['price_breakdown']['total_price'] = ceil($estimated_price['price_breakdown']['total_price']);

        $estimated_price = $this->getDiscountAmount($estimated_price, $user_id, $voucher);

    //    dd($estimated_price);
        return $estimated_price;
    }

    public function getDiscountAmount($estimated_price, $user_id, $voucher)
    {
        $estimated_price['price_breakdown']['promotion_voucher_id'] = null;
        $estimated_price['price_breakdown']['discount_amount'] = 0;
        $estimated_price['price_breakdown']['original_price'] =    $estimated_price['price_breakdown']['total_price'];
        if ($voucher) {


            $promotion_voucher = PromotionVoucher::where('code', $voucher)
                ->where('user_type', 'customer')
                ->where('status', 'active')
                ->whereRaw("starts_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now()->format('Y-m-d H:i'))
                ->whereRaw("expires_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now()->format('Y-m-d H:i'))
                ->whereRaw('uses < max_uses')
                ->first();
            $user = User::find($user_id);


            if ($promotion_voucher && $user) {
                //CHECK FOR VARIOUS ELIGIBILITY FACTORS OF THE VOUCHER 


                $used_promotion_vouchers = PriceDetail::whereHas('completed_trip', function (Builder $query) use ($user_id) {
                    $query->where('user_id', $user_id);
                    $query->whereStatus('completed');
                })->where('promotion_voucher_id', $promotion_voucher->id)->pluck('id', 'promotion_voucher_id');

                //Check if the voucher still has uses left for the user
                if (count($used_promotion_vouchers) < $promotion_voucher->max_uses_user) {
                    $user_travelled_distance = CompletedTrip::where('user_id', $user_id)->where('status', 'completed')->sum('distance'); //in meters
                    $user_spent_price = CompletedTrip::where('user_id', $user_id)->where('status', 'completed')->sum('price');

                    $price_eligibility_allowance = 0;
                    $distance_eligibility_allowance = 0;

                    if (isset($promotion_voucher->price_eligibility)) {
                        //dd('as',$promotion_voucher->price_eligibility);
                        foreach ($promotion_voucher->price_eligibility as $price_range) {
                            if ($user_spent_price >= $price_range['price'])
                                $price_eligibility_allowance = intval($price_range['worth']);
                        }
                    }
                    if (isset($promotion_voucher->distance_eligibility)) {
                        foreach ($promotion_voucher->distance_eligibility as $distance_range) {
                            if ($user_travelled_distance >= $distance_range['distance'])
                                $distance_eligibility_allowance = intval($distance_range['worth']);
                        }
                    }

                    $voucher_worth = $promotion_voucher->worth + $price_eligibility_allowance + $distance_eligibility_allowance;

                    if (isset($promotion_voucher->eligible_user_ids)) {
                        if (in_array($user_id, $promotion_voucher->eligible_user_ids)) {
                            //APPLY DISCOUNT
                            if (!$promotion_voucher->is_fixed) {
                                $estimated_price['price_breakdown']['discount_amount'] =
                                    $estimated_price['price_breakdown']['total_price'] * ($voucher_worth / 100);
                            } else {
                                $estimated_price['price_breakdown']['discount_amount'] = $voucher_worth;
                            }
                            $estimated_price['price_breakdown']['total_price'] =
                                $estimated_price['price_breakdown']['total_price'] - $estimated_price['price_breakdown']['discount_amount'];

                            $estimated_price['price_breakdown']['total_price'] = ($estimated_price['price_breakdown']['total_price'] >= 0) ?  $estimated_price['price_breakdown']['total_price'] : 0;
                            $estimated_price['price_breakdown']['promotion_voucher_id'] = $promotion_voucher->id;
                        } else {
                            //DO NOT APPLY DISCOUNT
                            $voucher_worth = 0;
                        }
                    } else {
                        //APPLY DISCOUNT
                        if (!$promotion_voucher->is_fixed) {
                            $estimated_price['price_breakdown']['discount_amount'] =
                                $estimated_price['price_breakdown']['total_price'] * ($voucher_worth / 100);
                        } else {
                            $estimated_price['price_breakdown']['discount_amount'] = $voucher_worth;
                        }
                        $estimated_price['price_breakdown']['total_price'] =
                            $estimated_price['price_breakdown']['total_price'] - $estimated_price['price_breakdown']['discount_amount'];

                        $estimated_price['price_breakdown']['total_price'] = ($estimated_price['price_breakdown']['total_price'] >= 0) ?  $estimated_price['price_breakdown']['total_price'] : 0;
                        $estimated_price['price_breakdown']['promotion_voucher_id'] = $promotion_voucher->id;
                    }
                }
            }
        }
        return $estimated_price;
    }

    public function calculate_final_price($vehicle_type_id, $old_estimated_price, $old_duration, $new_duration = 60)  //minimum 10 minutes duration in seconds
    {
        try {
            $price_per_min = VehicleType::find($vehicle_type_id)->price_per_min;

            $duration_to_be_charged = 0;

            if ($new_duration > $old_duration) {
                $duration_to_be_charged = $new_duration - $old_duration;
            }

            $duration_charge = ($price_per_min * $duration_to_be_charged / 60);
            $new_total_price = $old_estimated_price +  $duration_charge;

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
        // dd($origin_latitude, $origin_longitude, $vehicle_type_id);
        //$radius = !empty(config('settings.scan_radius')) ? floatval(config('settings.scan_radius')) : 20.0; //Radius in kilometers within origin center::To be fetched from DB SETTINGS
        return $this->rider_location_service->getNearbyAvailableRiders($origin_latitude, $origin_longitude, $vehicle_type_id);
    }

    /**
     * Fetches the pending bookings within the radius circle of certain origin latitude and longitude
     */
    public function get_nearby_pending_bookings($origin_latitude, $origin_longitude, $vehicle_type_id = null, $radius = null)
    {
        // dd($origin_latitude, $origin_longitude, $vehicle_type_id);
        return $this->rider_location_service->getNearbyAvailableUsers($origin_latitude, $origin_longitude, $vehicle_type_id);
    }


    public function notify_booking_timed_out($bookingId)
    {
      
            $booking = Booking::with('user:id,first_name,last_name,image')->where('id',$bookingId)->first();
            // $booking = Booking::find($bookingId);

                        // dd($booking->user->toArray());
            
            //Send pusher/echo broadcast notification to all admins
            $title = "Booking Timed Out" ;
            $message = "Booking request timed out by ".$booking->user->name.' made on '.$booking->created_at->toDayDateTimeString();;
            event(
                new BookingTimedOut( 
                    $title,
                    $message, 
                    $bookingId,
                    $booking->user->name
                )
                );

            //Create Notification sent via pusher broadcast
            $this->notification_service->create(
                [
                    'recipient_id'=>null,
                    'recipient_type'=>'admin',
                    'recipient_device_token'=>null,
                    'recipient_quantity_type'=>'all',
                    'notification_type'=>'customer_ignored',
                    'title'=> $title, 
                    'message'=> $message, 
                    'booking_id'=>$bookingId
                ]
            );

    }



    public function find($id)
    {
        return $this->booking->find($id);
    }
}
