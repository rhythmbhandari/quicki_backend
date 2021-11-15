<?php

namespace App\Modules\Services\Vehicle;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\Shift;

class ShiftService extends Service
{
    protected $shift;

    function __construct(Shift $shift)
    {
        $this->shift = $shift;
    }
}
