<?php

namespace App\Modules\Services\User;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


//services
use App\Modules\Services\User\UserService;
use App\Modules\Services\Document\DocumentService;
use App\Modules\Services\Vehicle\VehicleTypeService;
use App\Modules\Services\Vehicle\VehicleService;

//models
use App\Modules\Models\Rider;
use App\Modules\Models\User;
class RiderService extends Service
{
    protected  $rider, $user_service, $vehicle_type_service, $vehicle_service, $document_service;

    function __construct(
            UserService $user_service,
            VehicleTypeService $vehicle_type_service, 
            VehicleService $vehicle_service, 
            DocumentService $document_service,
            Rider $rider
        )
    {
        $this->user_service = $user_service;
        $this->vehicle_service = $vehicle_service;
        $this->vehicle_type_service = $vehicle_type_service;
        $this->document_service = $document_service;
        $this->rider = $rider;
    }

    function getRider(){
        return $this->rider;
    }

    function getAllowedRidersQuery()
    {
        $allowed_rider = new Rider();
        $allowed_riders = $allowed_rider->newQuery();
        $allowed_riders = $allowed_riders->where('status','active');
        $allowed_riders = $allowed_riders->whereNotNull('approved_at');
        $allowed_riders = $allowed_rider->whereHas('vehicle',function (Builder $query) {
            $query->whereRelation('vehicle_type','status','!=','in_active');
        });
        return $allowed_riders;
    }
    function getAllowedRiders()
    {
        $allowed_rider = new Rider();
        $allowed_riders = $allowed_rider->newQuery();
        $allowed_riders = $allowed_riders->where('status','active');
        $allowed_riders = $allowed_riders->whereNotNull('approved_at');
        $allowed_riders = $allowed_rider->whereHas('vehicle',function (Builder $query) {
            $query->whereRelation('vehicle_type_id','status','!=','in_active');
        })->get();
        return $allowed_riders;
    }
    function getAllowedRidersIds()
    {
        $allowed_rider = new Rider();
        $allowed_riders = $allowed_rider->newQuery();
        $allowed_riders = $allowed_riders->where('status','active');
        $allowed_riders = $allowed_riders->whereNotNull('approved_at');
        $allowed_riders = $allowed_rider->whereHas('vehicle',function (Builder $query) {
            $query->whereRelation('vehicle_type_id','status','!=','in_active');
        })->pluck('id');
        return $allowed_riders;
    }

    function create(array $data, $user=null)
    {
        try {
            
            //CREATE USER
            $created_user = null;
            if($user == null)
                $createdUser = $this->user_service->create($data);
            else
                $createdUser = $user;   //Not newly created, but old user being upgraded
            //dd($createdUser, 'creating rider user');
            if($createdUser)
            {
                $data['rider']['user_id'] = $createdUser->id;
                $data['rider']['status'] = isset($data['rider']['status'])?$data['rider']['status']:'in_active';
                //CREATE RIDER
                $createdRider = $this->rider->create($data['rider']);
                if($createdRider)
                {
                    $createdRider->user->roles()->attach(2);

                    //CREATE DOCUMENT
                    $data['document']['documentable_id'] = $createdRider->id;
                    $data['document']['documentable_type'] = 'App\Modules\Models\Rider';
                    $createdDocument = $this->document_service->create($data['document']);
                    $createdRider->latest_document =  $createdDocument;

                    //CREATE VEHICLE
                    $data['vehicle']['rider_id'] = $createdRider->id;
                    $createdVehicle = $this->vehicle_service->create($data['vehicle']);
                    $createdRider->vehicle =  $createdVehicle;


                    $createdRider->roles = $createdRider->user->roles();

                    //dd("RIDER CREATED: ",$createdRider, $createdVehicle, $createdDocument);
                    return $createdRider;
                }
            }
                
           
        }
        catch (Exception $e) {
            return null;
        }
        return null;
    }


    function uploadFile($file)
    {
        if (!empty($file)) {
            $this->uploadPath = 'uploads/rider';
            return $fileName = $this->uploadFromAjax($file);
        }
    }

    public function __deleteImages($rider)
    {
        try {
            if (is_file($rider->image_path))
                unlink($rider->image_path);

            if (is_file($rider->thumbnail_path))
                unlink($rider->thumbnail_path);
        } catch (Exception $e) {
        }
    }

    public function updateImage($riderId, array $data)
    {
        try {
            $rider = $this->rider->find($riderId);
            $rider = $rider->update($data);

            return $rider;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }

}
