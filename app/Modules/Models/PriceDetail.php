<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

//models
use App\Modules\Models\Booking;
use App\Modules\Models\CompletedTrip;

class PriceDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $path = 'uploads/price_details';

    protected $casts = [
        'booking_id'=>'integer',
        'completed_trip_id'=>'integer',
        'minimum_charge'=>'float', 
        'price_per_km' =>'float' ,
        'price_after_distance' =>'float' , 
        'surge_rate' =>'float' ,  
        'surge' =>'float' , 
        'price_after_surge' =>'float' , 
        'app_charge_percent' =>'float' ,
        'app_charge' =>'float' , 
        'price_after_app_charge' =>'float' , 
        'price_per_min' =>'float' , 
        'duration_charge' =>'float' ,
        'price_after_duration' =>'float' , 
        'total_price' =>'float' ,
        ];

    protected $fillable = ['booking_id','completed_trip_id',
                        'minimum_charge', 'price_per_km','price_after_distance', 
                        'surge_rate',  'surge', 'price_after_surge', 'app_charge_percent',
                        'app_charge', 'price_after_app_charge', 'price_per_min', 'duration_charge',
                        'price_after_duration', 'total_price',
                        'deleted_at', 'updated_at','deleted_at'];


    protected $appends = [];

    public function booking(){
        return $this->belongsTo(Booking::class);
    }

    public function completed_trip(){
        return $this->belongsTo(CompletedTrip::class);
    }




}
