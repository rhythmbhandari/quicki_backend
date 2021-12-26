<?php

namespace App\Modules\Services\Payment;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

//models
use App\Modules\Models\Payment;
use App\Modules\Models\Transaction;

//services


class PaymentService extends Service
{
    protected $payment, $transaction_service;

    function __construct(Payment $payment, TransactionService $transaction_service)
    {
        $this->payment = $payment;
        $this->transaction_service = $transaction_service;
    }

    function getPayment(){
        return $this->payment;
    }

    
    function create(array $data)
    {
        try{

            $data['payment_status']
             =  ( isset($data['payment_status']) && in_array($data['payment_status'],['paid','unpaid']) ) ? $data['payment_status'] : 'unpaid';
            $data['commission_payment_status']
             =  ( isset($data['commission_payment_status']) && in_array($data['commission_payment_status'],['paid','unpaid']) ) ? $data['commission_payment_status'] : 'unpaid';

            $data['commission_amount'] = floatval($data['commission_amount']);
            $data['completed_trip_id'] = intval($data['completed_trip_id']);
           
            $createdPayment = $this->payment->create($data);
            if($createdPayment)
            {
                return $createdPayment;
            }
            return NULL;
        }
        catch(Exception $e)
        {
            return NULL;
        }
    }


    function offline_ride_payment($paymentId)
    {
        $payment = Payment::find($paymentId);

        try{
            $completed_trip = $payment->completed_trip;
            //Create transaction table
            $createdTransaction = $this->transaction_service->create([
                'amount'=>$completed_trip->price,
                'transaction_date'=> Carbon::now(),
                'creditor_type'=>'customer',
                'creditor_id'=>$completed_trip->user_id,
                'debtor_type'=>'rider',
                'debtor_id'=>$completed_trip->rider_id,
                'payment_mode'=>'offline'
            ]);


            //Update payment table
            if($createdTransaction)
            {
                $payment->payment_status = 'paid';
                $payment->save();
                return true;
            }

            return false;
        }
        catch(Exception $e)
        {
            return false;
        }

    }


}
