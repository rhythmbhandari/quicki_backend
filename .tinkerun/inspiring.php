<?php

use Carbon\Carbon;

// echo Carbon::now()->format('H:i:s D:M:Y');

$response = \App\Modules\Models\Booking::where('id',1)->with('price_detail')->first();

response($response, 200);