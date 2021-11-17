<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Modules\Models\Booking;
use App\Modules\Models\CompletedTrip;
class Location extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [  'longitude_origin','latitude_origin','longitude_destination','latitude_destination',
    'created_at','updated_at',
    ];


    public function booking() {
        return $this->hasOne(Booking::class);
    }
    public function completed_trip() {
        return $this->hasOne(CompletedTrip::class);
    }

}
