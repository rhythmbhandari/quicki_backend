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
            
            $data['status'] = isset($data['status'])?$data['status']:'active';

            $data['rider_id'] = intval($data['rider_id']);
            $data['vehicle_type_id'] = intval($data['vehicle_type_id']);

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




    public function update($vehicleId,array $data)
    {
        try {
            if(isset($data['vehicle_type_id']))  $data['vehicle_type_id'] = intval($data['vehicle_type_id']);
            if(isset($data['rider_id']))  $data['rider_id'] = intval($data['rider_id']);
        
            $vehicle= Vehicle::findOrFail($vehicleId);
            $updatedVehicle = $vehicle->update($data);
            return $vehicle;

        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return null;
        }
    }
 


    function uploadFile($file)
    {
        if (!empty($file)) {
            $this->uploadPath = 'uploads/vehicle';
            return $fileName = $this->uploadFromAjax($file);
        }
    }

    public function __deleteImages($vehicle)
    {
        try {
            if (is_file($vehicle->image_path))
                unlink($vehicle->image_path);

            if (is_file($vehicle->thumbnail_path))
                unlink($vehicle->thumbnail_path);
        } catch (Exception $e) {
        }
    }

    public function updateImage($vehicleId, array $data)
    {
        try {
            $vehicle = $this->vehicle->find($vehicleId);
            $vehicle = $vehicle->update($data);

            return $vehicle;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }


}
