<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Models\SentNewsletters;

class Newsletter extends Model
{
    use HasFactory, SoftDeletes;


    protected $path = 'uploads/newsletter';

    protected $casts = [
        
    ];

    protected $fillable = ([
        'title', 'body', 'image', 'deleted_at', 'created_at', 'updated_at'
    ]);

    protected $appends = [
        'thumbnail_path', 'image_path',
    ]; 
    function getImagePathAttribute()
    {
        if ($this->image)
            return $this->path . '/'  . $this->image;
        else
            return 'assets/media/noimage.png';
    }

    function getThumbnailPathAttribute()
    {
        if ($this->image) 
        return $this->path .  '/' . $this->image;
            // return $this->path .  '/thumb/' . $this->image;
        else
            return 'assets/media/noimage.png';
    }


    public function ssent_newsletters()
    {
        return $this->hasMany(Newsletter::class);
    }


}
