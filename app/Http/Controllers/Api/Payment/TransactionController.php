<?php

namespace App\Http\Controllers\Api\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//models
use App\Modules\Models\User;
use App\Modules\Models\Rider;
use App\Modules\Models\Booking;
use App\Modules\Models\CompletedTrip;
use App\Modules\Models\Payment;
use App\Modules\Models\Transaction;

//services
use App\Http\Modules\Services\User\UserService;
use App\Http\Modules\Services\User\BookingService;
use App\Http\Modules\Services\Payment\PaymentService;
use App\Http\Modules\Services\Transaction\TransactionService;
// use App\Http\Modules\Services\User\TransactionService;

class TransactionController extends Controller
{
    protected $payment, $user_service, $transaction_service;

    public function __construct(TransactionService $transaction, UserService $user_service)
    {
        $this->transaction = $transaction;
        $this->user_service = $user_service;
    }


    public function store(Request $request)
    {

    }


}
