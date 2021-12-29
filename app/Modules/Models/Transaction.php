<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Models\Payment;
use App\Modules\Models\PaymentTransaction;
use App\Modules\Models\PromotionVoucher;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $path = 'uploads/transaction';

    
    protected $casts = ['amount'=>'float',
                        'payment_gateway_transaction_amount'=>'float',
                        'creditor_id'=>'integer',
                        'debtor_id'=>'integer',
                        'promotion_voucher_id'=>'integer',
                        ];

    protected $fillable = ['transaction_date','amount','creditor_type','creditor_id','debtor_type','debtor_id','payment_mode', 'promotion_voucher_id',
                            'payment_gateway_type','payment_gateway_user_id','payment_gateway_transaction_amount','payment_gateway_transaction_id',
                            'type','description',
                            'created_at','updated_at','deleted_at'];

    protected $appends = [  'is_fined'  ];

    public function getIsFinedAttribute()
    {
        return ($this->status == 'fine');
    }
    /**
     * Returns the booking payments associated with this transaction.
     */
    public function payments(){
        return $this->belongsToMany(Payment::class)->withTimestamps();
    }

/*
     * Get the creditor of this transaction.
     */
    public function creditor(){
        return $this->belongsTo(User::class, 'creditor_id');
    }

    /**
     * Get the debtor for this transaction.
     */
    public function debtor(){
        return $this->belongsTo(User::class, 'debtor_id');
    }

  
    public function promotion_voucher()
    {
        return $this->belongsTo(PromotionVoucher::class);
    }


}
