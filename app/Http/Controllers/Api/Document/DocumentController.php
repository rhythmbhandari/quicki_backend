<?php

namespace App\Http\Controllers\Api\Document;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

//requests
use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\Document\DocumentUpdateRequest;

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




    /**
    * @OA\Post(
    *   path="/api/document/create",
    *   tags={"Document"},
    *   summary="Create Document",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "documentable_type":"rider",
    *                  "documentable_id":"1",
    *                  "type":"license",
    *                  "document_number":"546352",
    *                  "issue_date":"2000/01/01",
    *                  "expiry_date":"2000/01/01",
    *                  "image":"file()",
    *              }
    *         )
    *     )
    *   ),
    *
    *      @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={"message":"Document created successfully!","document":{"documentable_type":"rider","documentable_id":"1","type":"passport","document_number":"456457","issue_date":"2000\/01\/03","image":null,"reason":"pending","updated_at":"2021-11-18T06:38:39.000000Z","created_at":"2021-11-18T06:38:39.000000Z","id":2,"thumbnail_path":"assets\/media\/noimage.png","image_path":"assets\/media\/noimage.png"}}
    *                 )
    *           )
    *      ),
    *
    *      @OA\Response(
    *          response=422,
    *          description="Validation Fail",
    *             @OA\MediaType(
     *           mediaType="application/json",
     *      )
    *      ),
     *      @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Documentable Model doesn't exist!",
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    function store(DocumentRequest $request)
    {
        //VALIDATIONS
        $validator = Validator::make($request->all(), [
           
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


    /**
    * @OA\Post(
    *   path="/api/document/{document_id}/update",
    *   tags={"Document"},
    *   summary="Update Document",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "type":"license",
    *                  "document_number":"546352",
    *                  "issue_date":"2000/01/01",
    *                  "expiry_date":"2000/01/01",
    *                  "image":"file()",
    *              }
    *         )
    *     )
    *   ),
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={"message":"Document updated Successfully!","document":{"id":2,"documentable_type":"rider","documentable_id":1,"type":"citizenship","document_number":"456457","issue_date":"2000-01-03","expiry_date":null,"verified_at":null,"reason":"pending","image":null,"deleted_at":null,"created_at":"2021-11-18T06:38:39.000000Z","updated_at":"2021-11-18T06:43:32.000000Z","thumbnail_path":"assets\/media\/noimage.png","image_path":"assets\/media\/noimage.png"}}
    *                 )
    *           )
    *      ),
    *
    *      @OA\Response(
    *          response=422,
    *          description="Validation Fail",
    *             @OA\MediaType(
     *           mediaType="application/json",
     *      )
    *      ),
     *      @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
     *      @OA\Response(
    *          response=404,
    *          description="Document Not Found!",
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    function update(DocumentUpdateRequest $request, $document_id)
    {
        //dd($request, $document_id);
        $document = Document::findOrFail($document_id);

   
    
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
