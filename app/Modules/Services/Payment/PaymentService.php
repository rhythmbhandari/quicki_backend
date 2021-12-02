<?php

namespace App\Modules\Services\Payment;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\DB;

//models
use App\Modules\Models\Payment;
use App\Modules\Models\Transaction;

class PaymentService extends Service
{
    protected $payment;

    function __construct(Payment $payment)
    {
        $this->payment = $payment;
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

}
