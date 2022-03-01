<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Otp extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ([
        'phone', 'code', 'code_status', 'deleted_at'
    ]);


}
