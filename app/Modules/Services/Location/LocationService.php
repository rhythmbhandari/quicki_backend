<?php

namespace App\Modules\Services\Location;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\Location;

class LocationService extends Service
{
    protected $location;

    function __construct(Location $location)
    {
        $this->location = $location;
    }

    function getLocation(){
        return $this->location;
    }


    function create(array $data)
    {
        try{
            $createdLocation = $this->location->create($data);
            if($createdLocation)
            {
                return $createdLocation;
            }
            return NULL;
        }
        catch(Exception $e)
        {
            return NULL;
        }
       
    }

}
