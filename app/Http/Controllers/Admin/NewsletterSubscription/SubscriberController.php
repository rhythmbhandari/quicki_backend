<?php

namespace App\Http\Controllers\Admin\NewsletterSubscription;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Kamaln7\Toastr\Facades\Toastr;


use App\Modules\Models\Newsletter;
use App\Modules\Models\User;

use App\Modules\Services\NewsletterSubscription\SubscriberService;

class SubscriberController extends Controller
{
    protected $subscriber;
    function __construct(SubscriberService $subscriber) {
        $this->subscriber = $subscriber;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.subscriber.index');
    }

    public function getAllData()
    {
        // dd('helloww');
        return $this->subscriber->getAllData();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.subscriber.index');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::get();
        $subscriber = Subscriber::findOrFail($id);
        // $suggested_codes = ['asdasda', 'asdaccdads', 'asdasf'];
        return view('admin.subscriber.edit', compact('subscriber','users'));
    }

 
    public function store(SubscriberRequest $request)
    {

      
            
        // $createdSubscriber = $this->subscriber->create($request->all());
        // if ($createdSubscriber) {
        //     Toastr::success('Subscriber created successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
        //     return redirect()->route('admin.subscriber.index');
        // }
        // Toastr::error('Subscriber cannot be created.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
        // return redirect()->route('admin.subscriber.index');
    
       
    }


    public function update(UpdateSubscriberRequest $request,$id)
    {

        // $createdSubscriber = $this->subscriber->update($request->all(),$id);
        // if ($createdSubscriber) {
        //     Toastr::success('Subscriber updated successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
        //     return redirect()->route('admin.subscriber.index');
        // }
        // Toastr::error('Subscriber cannot be updated.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
        // return redirect()->route('admin.subscriber.index');

    }

   
}
