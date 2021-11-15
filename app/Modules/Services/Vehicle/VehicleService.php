<?php

namespace App\Modules\Services\Vehicle;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\Vehicle;

class VehicleService extends Service
{
    protected $vehicle;

    function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    function getVehicle(){
        return $this->vehicle;
    }

    function create(array $data)
    {
        try {
            
            $data['status'] = isset($data['status'])?$data['status']:'in_active';

            //CREATE VEHICLE
            $createdVehicle =  $this->vehicle->create($data);

            if($createdVehicle)
                return $createdVehicle;
        }
        catch(Exception $e){
            return NULL;
        }
        return NULL;
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
