<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'subscribed'=>'boolean'
    ];

    protected $fillable = ([
        'email', 'subscribed', 'deleted_at', 'created_at', 'updated_at'
    ]);

    protected $appends = [
        
    ];


}
