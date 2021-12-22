<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title, $message, $event_id, $sos_id, $user_name, $user_type ;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $title, $message, $user_name=null, $user_type=null,  $sos_id=null,   $event_id=null  )
    {
        $this->title = $title;
        $this->message = $message;
        $this->user_name = $user_name;
        $this->user_type = $user_type;
        $this->event_id = $event_id;
        $this->sos_id = $sos_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('sos');
    }

    public function broadcastAs()
    {
        return 'event_created';
    }

}
