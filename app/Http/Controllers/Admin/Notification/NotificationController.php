<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;
use App\Modules\Models\Booking;
use App\Modules\Models\Sos;
use App\Modules\Models\User;
use App\Modules\Models\Notification;
use App\Modules\Models\Rider;
use App\Modules\Models\Event;
use App\Modules\Services\Sos\SosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Services\Notification\NotificationService;
use Carbon\Carbon;

class NotificationController extends Controller
{
    protected $sos, $notification_service;
    function __construct(
        SosService $sos,
        NotificationService $notification_service
    ) {
        $this->sos = $sos;
        $this->notification_service = $notification_service;
    }

    public function getLatestNotification($notification_type="all")
    {
        $notifications = $sos = $events = null;
        
    

        if($notification_type=="notification")
        {
                $notifications = Notification::where('recipient_type','admin')
                ->where(function ($query) {
                    $query->where('read_at',NULL)
                        ->orWhereRelation('booking','status','==', 'pending');
                })
                ->orderBy('created_at','desc')->get();
        }
        else if($notification_type == "sos")
        {
           
            $sos = Sos::where('created_by_type','!=','admin')->where('status','!=','closed')
            ->where(function ($query) {
                $query->where('read_at',NULL)
                    ->orWhere('status','!=', 'closed');
            })
            ->orderBy('created_at','desc')->get();
        }
        else if($notification_type == "event")
        {
        
            $events = Event::where('created_by_type','!=','admin')
            ->where(function ($query) {
                $query->where('read_at',NULL)
                    ->orWhereRelation('sos','status','!=', 'closed');
            })
            ->orderBy('created_at','desc')->get();
        }
        else
        {
            
            $notifications = Notification::where('recipient_type','admin')
                ->where('read_at',NULL)
                ->orderBy('created_at','desc')->get();
                $sos = Sos::where('created_by_type','!=','admin')->where('status','!=','closed')
                ->where(function ($query) {
                    $query->where('read_at',NULL)
                        ->orWhere('status','!=', 'closed');
                })
                ->orderBy('created_at','desc')->get();
            $events = Event::where('created_by_type','!=','admin')
            ->where(function ($query) {
                $query->where('read_at',NULL)
                    ->orWhereRelation('sos','status','!=', 'closed');
            })
            ->orderBy('created_at','desc')->get();

        }
        // dd($notifications, $sos, $events);

        $total_alert = 0;
        $notification_alert = 0;
        $notification_alert = isset($notifications)?count($notifications->where('read_at',NULL)):0;
        // $notification_alert = $notifications->contains('five_minutes_old',true);
        $sos_alert = 0;
        $sos_alert = isset($sos)?count($sos->where('read_at',NULL)):0;
        // $sos_alert = $sos->contains('five_minutes_old',true);
        $event_alert = 0;
        $event_alert = isset($events)?count($events->where('read_at',NULL)):0;

        $total_alert =   $notification_alert + $sos_alert + $event_alert;
        // $event_alert = $events->contains('five_minutes_old',true);

        $response = [
            "notification_section" =>  isset($notifications) ? view('layouts.admin.includes.notification_section',compact('notifications'))->render() : null  ,
            "notification_alert" => $notification_alert,
            "sos_section" =>   isset($sos) ? view('layouts.admin.includes.sos_section',compact('sos'))->render() : null ,
            "sos_alert" => $sos_alert,
            "event_section" =>   isset($events) ? view('layouts.admin.includes.event_section',compact('events'))->render() : null ,
            "event_alert" => $event_alert,
            'total_alert'=>$total_alert,
            "notification_type"=>$notification_type
        ];

        return response($response, 200);


    }



    public function read_booking_notification($notification_id ){
        $notification = Notification::findOrFail($notification_id);

        if(!$notification->read_at)
        {   
            $notification->read_at = Carbon::now();
            $notification->save();
        }
       
        return redirect()->route('admin.map.dispatcher', ['booking_id'=>$notification->booking_id]);
    }

    public function read_sos($sos_id){
        $sos = Sos::findOrFail($sos_id);

        return redirect()->route('admin.sos-detail.create', $sos->id);
    }

    public function read_event($event_id){
        $event = Event::findOrFail($event_id);

        return redirect()->route('admin.sos-detail.create', $event->sos->id);
    }


}

