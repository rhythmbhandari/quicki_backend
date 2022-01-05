<?php

namespace App\Http\Controllers\Api\PromotionVoucher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

//models
use App\Modules\Models\User;
use App\Modules\Models\Rider;
use App\Modules\Models\Booking;
use App\Modules\Models\CompletedTrip;
use App\Modules\Models\PromotionVoucher;
use App\Modules\Models\Transaction;
use App\Modules\Models\PriceDetail;

//services
use App\Modules\Services\User\UserService;
// use App\Http\Modules\Services\User\TransactionService;

class PromotionVoucherController extends Controller
{
    protected $payment, $user_service;

    public function __construct(PromotionVoucherService $payment, UserService $user_service)
    {
        $this->payment = $payment;
        $this->user_service = $user_service;
    }



    


      /**
    * @OA\Get(
    *   path="/api/{user_type}/promotion_voucher/{promotion_voucher_id}/check",
    *   tags={"Promotion Voucher"},
    *   summary="Check if the user is eligible to use the promotion voucher",
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
    public function checkPromotionVoucher($user_type, $booking_id)
    {
        $user = Auth::user();

        $estimated_price['price_breakdown']['promotion_voucher_id'] = null;
        $estimated_price['price_breakdown']['discount_amount'] = 0;
        $estimated_price['price_breakdown']['original_price'] =    $estimated_price['price_breakdown']['total_price'];
        if ($voucher) {


            $promotion_voucher = PromotionVoucher::where('code', $voucher)
                ->where('user_type', 'customer')
                ->where('status', 'active')
                ->whereRaw("starts_at < STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now()->format('Y-m-d H:i'))
                ->whereRaw("expires_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now()->format('Y-m-d H:i'))
                ->whereRaw('uses < max_uses')
                ->first();
            $user = User::find($user_id);


            if ($promotion_voucher && $user) {
                //CHECK FOR VARIOUS ELIGIBILITY FACTORS OF THE VOUCHER 
                $used_promotion_vouchers = PriceDetail::whereHas('completed_trip', function (Builder $query) use ($user_id) {
                    $query->where('user_id', $user_id);
                    $query->whereStatus('completed');
                })->where('promotion_voucher_id', $promotion_voucher->id)->pluck('id', 'promotion_voucher_id');

                //Check if the voucher still has uses left for the user
                if (count($used_promotion_vouchers) < $promotion_voucher->max_uses_user) {
                    $user_travelled_distance = CompletedTrip::where('user_id', $user_id)->where('status', 'completed')->sum('distance'); //in meters
                    $user_spent_price = CompletedTrip::where('user_id', $user_id)->where('status', 'completed')->sum('price');

                    $price_eligibility_allowance = 0;
                    $distance_eligibility_allowance = 0;

                    if (isset($promotion_voucher->price_eligibility)) {
                        //dd('as',$promotion_voucher->price_eligibility);
                        foreach ($promotion_voucher->price_eligibility as $price_range) {
                            if ($user_spent_price >= $price_range['price'])
                                $price_eligibility_allowance = intval($price_range['worth']);
                        }
                    }
                    if (isset($promotion_voucher->distance_eligibility)) {
                        foreach ($promotion_voucher->distance_eligibility as $distance_range) {
                            if ($user_travelled_distance >= $distance_range['distance'])
                                $distance_eligibility_allowance = intval($distance_range['worth']);
                        }
                    }

                    $voucher_worth = $promotion_voucher->worth + $price_eligibility_allowance + $distance_eligibility_allowance;

                    if (isset($promotion_voucher->eligible_user_ids)) {
                        if (in_array($user_id, $promotion_voucher->eligible_user_ids)) {
                            //APPLY DISCOUNT
                            if (!$promotion_voucher->is_fixed) {
                                $estimated_price['price_breakdown']['discount_amount'] =
                                    $estimated_price['price_breakdown']['total_price'] * ($voucher_worth / 100);
                            } else {
                                $estimated_price['price_breakdown']['discount_amount'] = $voucher_worth;
                            }
                            $estimated_price['price_breakdown']['total_price'] =
                                $estimated_price['price_breakdown']['total_price'] - $estimated_price['price_breakdown']['discount_amount'];

                            $estimated_price['price_breakdown']['total_price'] = ($estimated_price['price_breakdown']['total_price'] >= 0) ?  $estimated_price['price_breakdown']['total_price'] : 0;
                            $estimated_price['price_breakdown']['promotion_voucher_id'] = $promotion_voucher->id;
                        } else {
                            //DO NOT APPLY DISCOUNT
                            $voucher_worth = 0;
                        }
                    } else {
                        //APPLY DISCOUNT
                        if (!$promotion_voucher->is_fixed) {
                            $estimated_price['price_breakdown']['discount_amount'] =
                                $estimated_price['price_breakdown']['total_price'] * ($voucher_worth / 100);
                        } else {
                            $estimated_price['price_breakdown']['discount_amount'] = $voucher_worth;
                        }
                        $estimated_price['price_breakdown']['total_price'] =
                            $estimated_price['price_breakdown']['total_price'] - $estimated_price['price_breakdown']['discount_amount'];

                        $estimated_price['price_breakdown']['total_price'] = ($estimated_price['price_breakdown']['total_price'] >= 0) ?  $estimated_price['price_breakdown']['total_price'] : 0;
                        $estimated_price['price_breakdown']['promotion_voucher_id'] = $promotion_voucher->id;
                    }
                }
            }
        }
        return $estimated_price;

        if(!$payment)
        {
            $response = ['message' => 'PromotionVoucher not found!'];
            return response($response, 404);
        }
        $response = ['message' => 'Success!', 'payment'=>$payment];
            return response($response, 200);
    }

}
