<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

use App\Modules\Models\Booking;
use App\Modules\Models\Rider;
use App\Modules\Models\User;
use App\Modules\Models\Location;

class CompletedTrip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ([
        'origin','destination','location_id','profile_img_user','profile_img_rider','book_status',
        'user_id','rider_id','booking_id','cancelled_by','cancel_message',
        'deleted_at','created_at','updated_at'
    ]);

    public function location(){
        return $this->hasOne(Location::class);//,'location_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function rider(){
        return $this->belongsTo(User::class,'rider_id');
    }
    public function booking(){
        return $this->belongsTo(Booking::class);//,'book_id');
    }

}
