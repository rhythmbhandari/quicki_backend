<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

use App\Modules\Models\Rider;
use App\Modules\Models\VehicleType;
use App\Modules\Models\Document;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $path = 'uploads/vehicle';

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'vehicle_number'
            ]
        ];
    }

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [ 'slug', 'rider_id', 'vehicle_type_id', 'image',
    'vehicle_number', 'make_year', 'vehicle_color', 'brand','model','status', 
    'created_at','updated_at','deleted_at', 'last_updated_by','last_deleted_by'
    ];
 
    protected $appends = [
          'thumbnail_path', 'image_path'
    ];

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
    
    //Vehicle belonging to the rider
    public function vehicle(){
        return $this->belongsTo(Rider::class);
    }

    //Vehicle belonging to the rider
    public function vehicle_type(){
        return $this->belongsTo(VehicleType::class); //, 'vehicle_type_id', 'id');
    }


    /**
     * Get the documents for the rider.
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

}
