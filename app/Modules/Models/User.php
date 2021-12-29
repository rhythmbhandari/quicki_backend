<?php

namespace App\Modules\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;


use App\Modules\Models\Rider;
use App\Modules\Models\Review;
use App\Modules\Models\Role;
use App\Modules\Models\Document;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use App\Modules\Models\Transaction;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Sluggable, HasRoles, HasPermissions;

    protected $path = 'uploads/user';

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name', 'middle_name', 'last_name',   'email', 'username', 'password', 'slug', 'status',
        'image', 'phone', 'email_verified_at', 'no_of_logins', 'last_logged_in', 'avatar', 'dob', 'emergency_contacts',
        'google_id', 'facebook_id', 'image', 'device_token', 'social_image_url', 'location', 'gender',
        'created_at', 'updated_at', 'deleted_at', 'last_updated_by', 'last_deleted_by'
    ];
    protected $appends = ['name', 'status_text', 'thumbnail_path', 'image_path'];

    function getNameAttribute()
    {
        return $this->first_name . ( isset($this->middle_name) ? (' '.$this->middle_name) : ' ' ) . $this->last_name;
    }

    function getImagePathAttribute()
    {
        if ($this->image)
            return $this->path . '/'  . $this->image;
        else
            return 'assets/media/user_placeholder.png';
    }

    function getThumbnailPathAttribute()
    {
        if ($this->image) 
        return $this->path .  '/' . $this->image;
            // return $this->path .  '/thumb/' . $this->image;
        else
            return 'assets/media/user_placeholder.png';
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',  'emergency_contacts' => 'array', 'location' => 'array'
    ];

    // public function getNameAttribute()
    // {
    //     return ucwords($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    // }


    /**
     * Returns the users having this role.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimeStamps();
    }

    function getStatusTextAttribute()
    {
        return ucwords(str_replace('_', '', $this->status));
    }

    //Rider model of the user if any
    public function rider()
    {
        return $this->hasOne(Rider::class)->with('vehicle')->with('documents');
    }

    //Reviews involving the user --> Returns both reviews made by and made for this user
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the documents for the user.
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Returns the transactions that are received by this user.
     */
    public function received_transactions()
    {
        return $this->hasMany(Transaction::class, 'debtor_id');
    }

    /**
     * Returns the transactions that are paid by this user.
     */
    public function paid_transactions()
    {
        return $this->hasMany(Transaction::class, 'creditor_id');
    }
}
