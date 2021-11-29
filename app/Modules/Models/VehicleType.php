<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;

use App\Modules\Models\Vehicle;
use App\Modules\Models\Shift;

class VehicleType extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $path = 'uploads/vehicle_type';

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    protected $casts = [
        'price_km' => 'integer',
        'price_min' => 'float',
        'base_fare' => 'integer',
        'commission' => 'integer',
        'capacity' => 'integer',
        'surge_rate'=>'float',
    ];

    //Here, rate is surge rate applies when booking falls either in density or shift surge criterion
    
    protected $fillable = [ 
        'name','slug','price_km','price_min','image', 'base_fare','commission','capacity','status','surge_rate',
        'created_at','updated_at','deleted_at'
    ];
    protected $appends = [
         'thumbnail_path', 'image_path', 'price_per_km', 'price_per_min'
    ];

    function getPricePerKmAttribute()
    {
        return $this->price_km;
    }


    function getPricePerMinAttribute()
    {
        return $this->price_min;
    }

    function getImagePathAttribute()
    {
        if ($this->image)
            return $this->path . '/' . $this->type . '/' . $this->image;
        else
            return 'assets/media/noimage.png';
    }

    function getThumbnailPathAttribute()
    {
        if ($this->image)
            return $this->path . '/' . $this->type . '/thumb/' . $this->image;
        else
            return 'assets/media/noimage.png';
    }

    //Vehicles of this type
    public function vehicles(){
        return $this->hasMany(Vehicle::class);
    }

    //Shifts for this vehicle type
    public function shifts(){
        return $this->hasMany(Shift::class);
    }
    // public function prices(){
    //     return $this->hasMany(Price::class);
    // }

}
