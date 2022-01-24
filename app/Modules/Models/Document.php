<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{    
    use HasFactory, SoftDeletes;

    protected $path = 'uploads/document';

    protected $casts = [
        'documentable_id' => 'integer'
    ];

    protected $fillable = ([
        'documentable_id', 'documentable_type', 'document_number', 'type', 'issue_date', 'expiry_date', 'image', 'name',
        'verified_at','reason',
        'created_at', 'updated_at' ,'deleted_at'
    ]);

    protected $appends = [
        'thumbnail_path', 'image_path', 'document_for'
    ];

    function getDocumentForAttribute()
    {
        if(str_contains( strtolower($this->documentable_type) , "rider"  ))
            return "rider";
        else  if(str_contains( strtolower($this->documentable_type) , "user"  ))
            return "user";
        else if(str_contains( strtolower($this->documentable_type) , "vehicle"  ))
            return "vehicle";
        else 
            return $this->documentable_type;
    }

    function getImagePathAttribute()
    {
        if ($this->image)
            return $this->path . '/' . $this->image;
        else
            return 'assets/media/noimage.png';
    }

    function getThumbnailPathAttribute()
    {
        if ($this->image)
            return $this->path . '/thumb/' . $this->image;
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
