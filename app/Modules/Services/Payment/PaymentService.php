<?php

namespace App\Modules\Services\Payment;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Carbon;


//models
use App\Modules\Models\Payment;
use App\Modules\Models\Transaction;
use App\Modules\Models\Rider;
use App\Modules\Models\PaymentTransaction;

//services


class PaymentService extends Service
{
    protected $payment, $transaction_service;

    function __construct(Payment $payment, TransactionService $transaction_service)
    {
        $this->payment = $payment;
        $this->transaction_service = $transaction_service;
    }

    /*For DataTable*/
    public function getAllData()
    {

        $query = $this->payment->orderBy('created_at', 'DESC')->get();
        // $count = count($query);
        // dd($query);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('comission_amount', function (Payment $payment) {
                return number_format($payment->comission_amount, 2);
            })
            ->editColumn('customer_name', function (Payment $payment) {
                return $payment->completed_trip->user->name;
            })
            ->editColumn('rider_name', function (Payment $payment) {
                $status = $payment->payment_status;
                $btn = '<button type="button"  class="btnShowStatusModal nav-icon btn btn-icon-toggle  bg p-0  "
            data-toggle="modal" data-theme="dark"  title="Change Status" data-status="' . $payment->payment_status . '"  data-target="#statusModal" id="btnShowStatusModal"
            data-slug="' . $payment->slug . '" data-id="' . $payment->id . '" data-status_type ="payment" >';

                return $btn . '<span class="' . getLabel($status) . '" >' . $status . '</span> </button>';
            })
            ->editColumn('payment_number', function (Payment $payment) {
                return $payment->payment_number;
            })
            ->editColumn('user_name', function (Payment $payment) {
                return $payment->user->name;
            })
            ->editColumn('user_email', function (Payment $payment) {
                return $payment->user->email;
            })

            ->editColumn('actions', function (Payment $payment) {
                $editRoute = route('admin.payment.edit', $payment->slug);
                $deleteRoute = route('admin.payment.destroy', $payment->slug);
                $showRoute = route('admin.payment.show', $payment->slug);
                $visibilityRoute = '';  // route('admin.product.visibility',$product->slug);
                $statusRoute = ''; //route('admin.product.status',$product->slug);
                $optionRoute = '';
                $optionRouteText = '';
                $optionalMenuText = 'payment';
                return getTableHtml($payment, 'actions', $editRoute, $deleteRoute, $showRoute, $visibilityRoute, $statusRoute, $optionRoute, $optionalMenuText);
            })
            ->addColumn('expand', function (Payment $payment) {
                return '<a href="#" class="details-control p-0 btn "><i class=" flaticon2-plus-1 text-danger"></i></a>';
            })
            ->addColumn('details_url', function (Payment $payment) {
                return route('admin.payment.row-details-data', $payment->id);
            })
            ->rawColumns(['total', 'user_name', 'order_number', 'user_email', 'user_image', 'expand', 'order_status', 'payment_status'])
            ->make(true);
    }

    function clearRiderCommission($rider)
    {
        $due_payments = Payment::whereIn('completed_trip_id', $rider->completed_trips->pluck('id'))->where('commission_payment_status', 'unpaid')->get();

        foreach ($due_payments as $due) {
            $due['commission_payment_status'] = 'paid';
            $due->save();
        }
    }


    function create(array $data)
    {
        try {

            $data['payment_status']
                =  (isset($data['payment_status']) && in_array($data['payment_status'], ['paid', 'unpaid'])) ? $data['payment_status'] : 'unpaid';
            $data['commission_payment_status']
                =  (isset($data['commission_payment_status']) && in_array($data['commission_payment_status'], ['paid', 'unpaid'])) ? $data['commission_payment_status'] : 'unpaid';

            $data['commission_amount'] = floatval($data['commission_amount']);
            $data['completed_trip_id'] = intval($data['completed_trip_id']);

            $createdPayment = $this->payment->create($data);
            if ($createdPayment) {
                return $createdPayment;
            }
            return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    function offline_ride_payment($paymentId)
    {
        $payment = Payment::find($paymentId);

        try {
            $completed_trip = $payment->completed_trip;

            $debtor_id = isset($completed_trip->rider_id) ? Rider::select('user_id')->where('id', $completed_trip->rider_id)->first()->user_id : $completed_trip->rider_id;

            //Create transaction table
            $createdTransaction = $this->transaction_service->create([
                'amount' => $completed_trip->price,
                'transaction_date' => Carbon::now(),
                'creditor_type' => 'customer',
                'creditor_id' => $completed_trip->user_id,
                'debtor_type' => 'rider',
                'debtor_id' => $debtor_id,
                'payment_mode' => 'offline'
            ]);


            //Update payment table
            if ($createdTransaction) {
                // PaymentTransaction::create([
                //     'payment_id'=>$paymentId,
                //     'transaction_id'=>$createdTransaction->id
                // ]);
                $payment->transactions()->attach($createdTransaction->id);
                $payment->payment_status = 'paid';
                $payment->save();
                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
