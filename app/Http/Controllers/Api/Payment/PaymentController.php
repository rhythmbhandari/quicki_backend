<?php

namespace App\Http\Controllers\Api\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

//models
use App\Modules\Models\User;
use App\Modules\Models\Rider;
use App\Modules\Models\Booking;
use App\Modules\Models\CompletedTrip;
use App\Modules\Models\Payment;
use App\Modules\Models\Transaction;

//services
use App\Modules\Services\User\UserService;
use App\Modules\Services\User\BookingService;
use App\Modules\Services\Payment\PaymentService;
use App\Modules\Services\Payment\TransactionService;
// use App\Http\Modules\Services\User\TransactionService;

class PaymentController extends Controller
{
    protected $payment, $user_service, $transaction_service;

    public function __construct(PaymentService $payment, UserService $user_service,  TransactionService $transaction_service)
    {
        $this->payment = $payment;
        $this->user_service = $user_service;
        $this->transaction_service = $transaction_service;
    }



    public function store(Request $request)
    {
    }


    public function update(PaymentUpdateRequest $request)
    {

    }
    
    /**
    * @OA\Post(
    *   path="/api/payment/{payment_id}/offline_ride_payment",
    *   tags={"Payment"},
    *   summary="Booking Offine Payment",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="payment_id",
    *         in="path",
    *         description="Payment Id",
    *         required=true,
    *      ),
    *
    *
    *      @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                       "message": "Payment Updated Successflly!",
    *                         "payment": {"id": 5, "completed_trip_id": 5, "commission_amount": 15, "original_commission": null, "payment_status": "unpaid", "commission_payment_status": "unpaid", "deleted_at": null, "created_at": "2021-12-30T10:11:05.000000Z", "updated_at":" 2021-12-30T10:11:05.000000Z", "customer_payment_status": "unpaid","transactions":{}}
    *                   }
    *                 )
    *           )
    *      ),
    *
    *      @OA\Response(
    *          response=422,
    *          description="Validation Fail",
    *             @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="The ride has already been paid!",
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    public function offline_ride_payment($paymentId)
    {
        $payment = Payment::find($paymentId);
        if(!$payment)
        {
            $response = ['message' => 'Payment not found!'];
            return response($response, 404);
        }

        if($payment->customer_payment_status == "paid")
        {
            $response = ['message' => 'The ride has already been paid!', 'payment'=>$payment];
            return response($response, 400);
        }

        if( !$payment->completed_trip->rider_id )
        {
            $response = ['message' => 'Debtor not found for the transaction!', 'payment'=>$payment];
            return response($response, 400);
        }


        return DB::transaction(function () use ($paymentId)
        {
            
            $updatedPayment = $this->payment->offline_ride_payment($paymentId);

            if($updatedPayment)
            {
                $payment = Payment::where('id',$paymentId)->with('transactions')->first();
                $response = ['message' => 'Payment Updated Successflly!', "payment"=>$payment];
                return response($response, 200);
            }

            return response("Internal Server Error!", 500);
        });




    }





    /**
    * @OA\Get(
    *   path="/api/payment/{payment_id}",
    *   tags={"Payment"},
    *   summary="Get payment from payment id",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="payment_id",
    *         in="path",
    *         description="Payment Id",
    *         required=true,
    *      ),
    *
    *
    *      @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                       "message": "Success!",
    *                         "payment": {"id": 5, "completed_trip_id": 5, "commission_amount": 15, "original_commission": null, "payment_status": "unpaid", "commission_payment_status": "unpaid", "deleted_at": null, "created_at": "2021-12-30T10:11:05.000000Z", "updated_at":" 2021-12-30T10:11:05.000000Z", "customer_payment_status": "unpaid","transactions":{}}
    *                   }
    *                 )
    *           )
    *      ),
    *
    *      @OA\Response(
    *          response=422,
    *          description="Validation Fail",
    *             @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    public function getPayment($paymentId)
    {
        $payment = Payment::where('id',$paymentId)->with('transactions')->first();
        if(!$payment)
        {
            $response = ['message' => 'Payment not found!'];
            return response($response, 404);
        }
        $response = ['message' => 'Success!', 'payment'=>$payment];
            return response($response, 200);
    }


      /**
    * @OA\Get(
    *   path="/api/booking/{booking_id}/payment",
    *   tags={"Payment"},
    *   summary="Get Payment from booking id",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="booking_id",
    *         in="path",
    *         description="Booking Id",
    *         required=true,
    *      ),
    *
    *
    *      @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                       "message": "Success!",
    *                        "payment": {"id": 5, "completed_trip_id": 5, "commission_amount": 15, "original_commission": null, "payment_status": "unpaid", "commission_payment_status": "unpaid", "deleted_at": null, "created_at": "2021-12-30T10:11:05.000000Z", "updated_at":" 2021-12-30T10:11:05.000000Z", "customer_payment_status": "unpaid","transactions":{}}
    *                   }
    *                 )
    *           )
    *      ),
    *
    *      @OA\Response(
    *          response=422,
    *          description="Validation Fail",
    *             @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    public function getPaymentFromBooking($booking_id)
    {
        $booking = Booking::find($booking_id);
        if(!$booking)
        {
            $response = ['message' => 'Booking not found!'];
            return response($response, 404);
        }
        if($booking->status != 'completed')
        {
            $response = ['message' => 'Booking has not been completed!'];
            return response($response, 400);
        }

        $payment = $booking->completed_trip->payment;

        if(!$payment)
        {
            $response = ['message' => 'Payment not found!'];
            return response($response, 404);
        }
        $response = ['message' => 'Success!', 'payment'=>$payment];
            return response($response, 200);
    }

}
