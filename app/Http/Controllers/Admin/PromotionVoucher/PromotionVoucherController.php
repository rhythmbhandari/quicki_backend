<?php

namespace App\Http\Controllers\Admin\PromotionVoucher;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


use App\Modules\Models\PromotionVoucher;

use App\Modules\Services\PromotionVoucher\PromotionVoucherService;
use App\Modules\Services\Notification\NotificationService;

class PromotionVoucherController extends Controller
{
    protected $promotion_voucher, $notification_service;
    function __construct(
        PromotionVoucherService $promotion_voucher,
        NotificationService $notification_service
    ) {
        $this->promotion_voucher = $promotion_voucher;
        $this->notification_service = $notification_service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.promotion_voucher.index');
    }

    public function getAllData()
    {
        // dd('helloww');
        return $this->promotion_voucher->getAllData();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$bookings = Booking::get();
        return view('admin.promotion_voucher.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promotion_voucher = PromotionVoucher::findOrFail($id);
        return view('admin.promotion_voucher.edit', compact('promotion_voucher'));
    }

 
    public function store()
    {



    }


    public function update()
    {

        

    }

   
}
