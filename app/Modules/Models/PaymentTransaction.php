<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Modules\Models\Payment;
use App\Modules\Models\Transaction;

class PaymentTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
                        'payment_id'=>'integer',
                        'transaction_id'=>'integer'
                        ];

    protected $fillable = ['payment_id','transaction_id','created_at','updated_at','deleted_at'];

    /**
     * Returns the transaction.
     */
    public function transaction(){
        return $this->belongsTo(Transaction::class);        //,'id', 'user_id');
    }
    /**
     * Returns the completed trip payment.
     */
    public function payment(){
        return $this->belongsTo(Payment::class);
    }

}
