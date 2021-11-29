<?php

namespace App\Http\Requests\Api\Booking;

use Illuminate\Foundation\Http\FormRequest;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
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
