<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;

use App\Modules\Models\PriceDetail;
use App\Modules\Models\Payment;

class PromotionVoucher extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $path = 'uploads/promotion_voucher';

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $casts = [
        'uses'=>'integer', 
        'max_uses'=>'integer',
        'max_uses_user'=>'integer',
        'worth'=>'integer',
        'is_fixed'=>'boolean',
        'eligible_user_ids'=>'array',
        'price_eligibility'=>'array',
        'distance_eligibility'=>'array',
        'starts_at'=>'timestamp',
        'expires_at'=>'timestamp',
    ];

    protected $fillable = ['name', 'slug', 'user_type', 'image','code',
        'description', 'uses', 'max_uses', 'max_uses_user', 'type', 'worth',
        'is_fixed', 'eligible_user_ids', 'price_eligibility', 'distance_eligibility',
        'starts_at', 'expires_at', 'status',
        'created_at', 'updated_at', 'deleted_at'];
    
    protected $appends = [
        'status_text', 'thumbnail_path', 'image_path', 'is_expired'
    ];

    function getIsExpiredAttribute(){
        //If current date is greater than expires at date of the voucher
        return  ( (Carbon::now())->gt( new Carbon($this->expires_at) ));
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
            return $this->path .  '/thumb/' . $this->image;
        else
            return 'assets/media/user_placeholder.png';
    }


    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function price_details()
    {
        return $this->hasMany(PriceDetail::class);
    }


}
