<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kamaln7\Toastr\Facades\Toastr;
use Mail;
use App\Mail\NewsletterMail;


use App\Modules\Models\Newsletter;
use App\Modules\Models\Subscriber;
use App\Modules\Models\User;


class SendNewsletterMail //implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $newsletter_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($newsletter_id)
    {   
        $this->newsletter_id = $newsletter_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $users = User::get();
        // $input['subject'] = $this->mail_data['subject'];

        // foreach ($users as $key => $value) {
        //     $input['email'] = $value->email;
        //     $input['name'] = $value->name;
            
        //     \Mail::send('mails.mail', [], function($message) use($input){
        //         $message->to($input['email'], $input['name'])
        //             ->subject($input['subject']);
        //     });
        // }

        $emails = array_unique(array_merge(
            Subscriber::where('subscribed',true)->pluck('email')->toArray(), User::where('email','!=',NULL)->pluck('email')->toArray()
        ));
       // $emails = ['amit.karn98@gmail.com','bishant345@gmail.com'];
        $newsletter = Newsletter::find($this->newsletter_id);
        $body = $newsletter->body;

        $total_recipients = count($emails);
        $success = 0;
        $failed = 0;
        foreach($emails as $email)
        {
            try{
                Mail::to($email)->send(new NewsletterMail($newsletter));
                $success++;
            }
            catch(Exception $e) {
                $failed++;
            }
        }
        $message = $success. 'out of '. $total_recipients.' mails were delivered!';
        if($failed > 0)
            $message .= ' '.$failed.' failed to deliver!';

        if($success == 0)
        {
            Toastr::error($message.'Newsletter cannot be sent. ', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
        }
        else{
            Toastr::success($message, 'Success !!!', ["positionClass" => "toast-bottom-right"]);
        }
        


    }
}