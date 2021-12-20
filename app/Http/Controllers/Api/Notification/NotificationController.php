<?php

namespace App\Http\Controllers\Api\Notification;

use App\Modules\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
// use Illuminate\Support\Facades\Config;

//services
use App\Modules\Services\Notification\FirebaseNotificationService;
use App\Modules\Services\Notification\NotificationService;
use App\Modules\Services\User\UserService;

class NotificationController extends Controller
{


    protected $notification, $user_service;

    public function __construct(FirebaseNotificationService $firebase_notification_service, NotificationService $notification_service, UserService $user_service)
    {
        $this->user_service = $user_service;
        $this->notification_service = $notification_service;
        $this->firebase_notification_service = $firebase_notification_service;
    }


    /**
    * @OA\Get(
    *   path="/api/notification/test",
    *   tags={"Test"},
    *   summary="Test Notification",
    *
    *
    *      @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={}
    *                 )
    *           )
    *      ),
    *)
    **/
    public function test_notification()
    {
        $device_token = "ewVH7IGaR9W153EcGzneKb:APA91bGOK19YGdZ5daIv1BkiTwCX00jBmq0MR-AFqvzyW6X05ddDQ4BWhCs92oqAHgsLr6o3AEUGcYrWq8ldEYhsrGSjf53yGlhZTMK5VlHa4UxWb4JMhJ0ZejtrGqceCtGmxlOkSpld";
        $title = "PURAIDEY NOTIFICATION TEST ";
        $message = "blah blah blah!!!!!";
        $imageUrl = Config::get('webapp_url', 'http://puryaideuv2.letitgrownepal.com/') . '/assets/media/logo.png';

        // if($recipient_data == null)
        // {
        //     $recipient_data = [
        //         [
        //             "recipient_id"=>2,
        //             "recipient_type"=>"customer",
        //             "recipient_device_token"=>$device_token,
        //             "recipient_quantity_type"=>"individual",
        //             "notification_type"=>"push_notification",
        //             "title"=>$title,
        //             "message"=>$message,
        //         ],
        //     ];
        // }
        

        // $response =  $this->firebase_notification_service->send(
        //     [
        //         "title" => $title,
        //         "body" => $message,
        //         "imageUrl" =>  $imageUrl   
        //     ], 
        //     [
        //         $device_token
        //     ],
        //     [
        //         'data' => "push_notification"
        //     ]
        // );

        // if(json_decode($response)->success == 1 && $recipient_data[0]['recipient_quantity_type']=="all")
        // {
        //     $this->notification_service->create($recipient_data[0]);
        // }

        $response = $this->notification_service->send_firebase_notification( 
            [
                ['customer', 3 ],
            ],
            "push_notification",
            "individual"
         );
      

        return $response;
    }



    // public function saveNotification($device_token, $notification, $notification_type)
    // {
        
    // }

    
}
