<?php

namespace App\Http\Controllers\Api\Notification;

use App\Modules\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        return $this->firebase_notification_service->send(
            [
                "title" => "PURAIDEY NOTIFICATION TEST ",
                "body" => "blah blah blah!!!!!",
                "imageUrl" =>  Config::get('webapp_url', 'http://puryaideuv2.letitgrownepal.com/') . '/assets/media/logo.png'
            ], 
            [
                "ewVH7IGaR9W153EcGzneKb:APA91bGOK19YGdZ5daIv1BkiTwCX00jBmq0MR-AFqvzyW6X05ddDQ4BWhCs92oqAHgsLr6o3AEUGcYrWq8ldEYhsrGSjf53yGlhZTMK5VlHa4UxWb4JMhJ0ZejtrGqceCtGmxlOkSpld"
            ],
            [
                'data' => "booking_paid"
            ]
        );

    }

    
}
