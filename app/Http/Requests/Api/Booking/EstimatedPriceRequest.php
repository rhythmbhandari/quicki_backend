<?php

namespace App\Http\Requests\Api\Booking;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\ValidateDoubleRule;


class EstimatedPriceRequest extends FormRequest
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
           
            'origin_latitude' =>    ['required' ,  new ValidateDoubleRule() ],
            'origin_longitude' =>   ['required' ,  new ValidateDoubleRule() ],
            'distance' => 'required|integer',
            'duration' => 'required|integer',

        ];
    }
}
