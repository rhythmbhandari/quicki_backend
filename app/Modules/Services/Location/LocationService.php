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

    function getLocation()
    {
        return $this->location;
    }


    function create(array $data)
    {
        try {
            $data['latitude_origin'] = floatval($data['latitude_origin']);
            $data['longitude_origin'] = floatval($data['longitude_origin']);

            $data['latitude_destination'] = floatval($data['latitude_destination']);
            $data['longitude_destination'] = floatval($data['longitude_destination']);

            $createdLocation = $this->location->create($data);
            if ($createdLocation) {
                return $createdLocation;
            }
            return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    function update(array $data, $id)
    {
        try {
            $data['latitude_origin'] = floatval($data['latitude_origin']);
            $data['longitude_origin'] = floatval($data['longitude_origin']);

            $data['latitude_destination'] = floatval($data['latitude_destination']);
            $data['longitude_destination'] = floatval($data['longitude_destination']);

            $updatedLocation = $this->location->find($id)->update($data);

            return $updatedLocation;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
