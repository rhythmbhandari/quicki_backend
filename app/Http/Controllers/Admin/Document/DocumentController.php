<?php

namespace App\Http\Controllers\Admin\Document;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


//services
use App\Modules\Services\User\UserService;
use App\Modules\Services\Document\DocumentService;

//models
use App\Modules\Models\User;
use App\Modules\Models\CompletedTrip;
use App\Modules\Models\Rider;
use App\Modules\Models\Vehicle;
use App\Modules\Models\Document;


class DocumentController extends Controller
{

    protected $document, $user_service;

    public function __construct(DocumentService $document, UserService $user_service)
    {
        $this->document = $document;
        $this->user_service = $user_service;
    }




    function store(Request $request)
    {
        //VALIDATIONS
        $validator = Validator::make($request->all(), [
            'documentable_type' => //allowed
                ['required', function ($attribute, $value, $fail) {
                            if($value == "rider")
                            {
                               $value = "App\Modules\Models\Rider";
                            }
                            else if($value == "vehicle")
                            {
                                $value = "App\Modules\Models\Vehicle";
                            }
                            else if($value == "customer" || $value == "user")
                            {
                                $value = "App\Modules\Models\User";
                            }
                            else{
                                $fail('Invalid value given for the documentable type! Acceptable values  are user, rider, vehicle or customer!');
                            }
                        },],
            'documentable_id' => 'required|integer', 
            'type' => 'required|string|max:255',        //bluebook, license, passport, citizenship, etc
            'document_number' => 'required|string|max:255',
            'issue_date' => 'nullable|date|max:255',
            'expiry_date' => 'nullable|date|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        $documentable = null;
        //Return 404 response if the documentable model doesn't exist
        if($request->documentable_type == "rider")
        {
            $documentable = Rider::findOrFail($request->documentable_id);
        }
        else if($request->documentable_type == "vehicle")
        {
            $documentable = Vehicle::findOrFail($request->documentable_id);
        }
        else if($request->documentable_type == "customer" || $request->documentable_type == "user")
        {
            $documentable = User::findOrFail($request->documentable_id);
        }
        else{}

        
        //CREATE DOCUMENT
        return DB::transaction(function () use ($request)
        {
            $createdDocument = $this->document->create($request->all());
    
            if($createdDocument)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $createdDocument);
                }
                $response = ['message' => 'Document created successfully!',  "document"=>$createdDocument];
                return response($response, 201);
            }
            return response("Internal Server Error!", 500);
        });


    }


    function update(Request $request, $document_id)
    {
        //dd($request, $document_id);
        $document = Document::findOrFail($document_id);

        //VALIDATIONS
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255',        //bluebook, license, passport, citizenship, etc
            'document_number' => 'required|string|max:255',
            'issue_date' => 'required|date|max:255',
            'expiry_date' => 'required|date|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }
    
        //ROLE CHECK FOR RIDER
        // if( ! $this->user_service->hasRole($user, 'rider') )
        // {
        //     $response = ['message' => 'Forbidden Access!'];
        //     return response($response, 403);
        // }


        //UPDATE DOCUMENT
        return DB::transaction(function () use ($request,$document,$document_id)
        {
            $updatedDocument = $this->document->update($document->id,$request->all());
    
            if($updatedDocument)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $updatedDocument);
                }
                $response = ['message' => 'Document updated Successfully!',  "document"=>Document::findOrFail($document_id)];
                return response($response, 200);
            }
            return response("Internal Server Error!", 500);
        });


    }



    //Image for user 
    function uploadFile(Request $request, $document)
    {
        $file = $request->file('image');
        $fileName = $this->document->uploadFile($file);
        if (!empty($document->image))
            $this->document->__deleteImages($document);

        $data['image'] = $fileName;
        $this->document->updateImage($document->id, $data);
    }



}
