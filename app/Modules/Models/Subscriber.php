<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Subscriber extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $casts = [
        'subscribed'=>'boolean'
    ];

    protected $fillable = ([
        'email', 'subscribed', 'deleted_at', 'created_at', 'updated_at'
    ]);

    protected $appends = [
        
    ];


}
