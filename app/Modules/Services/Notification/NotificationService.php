<?php

namespace App\Modules\Services\Notification;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use App\Modules\Services\Notification\FirebaseNotificationService;
use Yajra\DataTables\Facades\DataTables;
use Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

//models
use App\Modules\Models\Notification;
use App\Modules\Models\User;
use App\Modules\Models\Rider;

class NotificationService extends Service
{

    protected $notification, $firebase_notification_service;
    protected $title = null, $body = null, $imageUrl = null;

    protected $default_notification_messages = [
        "booking_created" =>
        [
            "default" => [
                "title" => "Puryaideu: Ride Update",
                "body" => " New ride requested!",
            ],
        ],
        "booking_accepted" =>
        [
            "default" => [
                "title" => "Puryaideu: Ride Update",
                "body" => " The ride has been ACCEPTED!",
            ],
            "customer" => [
                "title" => "Puryaideu: Ride Update",
                "body" => " Your ride has been ACCEPTED by a rider!",
            ],
            "rider" => [
                "title" => "Puryaideu: Ride Update",
                "body" => "You ACCEPTED a ride successfully!"
            ]
        ],
        "booking_running" =>
        [
            "default" => [
                "title" => "Puryaideu: Ride Update",
                "body" => "Your ride is RUNNING!"
            ],
            "customer" => [
                "title" => "Puryaideu: Ride Update",
                "body" => "Your ride is RUNNING!"
            ],
            "rider" => [
                "title" => "Puryaideu: Ride Update",
                "body" => "Your ride is RUNNING!"
            ]
        ],
        "booking_completed" =>
        [
            "default" => [
                "title" => "Puryaideu: Ride Update",
                "body" => "Your ride has been COMPLETED!"
            ],
            "customer" => [
                "title" => "Puryaideu: Ride Update",
                "body" => "Your ride has been COMPLETED!"
            ],
            "rider" => [
                "title" => "Puryaideu: Ride Update",
                "body" => "Your ride has been COMPLETED!"
            ]
        ],
        "booking_cancelled" =>
        [
            "default" => [
                "title" => "Puryaideu: Ride Update",
                "body" => "Your ride has been CANCELLED!"
            ],
            "customer" => [
                "title" => "Puryaideu: Ride Update",
                "body" => "Your ride has been CANCELLED!"
            ],
            "rider" => [
                "title" => "Puryaideu: Ride Update",
                "body" => "Your ride has been CANCELLED!"
            ]
        ],
        "booking_paid_success" =>
        [
            "default" => [
                "title" => "Puryaideu: Ride Payment Update",
                "body" => "Payment successful for your most recent ride!"
            ],
            "customer" => [
                "title" => "Puryaideu: Ride Payment Update",
                "body" => "Payment successful for your most recent ride!"
            ],
            "rider" => [
                "title" => "Puryaideu: Ride Payment Update",
                "body" => "Payment successful for your most recent ride!"
            ]
        ],
        "booking_paid_fail" =>
        [
            "default" => [
                "title" => "Puryaideu: Ride Payment Update",
                "body" => "Payment failed for your most recent ride!"
            ],
            "customer" => [
                "title" => "Puryaideu: Ride Payment Update",
                "body" => "Payment failed for your most recent ride!"
            ],
            "rider" => [
                "title" => "Puryaideu: Ride Payment Update",
                "body" => "Payment failed for your most recent ride!"
            ]
        ]
    ];


    protected $push_notification_messages =
    [
        "default" =>
        [
            "default" => [
                "title" => "Puryaideu App",
                "body" => "Welcome to Puryaideu Services!"
            ],
            "customer" => [
                "title" => "Puryaideu App",
                "body" => "Welcome to Puryaideu customer!"
            ],
            "rider" => [
                "title" => "Puryaideu: Ride Payment Update",
                "body" => "Welcome to Puryaideu rider!"
            ]
        ]
    ];

    function __construct(Notification $notification, FirebaseNotificationService $firebase_notification_service)
    {
        $this->notification = $notification;
        $this->firebase_notification_service = $firebase_notification_service;
        $this->title = "PURAIDEY NOTIFICATION TEST ";
        $this->message = "blah blah blah!!!!!";
        $this->imageUrl =  Config::get('webapp_url', 'http://puryaideuv2.letitgrownepal.com/') . 'assets/media/logo.png';
    }


    public function create(array $data)
    {
        try {
            $data['recipient_id'] = isset($data['recipient_id'])  ? intval($data['recipient_id']) : null;

            $existing_codes = Notification::pluck('code')->toArray();
            $data['code'] = generateNotificationCode($existing_codes);

            $createdNotification = $this->notification->create($data);

            if ($createdNotification)
                return $createdNotification;
        } catch (Exception $e) {
            return NULL;
        }
        return NULL;
    }

    function update($data,$notificationId)
    {
        try {
            
            $notification= Notification::findOrFail($notificationId);
            $updatedNotification = $notification->update($data);
            return $updatedNotification;

        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return null;
        }
    }


    public function delete($notificationId)
    {
        // dd('delete');
        try {

            $notification = Notification::findOrFail($notificationId);
            return $deleted = $notification->delete();
      
        } catch (Exception $e) {
            return false;
        }
    }


    public function send_firebase_notification($recipients = null, $notification_type = "push_notification", $recipient_quantity_type = "individual", $message=null)
    {
        $device_tokens = null;

        $title = $this->push_notification_messages['default']['default']['title'];
        $body = $this->push_notification_messages['default']['default']['body'];
        $sound = "default";
        // $icon =  $this->imageUrl ;

        if($message == null)
        {
            if ($notification_type != "push_notification") {
                $title = $this->default_notification_messages[$notification_type]['default']['title'];
                $body = $this->default_notification_messages[$notification_type]['default']['body'];
            }
        }
        else{
            $title = $message['title'];
            $body = $message['body'];
        }
       
        if ($recipients) {
            //STORE NOTIFICATIONS TO NOTIFICATION TABLE
            if ($recipient_quantity_type != "all") {
                foreach ($recipients as $recipient) {
                    $recipient_type = $recipient[0];
                    $recipient_id = $recipient[1];
                    $device_token = null;
                    if ($recipient_type == "rider") {
                        $device_token = Rider::select('device_token')->where('id', $recipient_id)->first();
                    } else {
                        $device_token = User::select('device_token')->where('id', $recipient_id)->first();
                    }
                    // dd($recipient_id,$recipient_type,$device_token);
                    if ($device_token)
                        $device_tokens[] = $device_token = $device_token->device_token;

      
                    $create_data =  [
                        "recipient_id" => $recipient_id,
                        "recipient_type" => $recipient_type,
                        "recipient_device_token" => $device_token,
                        "recipient_quantity_type" => $recipient_quantity_type,
                        "notification_type" => $notification_type,
                        "title" => $title,
                        "message" => $body
                    ];
                    $this->create($create_data);
                }
            } else {

                foreach ($recipients as $recipient) {
                    $recipient_type = $recipient[0];
                    $recipient_id = $recipient[1];

                    $device_token = null;
                  if ($recipient_type == "rider") {
                        $device_token = Rider::select('device_token')->where('id', $recipient_id)->first();
                    } else {
                        $device_token = User::select('device_token')->where('id', $recipient_id)->first();
                    }
                    //dd($recipient_id,$device_token);

                    $device_tokens[] = $device_token->device_token;
                }

                
                $create_data =  [
                    "recipient_id" => null,
                    "recipient_type" => null,
                    "recipient_device_token" => null,
                    "recipient_quantity_type" => $recipient_quantity_type,
                    "notification_type" => $notification_type,
                    "title" => $title,
                    "message" => $body
                ];
                
                $this->create($create_data);

            }


            //SEND NOTIFICATION to client's devices via FIREBASE CLOUD MESSAGING
            $response =  $this->firebase_notification_service->send(
                [
                    "title" => $title,
                    "body" => $body,
                    "sound"=>$sound,
                    "imageUrl" =>  $this->imageUrl,
                    // "icon"=>$icon 
                ],
                $device_tokens,
                [
                    'data' => $notification_type
                ]
            );

            return $response;
        }
        
    }


    public function push_notification($notification_id)
    {
        $notification = Notification::findOrFail($notification_id);

        $device_tokens = null;
        $recipient_type = null;
        if($notification->recipient_type == "customer") {
            $recipient_type = "customer";
            $device_tokens = User::where('device_token','!=',NULL)
                                // ->where('status','active')
                                ->pluck('device_token')->toArray();
        }
        else if($notification->recipient_type == "rider") {
            $recipient_type =  "rider";
            $device_tokens = Rider::where('device_token','!=',NULL)
            ->pluck('device_token')->toArray();
        }
        else{
            $recipient_type = "all";
            $customer_tokens = User::where('device_token','!=',NULL)
            ->pluck('device_token')->toArray();
            $rider_tokens = Rider::where('device_token','!=',NULL)
            ->pluck('device_token')->toArray();

            $device_tokens = array_merge($customer_tokens , $rider_tokens);
        }

        $response = $this->firebase_notification_service->send(
            [
                "title" => $notification->title,
                "body" => $notification->message,
                "sound"=> "default",
                "imageUrl" =>  $this->imageUrl,
                // "icon"=>$icon 
            ],
            $device_tokens,
            [
                'data' => $notification->notification_type
            ]
        );

        Log::channel('custom')->critical(
            'PUSH NOTIFICATION :: DATETIME=>'.Carbon::now()->toDayDateTimeString(). ' :: RECIPIENT TYPE=> '.$recipient_type.
            ':: FIREBASE RESPONSE=> '.$response
        );

        $notification->read_at = Carbon::now();
        $notification->updated_at = Carbon::now();
        $notification->save();

        return $response;

    }



    /*For DataTable*/
    public function  getAllData($filter = null)
    {
        $query = $this->notification->where('notification_type','!=','push_notification')->orderBy('created_at','desc')->get();
        
        // dd('asdads', $filter);
        if($filter)
        {
            // dd('asdads', $filter);
            if($filter == "push_notification")
                $query = $this->notification
                // ->where('notification_type','push_notification')
                ->where(function($query) {
                    $query->where('notification_type','push_notification')
                          ->orWhere('notification_type','push_promo_notification');
                })
                ->orderBy('created_at','desc')->get();
        }
           

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('code', function (Notification $notification) {
                return $notification->code;
                // return route('admin.notification.destroy', $notification->id);
            })
            ->addColumn('title', function (Notification $notification) {
                return $notification->title;
            })
            ->editColumn('image', function(Notification $notification){
                return getTableHtml($notification, 'image');
            })
            ->addColumn('message', function (Notification $notification) {
                return $notification->message;
            })
            ->addColumn('notification_type', function (Notification $notification) {
                return  '<span class="'.getLabel($notification->notification_type).'">'. $notification->notification_type .'</span>';
            })
            ->addColumn('recipient_type', function (Notification $notification) {
                return '<span class="'.getLabel($notification->recipient_type).'">'. $notification->recipient_type .'</span>';
            })
            ->editColumn('actions', function (Notification $notification) {
                // $editRoute = ($notification->notification_type=="push_notification" && (!$notification->read_at) )?route('admin.notification.edit', $notification->id):'';
                $editRoute = ($notification->notification_type=="push_notification" || $notification->notification_type=="push_promo_notification" )?route('admin.notification.edit', $notification->id):'';
                $deleteRoute = ($notification->notification_type=="push_notification" || $notification->notification_type=="push_promo_notification" )?route('admin.notification.destroy', $notification->id):'';
                $showRoute = '';//route('admin.notification.show', $notification->id);
                $mapRoute = '';
                $optionRouteText = ($notification->notification_type=="push_notification" || $notification->notification_type=="push_promo_notification")?route('admin.notification.push', $notification->id):'';
                return getTableHtml($notification, 'actions', $editRoute, $deleteRoute, $showRoute, $optionRouteText, "", $mapRoute);
            })->rawColumns([ 'code', 'image','notification_type', 'recipient_type','message', 'actions', 'title'])
            ->make(true);
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
