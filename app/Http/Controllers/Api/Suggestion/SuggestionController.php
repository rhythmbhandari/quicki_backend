<?php

namespace App\Http\Controllers\Api\Suggestion;

use Illuminate\Http\Request;

//suggestion
use App\Modules\Services\Suggestion\SuggestionService;
use App\Http\Controllers\Controller;
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\Suggestion;



class SuggestionController extends Controller
{
    
    protected $suggestion, $user_service;

    public function __construct(SuggestionService $suggestion, UserService $user_service)
    {
        $this->suggestion = $suggestion;
        $this->user_service = $user_service;
    }



}
