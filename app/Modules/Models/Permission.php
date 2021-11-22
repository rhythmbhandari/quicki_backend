<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

use App\Modules\Models\User;
use App\Modules\Models\Role;

class Permission extends Model
{   
     use SoftDeletes, Sluggable;

    protected $path = 'uploads/permission';

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $fillable = [ 'name', 'slug', 'guard_name',  'created_at', 'deleted_at', 'updated_at','deleted_at' ];
    protected $appends = [  ];

    /**
     * Returns the users having this role.
     */
    public function users(){
        // return $this->hasMany(UserRole::class);
        return $this->belongsToMany(User::class);
    }


    // public function roles(){
    //     // return $this->hasMany(UserRole::class);
    //     return $this->belongsToMany(Role::class);
    // }

}
