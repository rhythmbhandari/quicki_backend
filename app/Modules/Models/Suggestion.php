<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Suggestion extends Model
{
    use HasFactory, Sluggable;

    protected $path = 'uploads/suggestion';

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $fillable = ['text','slug','image',  'type','category'];

    protected $appends = [   'thumbnail_path', 'image_path' ];

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


}
