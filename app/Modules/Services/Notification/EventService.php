<?php

namespace App\Modules\Services\Notification;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

//models
use App\Modules\Models\Event;

class EventService extends Service{

    protected $event;

    function __construct(Event $event)
    {
        $this->event = $event;
    }


    
    public function create(array $data)
    {
        try {
            $data['sos_id'] = isset($data['sos_id'])? intval($data['sos_id']) : null;
            $data['created_by_id'] = isset($data['created_by_id']) ? intval($data['created_by_id']) : null;


            $createdEvent = $this->event->create($data);

            if($createdEvent)
                return $createdEvent;
        }
        catch(Exception $e){
            return NULL;
        }
        return NULL;
    }



}