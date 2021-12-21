<?php

namespace App\Http\Controllers\Api\Notification;

use App\Modules\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use Illuminate\Support\Facades\Auth;
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

    /**
    * @OA\Get(
    *   path="/api/user/notifications",
    *   tags={"Notification and Sos"},
    *   summary="Get User's Notifications",
    *   security={{"bearerAuth":{}}},
    *
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example=
    *                       {
    *                         "message": "Success!",
    *                         "notifications": {
    *                           "current_page": 1,
    *                           "data": {
    *                             {
    *                               "id": 21,
    *                               "title": "Puryaideu: Ride Update",
    *                               "message": " The ride has been ACCEPTED!",
    *                               "image": null,
    *                               "recipient_id": 3,
    *                               "recipient_device_token": "dOUDZJ1fR6Kqp7vRNSE3KN:APA91bGbJOp3LWT_VptlGh5MZhM0Afq1r6Z26bXvdjKNcXP4ZAlxmNb1Z1wRh5vYszkYYqlA08-GKCH2lMezP_-HpEe7zhPV5lM_LnMvQToGfq3l6JiLdEBC-CgqGG7XH8WZVEc55Tvd",
    *                               "recipient_type": "customer",
    *                               "recipient_quantity_type": "some",
    *                               "notification_type": "booking_accepted",
    *                               "read_at": null,
    *                               "deleted_at": null,
    *                               "created_at": "2021-12-20T11:55:17.000000Z",
    *                               "updated_at": "2021-12-20T11:55:17.000000Z",
    *                               "thumbnail_path": "assets/media/noimage.png",
    *                               "image_path": "assets/media/noimage.png"
    *                             },
    *                             {
    *                               "id": 20,
    *                               "title": "Puryaideu: Ride Update",
    *                               "message": " New ride requested!",
    *                               "image": null,
    *                               "recipient_id": 3,
    *                               "recipient_device_token": "dOUDZJ1fR6Kqp7vRNSE3KN:APA91bGbJOp3LWT_VptlGh5MZhM0Afq1r6Z26bXvdjKNcXP4ZAlxmNb1Z1wRh5vYszkYYqlA08-GKCH2lMezP_-HpEe7zhPV5lM_LnMvQToGfq3l6JiLdEBC-CgqGG7XH8WZVEc55Tvd",
    *                               "recipient_type": "customer",
    *                               "recipient_quantity_type": "individual",
    *                               "notification_type": "booking_created",
    *                               "read_at": null,
    *                               "deleted_at": null,
    *                               "created_at": "2021-12-20T11:37:41.000000Z",
    *                               "updated_at": "2021-12-20T11:37:41.000000Z",
    *                               "thumbnail_path": "assets/media/noimage.png",
    *                               "image_path": "assets/media/noimage.png"
    *                             },
    *                           },
    *                           "first_page_url": "http://127.0.0.1:8000/api/user/notifications?page=1",
    *                           "from": 1,
    *                           "last_page": 1,
    *                           "last_page_url": "http://127.0.0.1:8000/api/user/notifications?page=1",
    *                           "links": {
    *                             {
    *                               "url": null,
    *                               "label": "&laquo; Previous",
    *                               "active": false
    *                             },
    *                             {
    *                               "url": "http://127.0.0.1:8000/api/user/notifications?page=1",
    *                               "label": "1",
    *                               "active": true
    *                             },
    *                             {
    *                               "url": null,
    *                               "label": "Next &raquo;",
    *                               "active": false
    *                             }
    *                           },
    *                           "next_page_url": null,
    *                           "path": "http://127.0.0.1:8000/api/user/notifications",
    *                           "per_page": 10,
    *                           "prev_page_url": null,
    *                           "to": 7,
    *                           "total": 7
    *                         }
    *                       }
    *                 )
    *           )
    *      ),
    *)
    **/
    public function getUserNotifications($recipient_type="customer",$recipient_id=null) {
        if(!$recipient_id)
        {
            $user = Auth::user();
            
            $recipient_id = $user->id;
            
        }
        $notifications =  Notification::where('recipient_id', $recipient_id)->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $response = ['message' => 'Success!', 'notifications' => $notifications];
        return response($response, 200);
    }


    /**
    * @OA\Get(
    *   path="/api/rider/notifications",
    *   tags={"Notification and Sos"},
    *   summary="Get Rider's Notifications",
    *   security={{"bearerAuth":{}}},
    *
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example=
    *                    {
    *                     "message": "Success!",
    *                     "notifications": {
    *                       "current_page": 1,
    *                       "data": {
    *                         {
    *                           "id": 22,
    *                           "title": "Puryaideu: Ride Update",
    *                           "message": " The ride has been ACCEPTED!",
    *                           "image": null,
    *                           "recipient_id": 1,
    *                           "recipient_device_token": "eptgkjw5ThWfxnIDLrJ_Pp:APA91bFvCkyvRevMQJErJfbDoZFNGtY6H8ZNdI-pSM--pt6bofgJMu2PZNdAe9PGNpz2wwKXdmZ5GOgcAzZl7EvAtZIJWwW9Xh0xBLwf_Qx2w_Ghz2UMW-1zwzZ_W9V4OHFxbHB7pxNy",
    *                           "recipient_type": "rider",
    *                           "recipient_quantity_type": "some",
    *                           "notification_type": "booking_accepted",
    *                           "read_at": null,
    *                           "deleted_at": null,
    *                           "created_at": "2021-12-20T11:55:17.000000Z",
    *                           "updated_at": "2021-12-20T11:55:17.000000Z",
    *                           "thumbnail_path": "assets/media/noimage.png",
    *                           "image_path": "assets/media/noimage.png"
    *                         },
    *                         {
    *                           "id": 17,
    *                           "title": "Puryaideu: Ride Update",
    *                           "message": "Your ride has been COMPLETED!",
    *                           "image": null,
    *                           "recipient_id": 1,
    *                           "recipient_device_token": "eptgkjw5ThWfxnIDLrJ_Pp:APA91bFvCkyvRevMQJErJfbDoZFNGtY6H8ZNdI-pSM--pt6bofgJMu2PZNdAe9PGNpz2wwKXdmZ5GOgcAzZl7EvAtZIJWwW9Xh0xBLwf_Qx2w_Ghz2UMW-1zwzZ_W9V4OHFxbHB7pxNy",
    *                           "recipient_type": "rider",
    *                           "recipient_quantity_type": "some",
    *                           "notification_type": "booking_completed",
    *                           "read_at": null,
    *                           "deleted_at": null,
    *                           "created_at": "2021-12-20T11:33:46.000000Z",
    *                           "updated_at": "2021-12-20T11:33:46.000000Z",
    *                           "thumbnail_path": "assets/media/noimage.png",
    *                           "image_path": "assets/media/noimage.png"
    *                         },
    *                       },
    *                       "first_page_url": "http://127.0.0.1:8000/api/rider/notifications?page=1",
    *                       "from": 1,
    *                       "last_page": 1,
    *                       "last_page_url": "http://127.0.0.1:8000/api/rider/notifications?page=1",
    *                       "links": {
    *                         {
    *                           "url": null,
    *                           "label": "&laquo; Previous",
    *                           "active": false
    *                         },
    *                         {
    *                           "url": "http://127.0.0.1:8000/api/rider/notifications?page=1",
    *                           "label": "1",
    *                           "active": true
    *                         },
    *                         {
    *                           "url": null,
    *                           "label": "Next &raquo;",
    *                           "active": false
    *                         }
    *                       },
    *                       "next_page_url": null,
    *                       "path": "http://127.0.0.1:8000/api/rider/notifications",
    *                       "per_page": 10,
    *                       "prev_page_url": null,
    *                       "to": 6,
    *                       "total": 6
    *                     }
    *                   }
    *                 )
    *           )
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
    *)
    **/
    public function getRiderNotifications($recipient_type="rider",$recipient_id=null) {
        if(!$recipient_id)
        {
            $user = Auth::user();

             //ROLE CHECK FOR RIDER
             if( ! $this->user_service->hasRole($user, 'rider') )
             {
                 $response = ['message' => 'Forbidden Access!'];
                 return response($response, 403);
             }
            $recipient_id = $user->rider->id;
           
        }
        $notifications =  Notification::where('recipient_id', $recipient_id)->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $response = ['message' => 'Success!', 'notifications' => $notifications];
        return response($response, 200);
    }

    
}
