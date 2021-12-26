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
        'base_fare'=>'float', 
        'base_covered_km'=>'integer',
        'minimum_charge'=>'float', 
        'price_per_km' =>'float' ,
        'price_after_distance' =>'float' , 
        'shift_surge' =>'float' ,  
        'density_surge' =>'float' ,  
        'surge_rate' =>'float' ,
        'price_per_km_after_surge' =>'float' ,  
        'surge' =>'float' , 
        'price_after_surge' =>'float' , 
        'app_charge_percent' =>'float' ,
        'app_charge' =>'float' , 
        'price_after_app_charge' =>'float' , 
        'price_per_min' =>'float' , 
        'duration_charge' =>'float' ,
        'price_after_duration' =>'float' , 
        'price_after_base_fare' =>'float' , 
        'total_price' =>'integer' ,
        ];

    protected $fillable = ['booking_id','completed_trip_id','base_fare','base_covered_km',
                        'minimum_charge', 'price_per_km','price_after_distance', 'shift_surge','density_surge',
                        'surge_rate','price_per_km_after_surge',  'surge', 'price_after_surge', 'app_charge_percent',
                        'app_charge', 'price_after_app_charge', 'price_per_min', 'duration_charge',
                        'price_after_duration', 'price_after_base_fare', 'total_price',
                        'deleted_at', 'updated_at','deleted_at'];


    protected $appends = [];

    public function booking(){
        return $this->belongsTo(Booking::class);
    }

    public function completed_trip(){
        return $this->belongsTo(CompletedTrip::class);
    }




}
