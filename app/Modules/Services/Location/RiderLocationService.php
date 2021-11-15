<?php

namespace App\Modules\Services\Location;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\RiderLocation;

class RiderLocationService extends Service
{
    protected $rider_location;

    function __construct(RiderLocation $rider_location)
    {
        $this->rider_location = $rider_location;
    }

    function getRiderLocation(){
        return $this->rider_location;
    }

}
