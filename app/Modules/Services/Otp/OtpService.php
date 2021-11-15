<?php

namespace App\Modules\Services\Otp;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\Otp;

class OtpService extends Service
{
    protected $otp;

    function __construct(Otp $otp)
    {
        $this->otp = $otp;
    }

    function getOtp(){
        return $this->otp;
    }

}
