<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Permission\Traits\HasPermissions;

use App\Modules\Models\User;
use App\Modules\Models\Permission;


class Role extends Model
{   
     use SoftDeletes, Sluggable, HasPermissions;

    protected $path = 'uploads/role';

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $fillable = [ 'name', 'slug','created_at','updated_at','deleted_at' ];
    protected $appends = [  ];

    /**
     * Returns the users having this role.
     */
    public function users(){
        // return $this->hasMany(UserRole::class);
        return $this->belongsToMany(User::class);
    }


    // public function permissions(){
    //     // return $this->hasMany(UserRole::class);
    //     return $this->belongsToMany(Permission::class);
    // }

}
