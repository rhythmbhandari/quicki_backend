<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{    
    use HasFactory, SoftDeletes;

    protected $path = 'uploads/document';

    protected $fillable = ([
        'documentable_id', 'documentable_type', 'document_number', 'type', 'issue_date', 'expire_date', 'image', 
        'verified_at','reason',
        'created_at', 'updated_at' ,'deleted_at'
    ]);

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

     /**
     * Get the model that the document belongs to!,{ user, or rider, or vehicle, etc}
     */
    public function documentable()
    {
        return $this->morphTo(__FUNCTION__, 'documentable_type', 'documentable_id');
    }

}
