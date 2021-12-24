<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Model;

use App\Modules\Models\User;
use App\Modules\Models\Permissions;


class Role extends Model
{
    use HasFactory;
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

    protected $fillable = ['name', 'slug', 'created_at', 'updated_at', 'deleted_at'];
    protected $appends = [];

    /**
     * Returns the users having this role.
     */
    public function users()
    {
        // return $this->hasMany(UserRole::class);
        return $this->belongsToMany(User::class);
    }


    public function permissions()
    {
        // return $this->hasMany(UserRole::class);
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }
}
