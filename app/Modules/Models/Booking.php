<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Models\Rider;
use App\Modules\Models\User;
use App\Modules\Models\Location;
use App\Modules\Models\CompletedTrip;
class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = ['stoppage'=>'array'];

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

}
