<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class NewsletterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $newsletter;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($newsletter)
    {   
        $this->newsletter = $newsletter;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $newsletter = $this->newsletter;
        return $this->subject(config('app.name').': Newsletter')
                    ->view('admin.email.newsletter_template',compact('newsletter'));
    }
}
