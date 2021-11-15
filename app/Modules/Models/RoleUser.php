<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Models\User;
use App\Modules\Models\Role;

class RoleUser extends Model
{
    use HasFactory;    
    protected $table = 'role_user';

    protected $fillable = ['user_id', 'role_id',  'created_at','updated_at'];
    protected $appends = [];

    /**
     * Returns the user.
     */
    public function user(){
        return $this->belongsTo(User::class);        //,'id', 'user_id');
    }
    /**
     * Returns the role.
     */
    public function role(){
        return $this->belongsTo(Role::class);
    }
}
