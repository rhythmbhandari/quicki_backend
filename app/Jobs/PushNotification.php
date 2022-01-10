<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kamaln7\Toastr\Facades\Toastr;
// use  App\Modules\Services\Notification\NotificationService;


use App\Modules\Models\Notification;
use App\Modules\Models\Subscriber;
use App\Modules\Models\User;


class PushNotification 
{
    use  InteractsWithQueue, Queueable, SerializesModels, Dispatchable;

    public $notification_id,$notification_service;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notification_id, $notification_service)
    {   
        $this->notification_service = $notification_service;
        $this->notification_id = $notification_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        return $this->notification_service->push_notification($this->notification_id);

    }
}