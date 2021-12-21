<?php

namespace App\Modules\Services\Notification;


//services
// use App\Modules\Services\Notification\NotificationService;

//models
use App\Modules\Models\Notification;
use App\Modules\Models\Sos;
use App\Modules\Models\Event;


class FirebaseNotificationService{
    
    private $server_api_key;
    private $fcm_url;
    private $headers;
    // protected $notification_service;

    public function __construct(){
        $this->server_api_key = config('app.firebase_server_api_key');
        $this->fcm_url = 'https://fcm.googleapis.com/fcm/send';
        $this->headers = [
            'Authorization: key=' . $this->server_api_key,
            'Content-Type: application/json',
        ];

        // $this->notification_service = $notification_service;
    }

    /**
     * Cast the data and send notification
     *
     * @param  array  $notification (Object array with title, body and image)
     * @param  array  $registration_ids (Array of tokens)
     * @return \Illuminate\Http\Response
     */
    public function send($notification, $registration_ids, $data=["data"=>"push_notification"]){//, $recipient_data=null){
        $dataString = json_encode(compact('registration_ids', 'notification', 'data'));

        // if (count($registration_ids) == 1) {
        //     $this->saveNotification($registration_ids[0], $notification, $data);
        // }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->fcm_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);

        // if(json_decode($response)->success == 1 &&  ($recipient_data[0]['recipient_quantity_type'] == "individual" || $recipient_data[0]['recipient_quantity_type'] == "some" ) )
        // {
        //    // dd(  "sent!" );
        //     //Save Notification
        //     foreach($recipient_data as $rd)
        //         $this->notification_service->create($rd);


        // }

        return $response;
    }

    // public function saveNotification($device_token, $notification, $notification_type, $recipient_type)
    // {
        
    // }


    // public function saveNotification($device_token, $notification, $type)
    // {
    //     $data['recipient_device_token'] = $device_token;
    //     $data['recipient_id'] = User::select('id')->where('device_token',$device_token)->first();
    // }

    // public function saveNotification($device_token, $notification, $type) {
    //     $notification['type'] = $type['type'];
    //     $customer = Customer::select('id')->where('device_token', $device_token)->first();
        
    //     $notification['customer_id'] = $customer->id;
    //     $notification['status'] = 'active';
    //     EventNotification::create($notification);
    // }
}