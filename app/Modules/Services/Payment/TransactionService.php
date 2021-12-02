<?php

namespace App\Modules\Services\Payment;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\DB;

//models
use App\Modules\Models\Transaction;
use App\Modules\Models\Booking;

class TransactionService extends Service
{
    protected $transaction;

    function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    function getTransaction(){
        return $this->transaction;
    }


    function getAllTransactions(){
        return Transaction::all();
    }

    
    function create(array $data)
    {
        try{
            //payment_gateway_user_id
            //transaction date
            $data['payment_mode']
             =  ( isset($data['payment_mode']) && in_array($data['payment_mode'],['online','offline']) ) ? $data['payment_mode'] : 'offline';
           
            $data['creditor_id'] = intval($data['creditor_id']);
            $data['debtor_id'] =  intval($data['debtor_id']);
            //creditor type
            //debtor type
            
            $data['payment_gateway_transaction_amount'] 
                =  ( $data['payment_mode'] == 'online'  ) ? floatval($data['payment_gateway_transaction_amount']) : null ;
            
            $data['amount']
                =  ( $data['payment_mode'] == 'online' ) ? floatval($data['payment_gateway_transaction_amount']) :  floatval($data['amount']);

            $createdTransaction = $this->transaction->create($data);
            if($createdTransaction)
            {
                return $createdTransaction;
            }
            return NULL;
        }
        catch(Exception $e)
        {
            return NULL;
        }
    }

}
