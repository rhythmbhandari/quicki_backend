<?php

namespace App\Http\Controllers\Admin\NewsletterSubscription;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Kamaln7\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Mail;
use App\Mail\NewsletterMail;
use Illuminate\Contracts\Bus\Dispatcher;

use App\Http\Requests\Admin\NewsletterSubscription\NewsletterRequest;
use App\Http\Requests\Admin\NewsletterSubscription\UpdateNewsletterRequest;

use App\Modules\Models\Newsletter;
use App\Modules\Models\Subscriber;
use App\Modules\Models\User;

use App\Modules\Services\NewsletterSubscription\NewsletterService;

class NewsletterController extends Controller
{
    protected $newsletter;
    function __construct(NewsletterService $newsletter) {
        $this->newsletter = $newsletter;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.newsletter.index');
    }

    public function getAllData()
    {
        // dd('helloww');
        return $this->newsletter->getAllData();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.newsletter.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $newsletter = Newsletter::findOrFail($id);
        return view('admin.newsletter.edit', compact('newsletter'));
    }

    public function show($id)
    {
        $recipient_emails = null;
        $user_emails = User::pluck('email')->toArray();
        $subscriber_emails = Subscriber::where('subscribed',1)->pluck('email')->toArray();
        $recipient_emails = array_unique(array_merge($user_emails, $subscriber_emails));

        $newsletter = Newsletter::findOrFail($id);
        return view('admin.newsletter.show', compact('newsletter','recipient_emails'));
    }

 
    public function store(NewsletterRequest $request)
    { 
        return DB::transaction(function () use ($request) {
            $createdNewsletter = $this->newsletter->create($request->except('image'));
            if ($createdNewsletter) {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $createdNewsletter);
                }
                Toastr::success('Newsletter created successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.newsletter.index');
            }
            Toastr::error('Newsletter cannot be created.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.newsletter.index');
        });
    }


    public function update(UpdateNewsletterRequest $request,$id)
    {
        return DB::transaction(function () use ($request, $id) {
            $updatedNewsletter = $this->newsletter->update($request->except('image','code'),$id);
            if ($updatedNewsletter) {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, Newsletter::find($id));
                }
                Toastr::success('Newsletter updated successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.newsletter.index');
            }
            Toastr::error('Newsletter cannot be updated.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.newsletter.index');
        });
    }



    public function send_newsletter($newsletter_id)
    {
        try{
            // $job = (new \App\Jobs\SendNewsletterMail($newsletter_id))
            // ->delay(now()->addSeconds(2)); 
            // dispatch($job);

            // $emails = array_unique(array_merge(
            //     Subscriber::where('subscribed',true)->pluck('email')->toArray(), User::where('email','!=',NULL)->pluck('email')->toArray()
            // ));

            /**** DEPLOYING EACH MAIL IN QUEUE IN THE JOBS TABLE which will execute when queue is working! */
            $emails = ['amit.karn98@gmail.com','bishant345@gmail.com','suzitmaharjan666@gmail.com','rhythm@letitgrownepal.com'
            ,'amit@letitgrownepal.com','bishant@letitgrownepal.com'];
            $newsletter = Newsletter::find($newsletter_id);
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
            $message = $success. ' out of '. $total_recipients.' mails queued for delivery!';
            if($failed > 0)
                $message .= ' '.$failed.' failed to deliver!';
    
            if($success == 0)
            {
                Toastr::error($message.' Newsletter cannot be sent. ', 'Oops !!! ', ["positionClass" => "toast-bottom-right"]);
            }
            else{
                Toastr::success($message, ' Success !!! ', ["positionClass" => "toast-bottom-right"]);
            }
            
        }
        catch(Exception $e)
        {
            Toastr::error(' Something went wrong while sending mails!!! ','Oppss!'. ["positionClass" => "toast-bottom-right"]);
        }
        return redirect()->route('admin.newsletter.index');
        
       
        
    }


    function uploadFile(Request $request, $newsletter)
    {
        $file = $request->file('image');
        $fileName = $this->newsletter->uploadFile($file);
        if (!empty($newsletter->image))
            $this->newsletter->__deleteImages($newsletter);

        $data['image'] = $fileName;
        $this->newsletter->updateImage($newsletter->id, $data);
    }
   
}
