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
            

            $data['uses'] = isset( $data['uses']) ? intval($data['uses']) : 0;
            $data['max_uses'] = isset( $data['max_uses']) ?  intval($data['max_uses']) : 0;
            $data['max_uses_user'] = 1;
            $data['worth'] = isset( $data['worth']) ?   floatval($data['worth']): 0;
            $data['is_fixed'] = isset( $data['is_fixed']) ?   intval($data['is_fixed']): 0;
            $data['status'] = (isset($data['status']) ?  $data['status'] : '')=='active' ? 'active' : 'in_active';

            if(isset($data['price_eligibility']))
            {
                $data['price_eligibility'] = json_decode($data['price_eligibility']);
            }
            if(isset($data['distance_eligibility']))
            {
                $data['distance_eligibility'] = json_decode($data['distance_eligibility']);
            }

            // dd('FINAL DATA',$data);

            $createdPromotionVoucher = $this->promotion_voucher->create($data);
            if ($createdPromotionVoucher) {
                return $createdPromotionVoucher;
            }
            return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }


    function update(array $data, $promotionVoucherId)
    {
        try {
            

            $data['uses'] = isset( $data['uses']) ? intval($data['uses']) : 0;
            $data['max_uses'] = isset( $data['max_uses']) ?  intval($data['max_uses']) : 0;
            $data['max_uses_user'] = 1;
            $data['worth'] = isset( $data['worth']) ?   floatval($data['worth']): 0;
            $data['is_fixed'] = isset( $data['is_fixed']) ?   intval($data['is_fixed']): 0;
            $data['status'] = (isset($data['status']) ?  $data['status'] : '')=='active' ? 'active' : 'in_active';

            if(isset($data['price_eligibility']))
            {
                $data['price_eligibility'] = json_decode($data['price_eligibility']);
            }
            if(isset($data['distance_eligibility']))
            {
                $data['distance_eligibility'] = json_decode($data['distance_eligibility']);
            }

            // dd('FINAL DATA',$data);

            $promotion_voucher = PromotionVoucher::find($promotionVoucherId);
            $updatedPromotionVoucher = $promotion_voucher->update($data);
            if ($updatedPromotionVoucher) {
                return $updatedPromotionVoucher;
            }
            return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }


}
