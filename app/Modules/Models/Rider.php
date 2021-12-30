<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

use App\Modules\Models\Vehicle;
use App\Modules\Models\RiderLocation;
use App\Modules\Models\CompletedTrip;
use App\Modules\Models\User;
use App\Modules\Models\Booking;
use App\Modules\Models\Review;
use App\Modules\Models\Document;

class Rider extends Model
{
    use HasFactory, SoftDeletes;
    protected $path = 'uploads/rider';

    protected $casts = [
        'user_id' => 'integer',
    ];

    protected $fillable = [
        'user_id', 'experience', 'trained', 'status',  'device_token',
        'approved_at', 'created_at', 'deleted_at', 'updated_at', 'last_updated_by', 'last_deleted_by'
    ];

    protected $appends = ['status_text'];

    //User model of the rider
    public function user()
    {
        return $this->belongsTo(User::class);   //,'user_id','id');
    }


    function getStatusTextAttribute()
    {
        return ucwords(str_replace('_', '', $this->status));
    }
    //Vehicle belonging to the rider
    public function vehicle()
    {
        return $this->hasOne(Vehicle::class)->with('documents')->with('vehicle_type:id,name,image');
    }

    //Currently active or last active location of the rider
    public function rider_location()
    {
        return $this->hasOne(RiderLocation::class);
    }

    //Bookings involving the rider
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    //CompletedTrips involving the rider
    public function completed_trips()
    {
        return $this->hasMany(CompletedTrip::class);
    }

    //Reviews involving the rider --> Returns both reviews made by and made for this rider
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the documents for the rider.
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
