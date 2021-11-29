<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Models\VehicleType;

class Shift extends Model
{
    use HasFactory;

    protected $casts = [
        'vehicle_type_id' => 'integer',
        'time_from' => 'integer',
        'time_to' => 'integer',
    ];

    protected $fillable = ([
        'vehicle_type_id', 'title',  'time_from', 'time_to', 'status'
    ]);

    protected $appends = [
        'time_text'
    ];

    function getTimeTextAttribute(){
        return $this->time_from. " - ".$this->time_to;
    }

    //Vehicle type of this shift
    public function vehicleType() {
        return $this->belongsTo(VehicleType::class);    //,'vehicle_type_id','id');
    }

}
