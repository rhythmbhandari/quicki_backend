<?php

namespace App\Modules\Services\Notification;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use App\Modules\Services\Notification\FirebaseNotificationService;
use Config;

//models
use App\Modules\Models\Notification;
use App\Modules\Models\User;
use App\Modules\Models\Rider;

class NotificationService extends Service{

    protected $notification, $firebase_notification_service;
    protected $title = null, $body = null, $imageUrl = null;

    protected $default_notification_messages = [
        "booking_created" => 
        [ 
            "default"=>[
                "title"=>"Puryaideu: Ride Update",
                "body"=>" New ride requested!",
            ],
        ],
        "booking_accepted" => 
            [ 
                "default"=>[
                    "title"=>"Puryaideu: Ride Update",
                    "body"=>" The ride has been ACCEPTED!",
                ],
                "customer"=>[
                    "title"=>"Puryaideu: Ride Update",
                    "body"=>" Your ride has been ACCEPTED by a rider!",
                ],
                "rider"=>[
                    "title"=>"Puryaideu: Ride Update",
                    "body"=>"You ACCEPTED a ride successfully!" 
                ] 
            ],
        "booking_running" => 
            [ 
                "default"=>[
                    "title"=>"Puryaideu: Ride Update",
                    "body"=>"Your ride is RUNNING!" 
                ],
                "customer"=>[
                    "title"=>"Puryaideu: Ride Update",
                    "body"=>"Your ride is RUNNING!" 
                ],
                "rider"=>[
                    "title"=>"Puryaideu: Ride Update",
                    "body"=>"Your ride is RUNNING!" 
                ] 
            ],
        "booking_completed" => 
            [ 
                "default"=>[
                    "title"=>"Puryaideu: Ride Update",
                    "body"=>"Your ride has been COMPLETED!" 
                ],
                "customer"=>[
                    "title"=>"Puryaideu: Ride Update",
                    "body"=>"Your ride has been COMPLETED!" 
                ],
                "rider"=>[
                    "title"=>"Puryaideu: Ride Update",
                    "body"=>"Your ride has been COMPLETED!" 
                ] 
            ],
        "booking_cancelled" => 
            [ 
                "default"=>[
                    "title"=>"Puryaideu: Ride Update",
                    "body"=>"Your ride has been CANCELLED!" 
                ],
                "customer"=>[
                    "title"=>"Puryaideu: Ride Update",
                    "body"=>"Your ride has been CANCELLED!" 
                ],
                "rider"=>[
                    "title"=>"Puryaideu: Ride Update",
                    "body"=>"Your ride has been CANCELLED!" 
                ] 
            ],
        "booking_paid_success" => 
            [ 
                "default"=>[
                    "title"=>"Puryaideu: Ride Payment Update",
                    "body"=>"Payment successful for your most recent ride!" 
                ],
                "customer"=>[
                    "title"=>"Puryaideu: Ride Payment Update",
                    "body"=>"Payment successful for your most recent ride!" 
                ],
                "rider"=>[
                    "title"=>"Puryaideu: Ride Payment Update",
                    "body"=>"Payment successful for your most recent ride!" 
                ] 
            ],
        "booking_paid_fail" => 
            [ 
                "default"=>[
                    "title"=>"Puryaideu: Ride Payment Update",
                    "body"=>"Payment failed for your most recent ride!" 
                ],
                "customer"=>[
                    "title"=>"Puryaideu: Ride Payment Update",
                    "body"=>"Payment failed for your most recent ride!" 
                ],
                "rider"=>[
                    "title"=>"Puryaideu: Ride Payment Update",
                    "body"=>"Payment failed for your most recent ride!" 
                ] 
            ],
    ];


    protected $push_notification_messages =
    [
        "default"=>
        [
            "default"=>[
                "title"=>"Puryaideu App",
                "body"=>"Welcome to Puryaideu Services!" 
            ],
            "customer"=>[
                "title"=>"Puryaideu App",
                "body"=>"Welcome to Puryaideu customer!" 
            ],
            "rider"=>[
                "title"=>"Puryaideu: Ride Payment Update",
                "body"=>"Welcome to Puryaideu rider!" 
            ] 
        ]
    ];

    function __construct(Notification $notification, FirebaseNotificationService $firebase_notification_service)
    {
        $this->notification = $notification;
        $this->firebase_notification_service = $firebase_notification_service;
        $this->title = "PURAIDEY NOTIFICATION TEST ";
        $this->message = "blah blah blah!!!!!";
        $this->imageUrl =  Config::get('webapp_url', 'http://puryaideuv2.letitgrownepal.com/') . '/assets/media/logo.png';
    }


    public function create(array $data)
    {
        try {
            $data['recipient_id'] = isset($data['recipient_id'])  ? intval($data['recipient_id']) :null;

            $createdNotification = $this->notification->create($data);

            if($createdNotification)
                return $createdNotification;
        }
        catch(Exception $e){
            return NULL;
        }
        return NULL;
    }


    public function send_firebase_notification( $recipients=null, $notification_type="push_notification", $recipient_quantity_type="individual" )
    {
        $device_tokens = null;

        $title = $this->push_notification_messages['default']['default']['title'];
        $body = $this->push_notification_messages['default']['default']['body'];

        if($notification_type != "push_notification")
        {
            $title = $this->default_notification_messages[$notification_type]['default']['title'];
            $body = $this->default_notification_messages[$notification_type]['default']['body'];
        }


        if($recipients)
        {
            //STORE NOTIFICATIONS TO NOTIFICATION TABLE
            if($recipient_quantity_type != "all")
            {
                foreach($recipients as $recipient)
                {
                    $recipient_type = $recipient[0];
                    $recipient_id = $recipient[1];
                    $device_token = null;
                    if($recipient_type == "rider")
                    {
                        $device_token = Rider::select('device_token')->where('id',$recipient_id)->first();
                    }
                    else{
                        $device_token = User::select('device_token')->where('id',$recipient_id)->first();
                    }
                    // dd($recipient_id,$recipient_type,$device_token);
                   if($device_token)
                        $device_tokens[] = $device_token = $device_token->device_token;

                    // $title = $this->push_notification_messages['default'][$recipient_type]['title'];
                    // $body = $this->push_notification_messages['default'][$recipient_type]['body'];

                    // if($notification_type != "push_notification")
                    // {
                    //     $title = $this->default_notification_messages[$notification_type][$recipient_type]['title'];
                    //     $body = $this->default_notification_messages[$notification_type][$recipient_type]['body'];
                    // }

                    
                    $this->notification->create(
                        [
                            "recipient_id"=>$recipient_id,
                            "recipient_type"=>$recipient_type,
                            "recipient_device_token"=>$device_token,
                            "recipient_quantity_type"=>$recipient_quantity_type,
                            "notification_type"=>$notification_type,
                            "title"=>$title,
                            "message"=>$body
                        ],
                    );

                    
                }
            }
            else{

                foreach($recipients as $recipient)
                {
                    $recipient_type = $recipient[0];
                    $recipient_id = $recipient[1];

                    $device_token = null;
                    if($recipient_type == "rider")
                    {
                        $device_token = Rider::select('device_token')->where('id',$recipient_id)->first();
                    }
                    else{
                        $device_token = User::select('device_token')->where('id',$recipient_id)->first();
                    }
                    //dd($recipient_id,$device_token);
                   
                    $device_tokens[] = $device_token->device_token;
                }

                // $title = $this->push_notification_messages['default']['default']['title'];
                // $body = $this->push_notification_messages['default']['default']['body'];
                // if($notification_type != "push_notification")
                // {
                //     $title = $this->default_notification_messages[$notification_type]["default"]['title'];
                //     $body = $this->default_notification_messages[$notification_type]["default"]['body'];
                // }
                $this->notification->create(
                    [
                        "recipient_id"=>null,
                        "recipient_type"=>null,
                        "recipient_device_token"=>null,
                        "recipient_quantity_type"=>$recipient_quantity_type,
                        "notification_type"=>$notification_type,
                        "title"=>$title,
                        "message"=>$body
                    ],
                );

            }


            //SEND NOTIFICATION to client's devices via FIREBASE CLOUD MESSAGING
            $response =  $this->firebase_notification_service->send(
                [
                    "title" => $title,
                    "body" => $body,
                    "imageUrl" =>  $this->imageUrl
                ], 
                $device_tokens,
                [
                    'data' => $notification_type
                ]
            );
      
            return $response;
        }
    }


















    
    function uploadFile($file)
    {
        if (!empty($file)) {
            $this->uploadPath = 'uploads/notification';
            return $fileName = $this->uploadFromAjax($file);
        }
    }

    public function __deleteImages($notification)
    {
        try {
            if (is_file($notification->image_path))
                unlink($notification->image_path);

            if (is_file($notification->thumbnail_path))
                unlink($notification->thumbnail_path);
        } catch (Exception $e) {
        }
    }

    public function updateImage($notificationId, array $data)
    {
        try {
            $notification = $this->notification->find($notificationId);
            $notification = $notification->update($data);

            return $notification;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }



}