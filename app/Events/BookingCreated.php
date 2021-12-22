<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title, $message,  $user_name, $booking_id ;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $title, $message, $booking_id=null, $user_name=null )
    {
        $this->title = $title;
        $this->message = $message;
        $this->user_name = $user_name;
        $this->booking_id = $sos_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('booking');
    }

    public function broadcastAs()
    {
        return 'message';
    }

}
