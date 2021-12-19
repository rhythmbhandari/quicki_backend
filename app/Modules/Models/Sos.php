<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


//models
use App\Modules\Models\User;
use App\Modules\Models\Booking;

class Sos extends Model
{
    use HasFactory, SoftDeletes;
    protected $path = 'uploads/notification';

    protected $casts = [
        'created_by_id' => 'integer',
        'booking_id'=>'integer',
        'location'=>'array'
    ];

    protected $fillable = ([
        'created_by_id', 'created_by_type', 'booking_id', 'location','status','action_taken', 'message','title',
        'created_at', 'updated_at' ,'deleted_at'
    ]);


    protected $appends = [
        
    ];


    /**
     * Gets the user model of the creator of the sos!
     */
    public function creator_user(){
        return $this->belongsTo(User::class,'created_by_id');
    }

    /**
     * Gets the booking model of the sos!
     */
    public function booking(){
        return $this->belongsTo(Booking::class);
    }




}
