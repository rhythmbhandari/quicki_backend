<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Models\Rider;
use App\Modules\Models\User;
use App\Modules\Models\Location;
use App\Modules\Models\CompletedTrip;
use App\Modules\Models\VehicleType;
use App\Modules\Models\BookingPayment;
use App\Modules\Models\PriceDetail;
class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'stoppage'=>'array', 
        'stoppage.latitude' => 'float',
        'stoppage.longitude' => 'float',
        'user_id'=>'integer', 
        'rider_id'=>'integer',
        'vehicle_type_id'=>'integer',
        'location_id'=>'integer',
        'passenger_number'=>'integer',
        'distance'=>'integer',
        'duration'=>'integer',
        'price'=>'integer'
    ];

    protected $fillable = [
        'origin','destination','distance','duration','price','passenger_number', //'name','phone_number',
        'user_id', 'status', 'rider_id', 'status', 'location_id', 'vehicle_type_id', 'stoppage',
        'start_time','end_time',
        'updated_at','created_at','deleted_at'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function location() {
        return $this->belongsTo(Location::class);
    }
  
    public function rider() {
        return $this->belongsTo(Rider::class);
    }

    public function completed_trip() {
        return $this->hasOne(CompletedTrip::class);
    }


    public function vehicle_type()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function price_detail()
    {
        return $this->hasOne(PriceDetail::class);
    }

}
