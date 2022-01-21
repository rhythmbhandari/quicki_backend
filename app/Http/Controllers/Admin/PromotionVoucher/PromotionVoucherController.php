<?php

namespace App\Http\Controllers\Admin\PromotionVoucher;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Kamaln7\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\Jobs\PushNotification;
use Carbon\Carbon;
use File;


use App\Modules\Models\PromotionVoucher;
use App\Modules\Models\User;
use App\Modules\Models\Notification;
use App\Http\Requests\Admin\PromotionVoucher\PromotionVoucherRequest;
use App\Http\Requests\Admin\PromotionVoucher\UpdatePromotionVoucherRequest;
use App\Http\Requests\Admin\PromotionVoucher\VoucherNotificationRequest;

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

    public function get_voucher_notification($promotion_voucher_id)
    {
        $promotion_voucher = PromotionVoucher::findOrFail($promotion_voucher_id);
        $voucher_code = $promotion_voucher->code;

        $related_notification = Notification::where('message','LIKE','%'.$voucher_code.'%')->latest()->first();
    //   dd($related_notification->toArray());
        $result['voucher_notification_section'] = view('admin.promotion_voucher.includes.voucher_notification_section',compact('related_notification','promotion_voucher'))->render();
        return $result;
        //dd($related_notifications->toArray());
    }

    public function save_voucher_notification(VoucherNotificationRequest $request, $promotion_voucher_id)
    {
        // dd($request->all(),$promotion_voucher_id);
        $promotion_voucher = PromotionVoucher::findOrFail($promotion_voucher_id);
        $voucher_code = $promotion_voucher->code;
        $related_notification = Notification::where('message','LIKE','%'.$voucher_code.'%')->latest()->first();
        $request['notification_type'] = 'push_promo_notification';
        $createdNotification = 0;

        // dd($related_notification);
        if($related_notification)  //UPDATE EXISTING NOTIFICATION
        {
            $id = $related_notification->id;
            $createdNotification = DB::transaction(function () use ($request, $id) {
                $updatedNotification = $this->notification_service->update($request->except('image','code','recipient_type','recipient_quantity_type'),$id);
                if ($updatedNotification) {
                    if ($request->hasFile('image')) {
                        $this->uploadFile($request, Notification::find($id), "notification");
                    }
                    Toastr::success('Voucher Notification updated successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                    return $updatedNotification = Notification::find($id);
                }
                Toastr::error('Voucher Notification cannot be updated.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
                return null;
            }); 
        }
        else{ //CREATE NEW NOTIFICATION
            $createdNotification = DB::transaction(function () use ($request,  $promotion_voucher ) {
                $request['recipient_quantity_type'] = "all";
               
                $createdNotification =   $this->notification_service->create($request->except('image'));
                if ($createdNotification) {

                    if ($request->hasFile('image')) {
                        // dd("image");
                        $this->uploadFile($request, $createdNotification, "notification");
                    } else {
                        if(  !empty($promotion_voucher->image))
                        {
                            // dd('yes');
                            $newFileName =  sprintf("%s.%s",  sha1($promotion_voucher->image . time()),".webp" );
                            File::copy($promotion_voucher->image_path, public_path('uploads/notification/'. $newFileName));
                            File::copy($promotion_voucher->thumbnail_path, public_path('uploads/notification/thumb/'. $newFileName));
                            $createdNotification->image =   $newFileName;
                            $createdNotification->save();
                            // dd();
                        }
                    }
                    // dd('no');
                    Toastr::success('Voucher Notification created successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                   return $createdNotification = Notification::find($createdNotification->id);
                }
                Toastr::error('Voucher Notification cannot be created.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
                return null;
            });
        }

        if( $createdNotification && $request['send_notification'] == 1)
        {
            $job = new PushNotification($createdNotification->id, $this->notification_service);
            dispatch($job);
            Toastr::success('Notifications sent successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
        }
        return redirect()->route('admin.notification.index');

    }

    public function push_voucher_notification($promotion_voucher_id)
    {
        $promotion_voucher = PromotionVoucher::findOrFail($promotion_voucher_id);
        $voucher_code = $promotion_voucher->code;

        $related_notifications = Notification::where('body','LIKE',$voucher_code)->get();

        dd($related_notifications->toArray());

    }



    function uploadFile(Request $request, $object, $service_type="promotion_voucher")
    {
        $file = $request->file('image');
        if($service_type=="notification")
        {
            $fileName = $this->notification_service->uploadFile($file);
            if (!empty($object->image))
                $this->notification_service->__deleteImages($object);
    
            $data['image'] = $fileName;
            $this->notification_service->updateImage($object->id, $data);
        }
        else
        {
            $fileName = $this->promotion_voucher->uploadFile($file);
            if (!empty($object->image))
                $this->promotion_voucher->__deleteImages($object);
    
            $data['image'] = $fileName;
            $this->promotion_voucher->updateImage($object->id, $data);
        }
      

    }
   
}
