<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


// use App\Modules\Models\Booking;
// use App\Modules\Models\CompletedTrip;
class Location extends Model
{
    use HasFactory, SoftDeletes;


    protected $casts = [
        'latitude_origin' => 'float',
        'longitude_origin' => 'float',
        'longitude_destination' => 'float',
        'latitude_destination' => 'float',
    ];

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [  'longitude_origin','latitude_origin','longitude_destination','latitude_destination',
    'created_at','updated_at', 'deleted_at'
    ];
    // protected $casts = [  'latitude_origin'=>'decimal:2,10', 
    //                         'longitude_origin'=>'decimal:2,10', 
    //                         'longitude_destination'=>'decimal:2,10', 
    //                         'latitude_destination'=>'decimal:2,10', 
    //                     ];

    // public function booking() {
    //     return $this->hasOne(Booking::class);
    // }
    // public function completed_trip() {
    //     return $this->hasOne(CompletedTrip::class);
    // }

}
