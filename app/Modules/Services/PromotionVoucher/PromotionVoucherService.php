<?php

namespace App\Modules\Services\PromotionVoucher;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

//models
use App\Modules\Models\PromotionVoucher;
use App\Modules\Models\Booking;

class PromotionVoucherService extends Service
{
    protected $promotion_voucher;

    function __construct(PromotionVoucher $promotion_voucher)
    {
        $this->promotion_voucher = $promotion_voucher;
    }

    function getPromotionVoucher()
    {
        return $this->promotion_voucher;
    }

    public function getAllData()
    {
        $query = $this->promotion_voucher->all();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('name', function (PromotionVoucher $promotion_voucher) {
                return $promotion_voucher->name;
            })
            ->editColumn('code', function (PromotionVoucher $promotion_voucher) {
                return $promotion_voucher->code;
            })
            ->editColumn('user_type', function (PromotionVoucher $promotion_voucher) {
                return $promotion_voucher->user_type;
            })
            ->editColumn('type', function (PromotionVoucher $promotion_voucher) {
                return $promotion_voucher->type;
            })
            ->editColumn('worth', function (PromotionVoucher $promotion_voucher) {
                if( $promotion_voucher->is_fixed)
                    return 'Rs. '.$promotion_voucher->worth;
                else 
                    return $promotion_voucher->worth.' %';
            })
            ->editColumn('remaining_uses', function (PromotionVoucher $promotion_voucher) {
                return $promotion_voucher->max_uses - $promotion_voucher->uses;
            })
            ->editColumn('starts_at', function (PromotionVoucher $promotion_voucher) {
                return prettyDate($promotion_voucher->starts_at);
            })
            ->editColumn('expires_at', function (PromotionVoucher $promotion_voucher) {
                return prettyDate($promotion_voucher->expires_at);
            })
            ->editColumn('status', function (PromotionVoucher $promotion_voucher) {
                return getTableHtml($promotion_voucher, 'status');
            })
            ->editColumn('actions', function (PromotionVoucher $promotion_voucher) {
                $editRoute = route('admin.promotion_voucher.edit', $promotion_voucher->id);
                $deleteRoute = '';
                // $deleteRoute = route('admin.vendor.destroy',$customer->id);
                $optionRoute = '';
                $optionRouteText = '';
                return getTableHtml($promotion_voucher, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText);
            })
            ->rawColumns(['name','user_type','code','type','worth','remaining_uses','starts_at','expires_at', 'status', 'actions'])
            ->make(true);
    }

    function getAllPromotionVouchers()
    {
        return PromotionVoucher::all();
    }


    function create(array $data)
    {
        try {
            //payment_gateway_user_id
            //promotion_voucher date
            $data['payment_mode']
                =  (isset($data['payment_mode']) && in_array($data['payment_mode'], ['online', 'offline'])) ? $data['payment_mode'] : 'offline';

            $data['creditor_id'] = intval($data['creditor_id']);
            $data['debtor_id'] =  intval($data['debtor_id']);
            //creditor type
            //debtor type

            $data['payment_gateway_promotion_voucher_amount']
                =  ($data['payment_mode'] == 'online') ? floatval($data['payment_gateway_promotion_voucher_amount']) : null;

            $data['amount']
                =  ($data['payment_mode'] == 'online') ? floatval($data['payment_gateway_promotion_voucher_amount']) :  floatval($data['amount']);

            $createdPromotionVoucher = $this->promotion_voucher->create($data);
            if ($createdPromotionVoucher) {
                return $createdPromotionVoucher;
            }
            return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
