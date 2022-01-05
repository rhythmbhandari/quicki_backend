<?php

namespace App\Http\Controllers\Admin\NewsletterSubscription;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Kamaln7\Toastr\Facades\Toastr;


use App\Modules\Models\Newsletter;
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
        
        return view('admin.newsletter.index');
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
        $newsletter = Newsletter::findOrFail($id);
        // $suggested_codes = ['asdasda', 'asdaccdads', 'asdasf'];
        return view('admin.newsletter.edit', compact('newsletter','users'));
    }

 
    public function store(NewsletterRequest $request)
    {

      
            
        // $createdNewsletter = $this->newsletter->create($request->all());
        // if ($createdNewsletter) {
        //     Toastr::success('Newsletter created successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
        //     return redirect()->route('admin.newsletter.index');
        // }
        // Toastr::error('Newsletter cannot be created.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
        // return redirect()->route('admin.newsletter.index');
    
       
    }


    public function update(UpdateNewsletterRequest $request,$id)
    {

        // $createdNewsletter = $this->newsletter->update($request->all(),$id);
        // if ($createdNewsletter) {
        //     Toastr::success('Newsletter updated successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
        //     return redirect()->route('admin.newsletter.index');
        // }
        // Toastr::error('Newsletter cannot be updated.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
        // return redirect()->route('admin.newsletter.index');

    }

   
}
