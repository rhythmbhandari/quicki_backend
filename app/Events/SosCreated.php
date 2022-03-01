<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SosCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title, $message, $sos_id, $user_name, $user_type,$user_thumbnail_path ;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $title, $message, $user_name=null, $user_type=null,  $sos_id=null , $user_thumbnail_path=null )
    {
        $this->title = $title;
        $this->message = $message;
        $this->user_name = $user_name;
        $this->user_type = $user_type;
        $this->sos_id = $sos_id;
        $this->user_thumbnail_path = $user_thumbnail_path;
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
        return 'sos_created';//sos
    }

}
