<?php

namespace App\Http\Requests\Api\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use App\Rules\ValidateDoubleRule;


class LocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'latitude' =>  ['required' ,  new ValidateDoubleRule() ],
            'longitude' =>  ['required' ,  new ValidateDoubleRule() ]
           
        ];
    }

    public function messages()
    {
        return [
          
        ];
    }

}
