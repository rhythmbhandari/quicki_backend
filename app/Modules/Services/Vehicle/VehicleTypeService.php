<?php

namespace App\Modules\Services\Vehicle;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\VehicleType;

class VehicleTypeService extends Service
{
    protected $vehicle_type;

    function __construct(VehicleType $vehicle_type)
    {
        $this->vehicle_type = $vehicle_type;
    }

    function getVehicleType(){
        return $this->vehicle_type;
    }

}
