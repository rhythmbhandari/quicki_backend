<?php

namespace App\Modules\Services\Document;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\Document;

class DocumentService extends Service
{
    
    protected $document;

    function __construct(Document $document)
    {
        $this->document = $document;
    }

    function getDocument(){
        return $this->document;
    }
    
    function create(array $data)
    {
        try {
            $data['reason'] = isset($data['reason'])?$data['reason']:"pending";
            $createdDocument = $this->document->create($data);
            if($createdDocument)
                return $createdDocument;
        }
        catch(Exception $e){
            return NULL;
        }
        return NULL;
    }



    public function update($documentId,array $data)
    {
        try {
        
            $document= Document::findOrFail($documentId);
            $updatedDocument = $document->update($data);
            return $updatedDocument;

        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return null;
        }
    }
   



    function uploadFile($file)
    {
        if (!empty($file)) {
            $this->uploadPath = 'uploads/document';
            return $fileName = $this->uploadFromAjax($file);
        }
    }

    public function __deleteImages($document)
    {
        try {
            if (is_file($document->image_path))
                unlink($document->image_path);

            if (is_file($document->thumbnail_path))
                unlink($document->thumbnail_path);
        } catch (Exception $e) {
        }
    }

    public function updateImage($documentId, array $data)
    {
        try {
            $document = $this->document->find($documentId);
            $document = $document->update($data);

            return $document;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }


}
