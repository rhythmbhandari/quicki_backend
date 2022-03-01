<?php

namespace App\Http\Requests\Api\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Rules\ValidateDoubleRule;

use App\Modules\Models\VehicleType;

class AvailableRidersRequest extends FormRequest
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

            'origin_latitude' =>  ['required' ,  new ValidateDoubleRule() ],
            'origin_longitude' =>  ['required' ,  new ValidateDoubleRule() ],
            'vehicle_type_id' =>  ['nullable','integer', function ($attribute, $value, $fail) {
                if($value>0)
                {
                    $vehicle_type = VehicleType::find($value);
                    if ( !$vehicle_type) {
                        $fail('The vehicle type does not exist!');
                    }
                }else {$fail('The vehicle type does not exist!');}
            },],
           
        ];
    }

    public function messages()
    {
        return [
          
        ];
    }

}
