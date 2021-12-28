<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use App\Modules\Models\PaymentTransaction;
use App\Modules\Models\Transaction;
use App\Modules\Models\CompletedTrip;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $path = 'uploads/payment';

    protected $casts = [    
                        'commission_amount'=>'float',
                        'completed_trip_id'=>'integer'
                        ];

    protected $fillable = [ 'commission_amount', 'payment_status','commission_payment_status','completed_trip_id',
                            'created_at','updated_at','deleted_at'];

    protected $appends = [ 'customer_payment_status'   ];


    /**
     * Returns the transactions associated with this booking payment.
     */
    public function transactions(){
        return $this->belongsToMany(Transaction::class)->withTimestamps();
    }

    /**
     * Returns the associated completed trip.
     */
    public function completed_trip(){
        return $this->belongsTo(CompletedTrip::class);
    }

    /**
     * Returns the associated booking.
     */
    // public function booking(){
    //     return $this->belongsTo(CompletedTrip::class);
    // }

    public function getCustomerPaymentStatusAttribute(){
        return ( $this->payment_status == "paid"  ||  $this->commission_payment_status == "paid" ) ? "paid" : "unpaid";
    }


}
