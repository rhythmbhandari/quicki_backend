<?php

namespace App\Modules\Services\Payment;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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

    function getTransaction()
    {
        return $this->transaction;
    }

    public function getAllData($request)
    {
        $query = $this->transaction->with('creditor', 'debtor')->where('deleted_at', null);
        return DataTables::of($query)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                return $instance->when($request->has('creditor_id') && $request->creditor_id != null, function ($query) use ($request) {
                    return $query->where('creditor_id', $request->creditor_id);
                })->when($request->has('creditor_type') && $request->creditor_type != null, function ($query) use ($request) {
                    return $query->where('creditor_type', $request->creditor_type);
                })->when($request->has('datefilter') && $request->datefilter != null, function ($query) use ($request) {
                    $date = explode(" - ", $request->datefilter);
                    return $query->whereBetween('created_at', $date);
                });
            }, true)
            ->editColumn('transaction_date', function (Transaction $transaction) {
                return $transaction->transaction_date;
            })
            ->addColumn('creditor_first_name', function (Transaction $transaction) {
                return $transaction->creditor->first_name;
            })
            ->addColumn('creditor_last_name', function (Transaction $transaction) {
                return $transaction->creditor->last_name;
            })
            ->addColumn('creditor', function () {
                return 'N/A';
            })
            ->editColumn('creditor_type', function (Transaction $transaction) {
                return $transaction->creditor_type;
            })
            ->addColumn('debtor_first_name', function (Transaction $transaction) {
                return $transaction->debtor->first_name;
            })
            ->addColumn('debtor_last_name', function (Transaction $transaction) {
                return $transaction->debtor->last_name;
            })
            ->addColumn('debtor', function () {
                return 'N/A';
            })
            ->editColumn('debtor_type', function (Transaction $transaction) {
                return $transaction->debtor_type;
            })
            ->editColumn('payment_mode', function (Transaction $transaction) {
                return $transaction->payment_mode;
            })
            ->addColumn('purpose', function (Transaction $transaction) {
                return $transaction->creditor_type;
            })
            ->editColumn('amount', function (Transaction $transaction) {
                return $transaction->amount;
            })
            // ->editColumn('actions', function (Transaction $transaction) {
            //     $editRoute = route('admin.transaction.edit', $transaction->id);
            //     $deleteRoute = '';
            //     // $deleteRoute = route('admin.vendor.destroy',$customer->id);
            //     $optionRoute = '';
            //     $optionRouteText = '';
            //     return getTableHtml($transaction, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText);
            // })
            // ->rawColumns(['image', 'status', 'actions'])
            ->make(true);
    }



    function getAllTransactions()
    {
        return Transaction::all();
    }


    function create(array $data)
    {
        try {
            //payment_gateway_user_id
            //transaction date
            $data['payment_mode']
                =  (isset($data['payment_mode']) && in_array($data['payment_mode'], ['online', 'offline'])) ? $data['payment_mode'] : 'offline';

            $data['creditor_id'] = intval($data['creditor_id']);
            $data['debtor_id'] =  intval($data['debtor_id']);
            //creditor type
            //debtor type

            $data['payment_gateway_transaction_amount']
                =  ($data['payment_mode'] == 'online') ? floatval($data['payment_gateway_transaction_amount']) : null;

            $data['amount']
                =  ($data['payment_mode'] == 'online') ? floatval($data['payment_gateway_transaction_amount']) :  floatval($data['amount']);

            $createdTransaction = $this->transaction->create($data);
            if ($createdTransaction) {
                return $createdTransaction;
            }
            return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
