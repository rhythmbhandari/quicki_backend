<?php

namespace App\Http\Controllers\Admin\Payment;

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
use App\Modules\Services\User\UserService;
use App\Modules\Services\User\BookingService;
use App\Modules\Services\Payment\PaymentService;
use App\Modules\Services\Payment\TransactionService;
// use App\Http\Modules\Services\User\TransactionService;

class TransactionController extends Controller
{
    protected $payment, $user_service, $transaction_service;

    public function __construct(TransactionService $transaction, UserService $user_service)
    {
        $this->transaction = $transaction;
        $this->user_service = $user_service;
    }


    public function index()
    {
        return view('admin.transaction.index');
    }
    public function store(Request $request)
    {
    }

    public function getAllData(Request $request)
    {
        return $this->transaction->getAllData($request);
    }

}
