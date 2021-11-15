<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Models\Rider;

class RiderLocation extends Model
{
    use HasFactory;

    protected $fillable = ([
        'longitude','latitude','rider_id','status',
        'created_at','updated_at'
    ]);

    public function rider() {
        return $this->belongsTo(Rider::class);  //,'rider_id');
    }

}
