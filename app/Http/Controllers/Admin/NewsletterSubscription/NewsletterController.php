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
