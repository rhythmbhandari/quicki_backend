<?php

namespace App\Modules\Services\Vehicle;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

use App\Modules\Models\Vehicle;

class VehicleService extends Service
{
    protected $vehicle;

    function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    function getVehicle()
    {
        return $this->vehicle;
    }

    /*For DataTable*/
    public function  getAllData($filter = null)
    {
        $query = $this->vehicle->all();
        // if (Auth::user()->hasRole('Vendor')) {
        //     $query = $this->vehicle->whereVendorId(Auth::user()->vendor->id)->latest()->with(['vendor', 'model', 'model.type', 'model.manufacturer', 'bookings' => function ($query) {
        //         return $query->where('status', 'running');
        //     }]);
        // } else {
        //     $query = $this->vehicle->latest()->with(['vendor', 'model', 'model.type', 'model.manufacturer', 'bookings' => function ($query) {
        //         return $query->where('status', 'running');
        //     }]);
        // }

        return DataTables::of($query)
            ->addIndexColumn()
            // ->filter(function ($instance) use ($filter) {
            //     $instance->when($filter->has('vendor'), function ($query) use ($filter) {
            //         $vendor = $this->vendor->findBySlug($filter->vendor);
            //         if ($vendor) {
            //             return $query->whereVendorId($vendor->id);
            //         }
            //     })->when($filter->has('manufacturer'), function ($query) use ($filter) {
            //         $manufacturer = $this->manufacturer->findBySlug($filter->manufacturer);
            //         if ($manufacturer) {
            //             $models = $this->model->findByManufacturer($manufacturer->id);
            //             $modelIds = $models->pluck('id')->toArray();
            //             return $query->whereIn('model_id', $modelIds);
            //         }
            //     })->when($filter->has('type'), function ($query) use ($filter) {
            //         $type = $this->type->findBySlug($filter->type);
            //         if ($type) {
            //             $types = $this->model->findByType($type->id);
            //             $typeIds = $types->pluck('id')->toArray();
            //             return $query->whereIn('model_id', $typeIds);
            //         }
            //     })->when($filter->has('model'), function ($query) use ($filter) {
            //         $model = $this->model->findBySlug($filter->model);
            //         if ($model) {
            //             return $query->whereModelId($model->id);
            //         }
            //     });
            // }, true)
            ->addColumn('image', function (Vehicle $vehicle) {
                return getTableHtml($vehicle, 'image');
            })
            ->addColumn('vehicle', function (Vehicle $vehicle) {
                return $vehicle->model->name . "<br /> <strong>" . $vehicle->vehicle_number . "</strong>";
            })
            ->editColumn('vehicle_number', function (Vehicle $vehicle) {
                return $vehicle->vehicle_number;
            })
            ->editColumn('status', function (Vehicle $vehicle) {
                return getTableHtml($vehicle, 'status');
            })
            ->editColumn('actions', function (Vehicle $vehicle) {
                $editRoute = route('admin.vehicle.edit', $vehicle->id);
                $deleteRoute = '';
                $optionRoute = '';
                $optionRouteText = '';
                return getTableHtml($vehicle, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText);
            })->rawColumns(['image', 'status', 'actions', 'vehicle', 'vehicle_type'])
            ->make(true);
    }

    public function all()
    {
        return $this->vehicle->whereStatus('active')->get();
    }

    function create(array $data)
    {
        try {

            $data['status'] = isset($data['status']) ? $data['status'] : 'active';

            $data['rider_id'] = intval($data['rider_id']);
            $data['vehicle_type_id'] = intval($data['vehicle_type_id']);

            //CREATE VEHICLE
            $createdVehicle =  $this->vehicle->create($data);

            if ($createdVehicle)
                return $createdVehicle;
        } catch (Exception $e) {
            return NULL;
        }
        return NULL;
    }




    public function update($vehicleId, array $data)
    {
        try {
            if (isset($data['vehicle_type_id']))  $data['vehicle_type_id'] = intval($data['vehicle_type_id']);
            if (isset($data['rider_id']))  $data['rider_id'] = intval($data['rider_id']);

            $vehicle = Vehicle::findOrFail($vehicleId);
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
