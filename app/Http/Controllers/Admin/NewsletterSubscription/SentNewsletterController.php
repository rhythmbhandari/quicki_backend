<?php

namespace App\Http\Controllers\Admin\NewsletterSubscription;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Kamaln7\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\Admin\NewsletterSubscription\NewsletterRequest;
use App\Http\Requests\Admin\NewsletterSubscription\UpdateNewsletterRequest;

use App\Modules\Models\Newsletter;
use App\Modules\Models\User;

use App\Modules\Services\NewsletterSubscription\NewsletterService;

class SentNewsletterController extends Controller
{
    protected $sent_sent_newsletter;
    function __construct(SentNewsletterService $sent_newsletter) {
        $this->sent_newsletter = $sent_newsletter;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.newsletter.sent_newsletter.index');
    }

    public function getAllData()
    {
        // dd('helloww');
        return $this->sent_sent_newsletter->getAllData();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.newsletter.sent_newsletter.create');
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
        return view('admin.newsletter.sent_newsletter.edit',compact('newsletter'));
    }

 
    public function store(NewsletterRequest $request)
    { 
        return DB::transaction(function () use ($request) {
            $createdNewsletter = $this->sent_newsletter->create($request->except('image'));
            if ($createdNewsletter) {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $createdNewsletter);
                }
                Toastr::success('Newsletter created successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.sent_newsletter.index');
            }
            Toastr::error('Newsletter cannot be created.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.sent_newsletter.index');
        });
    }


    public function update(UpdateNewsletterRequest $request,$id)
    {
        return redirect()->route('admin.sent_newsletter.index');
        // return DB::transaction(function () use ($request, $id) {
        //     $updatedNewsletter = $this->sent_newsletter->update($request->except('image','code'),$id);
        //     if ($updatedNewsletter) {
        //         if ($request->hasFile('image')) {
        //             $this->uploadFile($request, Newsletter::find($id));
        //         }
        //         Toastr::success('Newsletter updated successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
        //         return redirect()->route('admin.sent_newsletter.index');
        //     }
        //     Toastr::error('Newsletter cannot be updated.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
        //     return redirect()->route('admin.sent_newsletter.index');
        // });
    }



    function uploadFile(Request $request, $sent_newsletter)
    {
        $file = $request->file('image');
        $fileName = $this->sent_newsletter->uploadFile($file);
        if (!empty($sent_newsletter->image))
            $this->sent_newsletter->__deleteImages($sent_newsletter);

        $data['image'] = $fileName;
        $this->sent_newsletter->updateImage($sent_newsletter->id, $data);
    }
   
}
