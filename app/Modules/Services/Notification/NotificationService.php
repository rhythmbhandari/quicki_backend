<?php

namespace App\Modules\Services\Notification;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

//models
use App\Modules\Models\Notification;

class NotificationService extends Service{

    protected $notification;

    function __construct(Notification $notification)
    {
        $this->notification = $notification;
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