<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Models\Booking;
use App\Modules\Models\User;
use App\Modules\Models\Rider;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'booking_id' => 'integer',
        'rider_id' => 'integer',
        'user_id' => 'integer',
        'rate' => 'float'
    ];

    protected $fillable = [
        'booking_id','rider_id','user_id', 'reviewed_by_role',   'rate','ride_date','comment',
        'deleted_at', 'created_at', 'updated_at'
    ];

    protected $appends = [];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function rider() {
        return $this->belongsTo(Rider::class);
    }

}
