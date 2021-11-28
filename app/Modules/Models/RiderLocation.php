<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use App\Modules\Models\Rider;

class RiderLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $TIME_DIFFERENCE = 60;

    protected $casts = [
        'longitude' => 'float',
        'latitude' => 'float',
        'rider_id' => 'integer',
    ];


    protected $fillable = ([
        'longitude','latitude','rider_id','status',
        'created_at','updated_at', 'deleted_at'
    ]);

    protected $appends = [
        'availability'  
    ];

    public function getAvailabilityAttribute()
    {
        if($this->status == "active")
            return "available";
        else 
            return "not_available";

        /*
        //AVAILABLE if status is active and last updated was [TIME_DIFFERENCE] time ago
        $diffInSeconds = Carbon::now()->diffInSeconds(Carbon::parse($this->updated_at));
        if( ($diffInSeconds <= $this->TIME_DIFFERENCE) && $this->status == "active" )
        {
            // dd('available');
            return 'available';
        }
        else if( ($diffInSeconds > $this->TIME_DIFFERENCE) && $this->status == "active" )
        {
            // dd('not available 1');
            $this->status = "in_active";
            $this->save();
            return 'not_available';
        }
        else{
            // dd('not available2');
            return 'not_available';
        }
        */
    }

    public function rider() {
        return $this->belongsTo(Rider::class)->with('user')->with('vehicle')->with('reviews');  //,'rider_id');
    }



   


}
