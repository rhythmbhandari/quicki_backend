<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use App\Modules\Models\Rider;

class RiderLocation extends Model
{
    use HasFactory;

    protected $TIME_DIFFERENCE = 30;

    protected $fillable = ([
        'longitude','latitude','rider_id','status',
        'created_at','updated_at'
    ]);

    protected $appends = [
        'availability'  
    ];

    public function getAvailabilityAttribute()
    {
        //AVAILABLE if status is active and last updated was [TIME_DIFFERENCE] time ago
        $diffInSeconds = Carbon::now()->diffInSeconds(Carbon::parse($this->updated_at));
        if( ($diffInSeconds <= $this->TIME_DIFFERENCE) && $this->status == "active" )
        {
            return 'available';
        }
        else if( ($diffInSeconds > $this->TIME_DIFFERENCE) && $this->status == "active" )
        {
            $this->status = "in_active";
            $this->save();
            return 'not_available';
        }
        else
            return 'not_available';
    }

    public function rider() {
        return $this->belongsTo(Rider::class)->with('user')->with('vehicle')->with('reviews');  //,'rider_id');
    }

}
