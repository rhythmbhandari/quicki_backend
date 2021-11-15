<?php

namespace App\Modules\Services\Review;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\Review;

class ReviewService extends Service
{
    protected $review;

    function __construct(Review $review)
    {
        $this->review = $review;
    }

    function getReview(){
        return $this->review;
    }

}
