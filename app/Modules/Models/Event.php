<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//models
use App\Modules\Models\User;
use App\Modules\Models\Sos;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    protected $path = 'uploads/notification';


    protected $casts = [
        'sos_id' => 'integer',
        'created_by_id'=>'integer'
    ];

    protected $fillable = ([
        'created_by_id', 'created_by_type', 'message', 'sos_id',
        'created_at', 'updated_at' ,'deleted_at'
    ]);

    protected $appends = [
       
    ];

   
    /**
     * Gets the user model of the creator of the sos event!
     */
    // public function creator_user(){
    //     return $this->belongsTo(User::class,'created_by_id');
    // }

     /**
     * Gets the sos model of the sos event!
     */
    public function sos(){
        return $this->belongsTo(Sos::class);
    }


}
