<?php

namespace App\Modules\Services\Notification;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

//models
use App\Modules\Models\Sos;


class SosService extends Service{

    protected $sos, $notification_service;

    function __construct(Sos $sos, NotificationService $notification_service)
    {
        $this->sos = $sos;
        $this->notification_service = $notification_service;
    }

    public function create(array $data)
    {
        try {
            $data['booking_id'] = isset($data['booking_id'])? intval($data['booking_id']) : null;
            $data['created_by_id'] = isset($data['created_by_id']) ? intval($data['created_by_id']) : null;

            if(isset($data['location']) && count($data['location'])>0 )
            {
                for($i=0; $i < count($data['location']); $i++ )
                {
                    $data['location'][$i]['latitude'] = floatval( $data['location'][$i]['latitude'] );
                    $data['location'][$i]['longitude'] = floatval( $data['location'][$i]['longitude'] );
                }
            }
            $createdSos = $this->sos->create($data);

            if($createdSos)
                return $createdSos;
        }
        catch(Exception $e){
            return NULL;
        }
        return NULL;
    }





}