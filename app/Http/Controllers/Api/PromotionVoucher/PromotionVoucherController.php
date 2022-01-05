<?php

namespace App\Http\Controllers\Api\PromotionVoucher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

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
use App\Modules\Services\PromotionVoucher\PromotionVoucherService;
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
    *   path="/api/{user_type}/promotion_voucher/{promotion_voucher_code}/check",
    *   tags={"Promotion Voucher"},
    *   summary="Check if the user is eligible to use the promotion voucher",
    *   security={{"bearerAuth":{}}},
    *
    *      @OA\Parameter(
    *         name="user_type",
    *         in="path",
    *         description="User Type (Allowed Values: 'customer' or 'rider'  )",
    *         required=true,
    *      ),
    *
    *
    *      @OA\Parameter(
    *         name="promotion_voucher_code",
    *         in="path",
    *         description="Promotion Voucher Code",
    *         required=true,
    *      ),
    *
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                       "message": "Success!",
    *                       "promotion_voucher": {
    *                           "id": 2,
    *                           "slug": "test-rider-voucher",
    *                           "user_type": "rider",
    *                           "image": null,
    *                           "code": "#9816810976R",
    *                           "name": "TEST RIDER VOUCHER",
    *                           "description": "This is just a test voucher for riders!",
    *                           "uses": 0,
    *                           "max_uses": 50,
    *                           "max_uses_user": 1,
    *                           "type": "discount",
    *                           "worth": 10,
    *                           "is_fixed": true,
    *                           "eligible_user_ids": null,
    *                           "price_eligibility": {
    *                               {
    *                                   "price": 500,
    *                                   "worth": 10
    *                               },
    *                               {
    *                                   "price": 5000,
    *                                   "worth": 30
    *                               },
    *                               {
    *                                   "price": 10000,
    *                                   "worth": 60
    *                               }
    *                           },
    *                           "distance_eligibility": {
    *                               {
    *                                   "distance": 5000,
    *                                   "worth": 10
    *                               },
    *                               {
    *                                   "distance": 10000,
    *                                   "worth": 30
    *                               },
    *                               {
    *                                   "distance": 20000,
    *                                   "worth": 50
    *                               }
    *                           },
    *                           "starts_at": 1641291073,
    *                           "expires_at": 1643969473,
    *                           "status": "active",
    *                           "deleted_at": null,
    *                           "created_at": "2022-01-04T10:11:13.000000Z",
    *                           "updated_at": "2022-01-04T10:11:13.000000Z",
    *                           "status_text": "Active",
    *                           "thumbnail_path": "assets/media/user_placeholder.png",
    *                           "image_path": "assets/media/user_placeholder.png",
    *                           "is_expired": false,
    *                           "remaining_uses": 50
    *                       }
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
    *          response=400,
    *          description="Promotion expired/Failed!",
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Promotion Voucher not found!",
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
    public function checkPromotionVoucher($user_type, $promotion_voucher_code)
    {
        $user = Auth::user();
        $user_id = $user->id;
        //ROLE CHECK FOR ALREADY RIDER
        if($user_type=="rider" && !$this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        


        // $promotion_voucher = PromotionVoucher::find($promotion_voucher_id);
        $promotion_voucher = PromotionVoucher::where('code',$promotion_voucher_code)->first();

        if(!$promotion_voucher)
        {
            $response = ['message' => 'PromotionVoucher not found!'];
            return response($response, 404);
        }

        if($promotion_voucher->user_type == "rider" && $user_type != "rider")
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        if ($promotion_voucher && $user) {
            $used_promotion_vouchers = 0;
            //CHECK FOR VARIOUS ELIGIBILITY FACTORS OF THE VOUCHER 
            if($user_type == "customer")
            {
                $used_promotion_vouchers = PriceDetail::where('promotion_voucher_id', $promotion_voucher->id)
                ->whereHas('completed_trip', function (Builder $query) use ($user_id) {
                    $query->where('user_id', $user_id);
                    $query->whereStatus('completed');
                })->pluck('id', 'promotion_voucher_id');
            }
            else{
                $used_promotion_vouchers = Transaction::where('creditor_type','rider')->where('creditor_id',$user_id)
                                            ->where('promotion_voucher_id',$promotion_voucher->id)->pluck('id', 'promotion_voucher_id');
            }
            
            // dd(count($used_promotion_vouchers),$promotion_voucher->max_uses_user);
            //Check if the voucher still has uses left for the user
            if (count($used_promotion_vouchers) < $promotion_voucher->max_uses_user) {
                
                $user_travelled_distance = CompletedTrip::where('user_id', $user_id)->where('status', 'completed')->sum('distance'); //in meters
                $user_spent_price = CompletedTrip::where('user_id', $user_id)->where('status', 'completed')->sum('price');


                if (isset($promotion_voucher->eligible_user_ids)) {
                    if (in_array($user_id, $promotion_voucher->eligible_user_ids)) {
                        $response = ['message' => 'Success!','promotion_voucher'=>$promotion_voucher];
                        return response($response, 200);
                    } else {
                        $response = ['message' => 'Failed!'];
                        return response($response, 400);
                    }
                } else {
                    $response = ['message' => 'Success!','promotion_voucher'=>$promotion_voucher];
                    return response($response, 200);
                }
            }
            else{
                $response = ['message' => 'PromotionVoucher expired!'];
                return response($response, 400);
            }
        }
        else{
            $response = ['message' => 'PromotionVoucher not found!'];
            return response($response, 404);
        }

        $response = ['message' => 'Something went wrong! Internal Server Error!'];
            return response($response, 500);
    }









      /**
    * @OA\Get(
    *   path="/api/{user_type}/promotion_voucher/list",
    *   tags={"Promotion Voucher"},
    *   summary="Get the eligible promotion vouchers for the user",
    *   security={{"bearerAuth":{}}},
    *     @OA\Parameter(
    *         name="user_type",
    *         in="path",
    *         description="User Type (Allowed Values: 'customer' or 'rider'  )",
    *         required=true,
    *      ),
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                       "message": "Success!",
    *                       "promotion_vouchers": {{
    *                           "id": 2,
    *                           "slug": "test-rider-voucher",
    *                           "user_type": "rider",
    *                           "image": null,
    *                           "code": "#9816810976R",
    *                           "name": "TEST RIDER VOUCHER",
    *                           "description": "This is just a test voucher for riders!",
    *                           "uses": 0,
    *                           "max_uses": 50,
    *                           "max_uses_user": 1,
    *                           "type": "discount",
    *                           "worth": 10,
    *                           "is_fixed": true,
    *                           "eligible_user_ids": null,
    *                           "price_eligibility": {
    *                               {
    *                                   "price": 500,
    *                                   "worth": 10
    *                               },
    *                               {
    *                                   "price": 5000,
    *                                   "worth": 30
    *                               },
    *                               {
    *                                   "price": 10000,
    *                                   "worth": 60
    *                               }
    *                           },
    *                           "distance_eligibility": {
    *                               {
    *                                   "distance": 5000,
    *                                   "worth": 10
    *                               },
    *                               {
    *                                   "distance": 10000,
    *                                   "worth": 30
    *                               },
    *                               {
    *                                   "distance": 20000,
    *                                   "worth": 50
    *                               }
    *                           },
    *                           "starts_at": 1641291073,
    *                           "expires_at": 1643969473,
    *                           "status": "active",
    *                           "deleted_at": null,
    *                           "created_at": "2022-01-04T10:11:13.000000Z",
    *                           "updated_at": "2022-01-04T10:11:13.000000Z",
    *                           "status_text": "Active",
    *                           "thumbnail_path": "assets/media/user_placeholder.png",
    *                           "image_path": "assets/media/user_placeholder.png",
    *                           "is_expired": false,
    *                           "remaining_uses": 50
    *                       }}
    *                   }
    *                 )
    *           )
    *      ),
    *
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
    function getPromotionVoucherList($user_type)
    {
        $user = Auth::user();
        $user_id = $user->id;
        //ROLE CHECK FOR ALREADY RIDER
        if($user_type=="rider" && !$this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        $promotion_vouchers = PromotionVoucher::where('user_type',$user_type)
                                               ->orderBy('worth','desc')->take(5)->get();

        $response = ['message' => 'Success!', 'promotion_vouchers'=>$promotion_vouchers];
        return response($response, 200);


        $response = ['message' => 'Something went wrong! Internal Server Error!'];
        return response($response, 500);
    }

}





