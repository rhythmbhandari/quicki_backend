<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Models\Booking;
use App\Modules\Models\Rider;
use App\Modules\Models\User;
// use App\Modules\Models\Location;
use App\Modules\Models\Payment;
use App\Modules\Models\PriceDetail;

class CompletedTrip extends Model
{
    use HasFactory, SoftDeletes;


    protected $casts = [
        'stoppage'=>'array',
        'user_id'=>'integer',
        'rider_id'=>'integer',
        'booking_id'=>'integer',
        'cancelled_by_id'=>'integer',
        'distance'=>'integer',
        'duration'=>'integer',
        'price'=>'integer',
        'location'=>'array'
    ];
    
    protected $fillable = ([
       'profile_img_user','profile_img_rider','status', 'distance','duration','stoppage',
        'user_id','rider_id','booking_id','cancelled_by_type', 'cancelled_by_id' ,'cancel_message', 'start_time','end_time',
        'price','payment_type','location',
        'deleted_at','created_at','updated_at'
    ]);

    protected $appends = ['distance_km','duration_min'];

    public function getDistanceKmAttribute()
    {
        return floatval($this->distance/1000);
    }

    public function getDurationMinAttribute()
    {
        return floatval($this->duration/60);
    }

    // public function location(){
    //     return $this->belongsTo(Location::class);//,'location_id');
    // }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function rider(){
        return $this->belongsTo(Rider::class)->with('vehicle');
    }
    public function booking(){
        return $this->belongsTo(Booking::class);//,'book_id');
    }


    /**
     * Returns the associated booking payment.
     */
    public function payment(){
        return $this->hasOne(Payment::class);
    }

    
    public function price_detail()
    {
        return $this->hasOne(PriceDetail::class);
    }



}
