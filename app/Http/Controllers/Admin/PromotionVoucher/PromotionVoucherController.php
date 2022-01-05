<?php

namespace App\Http\Controllers\Admin\PromotionVoucher;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Kamaln7\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;


use App\Modules\Models\PromotionVoucher;
use App\Modules\Models\User;
use App\Http\Requests\Admin\PromotionVoucher\PromotionVoucherRequest;
use App\Http\Requests\Admin\PromotionVoucher\UpdatePromotionVoucherRequest;

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
        $users = User::get();
        $applicable_roles = ['customer','rider'];
        $suggested_code = generateVoucherCode(PromotionVoucher::pluck('code')->toArray());
        return view('admin.promotion_voucher.create',compact('applicable_roles','suggested_code','users'));
    }


    public function getGeneratedCode()
    {
        return $data = generateVoucherCode(PromotionVoucher::pluck('code')->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::get();
        $promotion_voucher = PromotionVoucher::findOrFail($id);
        // $suggested_codes = ['asdasda', 'asdaccdads', 'asdasf'];
        return view('admin.promotion_voucher.edit', compact('promotion_voucher','users'));
    }

 
    public function store(PromotionVoucherRequest $request)
    {

    
        return DB::transaction(function () use ($request) {
            $createdPromotionVoucher = $this->promotion_voucher->create($request->except('image'));
            if ($createdPromotionVoucher) {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $createdPromotionVoucher);
                }
                Toastr::success('PromotionVoucher created successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.promotion_voucher.index');
            }
            Toastr::error('PromotionVoucher cannot be created.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.promotion_voucher.index');
        });
    
       
    }


    public function update(UpdatePromotionVoucherRequest $request,$id)
    {

        return DB::transaction(function () use ($request, $id) {
            $updatedPromotionVoucher = $this->promotion_voucher->update($request->except('image'),$id);
            if ($updatedPromotionVoucher) {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, PromotionVoucher::find($id));
                }
                Toastr::success('PromotionVoucher updated successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.promotion_voucher.index');
            }
            Toastr::error('PromotionVoucher cannot be updated.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.promotion_voucher.index');
        });
    }


    function uploadFile(Request $request, $promotion_voucher)
    {
        $file = $request->file('image');
        $fileName = $this->promotion_voucher->uploadFile($file);
        if (!empty($promotion_voucher->image))
            $this->promotion_voucher->__deleteImages($promotion_voucher);

        $data['image'] = $fileName;
        $this->promotion_voucher->updateImage($promotion_voucher->id, $data);
    }
   
}
