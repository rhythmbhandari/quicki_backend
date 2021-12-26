<?php

namespace App\Modules\Services\Vehicle;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Yajra\DataTables\Facades\DataTables;

use App\Modules\Models\VehicleType;

class VehicleTypeService extends Service
{
    protected $vehicle_type;

    function __construct(VehicleType $vehicle_type)
    {
        $this->vehicle_type = $vehicle_type;
    }

    public function getAllData()
    {
        $query = $this->vehicle_type->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('image', function (VehicleType $vehicle_type) {
                return getTableHtml($vehicle_type, 'image');
            })
            ->editColumn('name', function (VehicleType $vehicle_type) {
                return $vehicle_type->name;
            })
            ->editColumn('price_km', function (VehicleType $vehicle_type) {
                return $vehicle_type->price_km;
            })
            ->editColumn('price_min', function (VehicleType $vehicle_type) {
                return $vehicle_type->price_min;
            })
            ->editColumn('base_fare', function (VehicleType $vehicle_type) {
                return $vehicle_type->base_fare;
            })
            ->editColumn('capacity', function (VehicleType $vehicle_type) {
                return $vehicle_type->capacity;
            })
            ->editColumn('status', function (VehicleType $vehicle_type) {
                return getTableHtml($vehicle_type, 'status');
            })
            ->editColumn('actions', function (VehicleType $vehicle_type) {
                $editRoute = route('admin.vehicle_type.edit', $vehicle_type->id);
                $deleteRoute = '';
                // $deleteRoute = route('admin.vendor.destroy',$customer->id);
                $optionRoute = '';
                $optionRouteText = '';
                return getTableHtml($vehicle_type, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText);
            })->rawColumns(['image', 'status', 'actions'])
            ->make(true);
    }

    function getVehicleType()
    {
        return $this->vehicle_type;
    }

    function all()
    {
        return $this->vehicle_type->all();
    }

    function create(array $data)
    {
        try {

            $data['status'] = (isset($data['status']) ?  $data['status'] : '') == 'on' ? 'active' : 'in_active';
            // $data['default_surge_rate'] = isset($data['default_surge_rate']) ?  (1 + ($data['default_surge_rate'] / 100) * 1) : 0;

            $data['price_km'] = isset($data['price_km']) ?  intval($data['price_km']) : 10;
            $data['price_min'] = isset($data['price_min']) ?  intval($data['price_min']) : 1;
            $data['base_fare'] = isset($data['base_fare']) ?  intval($data['base_fare']) : 1;
            $data['commission'] = isset($data['commission']) ?  intval($data['commission']) : 1;
            $data['capacity'] = isset($data['capacity']) ?  intval($data['capacity']) : 1;
            $data['default_surge_rate'] = isset($data['default_surge_rate']) ?  floatval($data['default_surge_rate']) : 1;
            $data['base_covered_km'] = isset($data['base_covered_km']) ?  intval($data['base_covered_km']) : 0;
            $data['min_charge'] = isset($data['min_charge']) ?  intval($data['min_charge']) : 1;
            $data['min_surge_customers'] = isset($data['min_surge_customers']) ?  intval($data['min_surge_customers']) : 1;
            

            
            $data['price_km'] = isset($data['price_km']) ?  intval($data['price_km']) : 1;

            // if (isset($data['surge_rates']) && count($data['surge_rates']) > 0) {
            //     for ($i = 0; $i < count($data['surge_rates']); $i++) {
            //         $data['surge_rates'][$i] = floatval($data['surge_rates'][$i]);
            //     }
            // }

            //CREATE VEHICLE
            $createdVehicleType =  $this->vehicle_type->create($data);

            if ($createdVehicleType)
                return $createdVehicleType;
        } catch (Exception $e) {
            return NULL;
        }
        return NULL;
    }

    public function update($vehicleTypeId, array $data)
    {
        try {
            $data['status'] = (isset($data['status']) ?  $data['status'] : '') == 'on' ? 'active' : 'in_active';
            $data['default_surge_rate'] = isset($data['default_surge_rate']) ?  (1 + ($data['default_surge_rate'] / 100) * 1) : 0;

            $vehicleType = VehicleType::findOrFail($vehicleTypeId);
            $vehicleType->update($data);
            return $vehicleType;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return null;
        }
    }

    function uploadFile($file)
    {   // dd('reached',!empty($file), $file);
        if (!empty($file)) { //dd('uploadFile', $file);
            $this->uploadPath = 'uploads/vehicle_type';
            return $this->uploadFromAjax($file);
        }
    }

    public function __deleteImages($vehicle_type)
    {
        try {
            if (is_file($vehicle_type->image_path))
                unlink($vehicle_type->image_path);

            if (is_file($vehicle_type->thumbnail_path))
                unlink($vehicle_type->thumbnail_path);
        } catch (Exception $e) {
        }
    }

    public function updateImage($vehicleTypeId, array $data)
    {
        try {
            $vehicleType = $this->vehicle_type->find($vehicleTypeId);

            $vehicleType = $vehicleType->update($data);
            // dd($vehicleType, $data, $vehicleTypeId);


            return $vehicleType;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }

    function find($id)
    {
        return $this->vehicle_type->find($id);
    }
}
