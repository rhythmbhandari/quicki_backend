<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//models
use App\Modules\Models\User;

class Notification extends Model
{
    use HasFactory, SoftDeletes;
    protected $path = 'uploads/notification';

    protected $casts = [
        'recipient_id' => 'integer',
    ];

    /**Allowed values for notification_types: 
     * push_notification => Simple Notification without any payload
     * booking_paid => Interpreted in: Rider or Customer Apps, Should trigger the invoice of the rider booking by fetching the latest completed trip!
     * rider_accepted => Interpreted in: Customer App, Should fetch the latest booking details!
     * 
     */

    protected $fillable = ([
        'recipient_id', 'recipient_type', 'recipient_device_token', 'notification_type','read_at', 'message','image','title',
        'created_at', 'updated_at' ,'deleted_at'
    ]);

    protected $appends = [
        'thumbnail_path', 'image_path', 
    ];

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
     * Gets the user model of the recipient!
     */
    public function recipient_user(){
        return $this->belongsTo(User::class,'recipient_id');
    }



}