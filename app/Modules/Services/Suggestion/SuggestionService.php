<?php

namespace App\Modules\Services\Suggestion;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\DB;

//models
use App\Modules\Models\Suggestion;
use App\Modules\Models\Booking;

class SuggestionService extends Service
{
    protected $suggestion;

    function __construct(Suggestion $suggestion)
    {
        $this->suggestion = $suggestion;
    }

    function getSuggestion(){
        return $this->suggestion;
    }


    function getAllSuggestions(){
        return Suggestion::all();
    }

    function getReviewbyUserSuggestion()
    {
        return Suggestion::where('type','review_by_user')->get();
    }
    function getReviewbyRiderSuggestion()
    {
        return Suggestion::where('type','review_by_rider')->get();
    }

    function getBookingCancelByRiderSuggestion()
    {
        return Suggestion::where('type','booking_cancel_by_rider')->get();
    }
    function getBookingCancelByUserSuggestion()
    {
        return Suggestion::where('type','booking_cancel_by_user')->get();
    }


    
    function create(array $data)
    {
        try{
           
            $createdSuggestion = $this->suggestion->create($data);
            if($createdSuggestion)
            {
                return $createdSuggestion;
            }
            return NULL;
        }
        catch(Exception $e)
        {
            return NULL;
        }
    }

}
