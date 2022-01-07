<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

use App\Modules\Models\Rider;
use App\Modules\Models\User;
use App\Modules\Models\Sos;
// use App\Modules\Models\Location;
use App\Modules\Models\CompletedTrip;
use App\Modules\Models\VehicleType;
use App\Modules\Models\BookingPayment;
use App\Modules\Models\PriceDetail;
use App\Modules\Models\Notification;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'stoppage' => 'array',
        'stoppage.latitude' => 'float',
        'stoppage.longitude' => 'float',
        'user_id' => 'integer',
        'rider_id' => 'integer',
        'vehicle_type_id' => 'integer',
        'passenger_number' => 'integer',
        'distance' => 'integer',
        'duration' => 'integer',
        'price' => 'integer',
        'location' => 'array',
    ];

    protected $fillable = [
       'distance', 'duration', 'price', 'passenger_number', //'name','phone_number',
        'user_id', 'status', 'rider_id', 'status',  'vehicle_type_id', 'stoppage',
        'start_time', 'end_time','trip_id','location',
        'updated_at', 'created_at', 'deleted_at'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $appends = ['status_text','distance_km','duration_min'];

    // public function getCreatedAtUtcAttribute()
    // {
    //     return $this->created_at->format('Y-m-d H:i:s');
    // }

    public function getUpdatedAtUtcAttribute()
    {
        return $this->updated_at->format('Y-m-d H:i:s');
    }


    public function getDistanceKmAttribute()
    {
        return floatval($this->distance/1000);
    }

    public function getDurationMinAttribute()
    {
        return floatval($this->duration/60);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function location()
    // {
    //     return $this->belongsTo(Location::class);
    // }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    function getStatusTextAttribute()
    {
        return ucwords(str_replace('_', '', $this->status));
    }

    public function completed_trip()
    {
        return $this->hasOne(CompletedTrip::class);
    }

    public function sos()
    {
        return $this->hasMany(Sos::class);
    }


    public function vehicle_type()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function price_detail()
    {
        return $this->hasOne(PriceDetail::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
