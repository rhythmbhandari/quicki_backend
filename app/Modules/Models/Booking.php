<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Models\Rider;
use App\Modules\Models\User;
use App\Modules\Models\Location;
class Booking extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'origin','destination','distance','duration','passenger_number', //'name','phone_number',
        'user_id', 'status', 'rider_id', 'ride_status', 'location_id', 'vehicle_type_id',
        'updated_at','created_at','deleted_at'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function location() {
        return $this->hasOne(Location::class);
    }
  
    public function rider() {
        return $this->belongsTo(User::class);
    }
}
