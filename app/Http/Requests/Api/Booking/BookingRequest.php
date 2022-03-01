<?php

namespace App\Http\Requests\Api\Booking;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Validator;
use App\Rules\ValidateDoubleRule;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

//models
use App\Modules\Models\VehicleType;

class BookingRequest extends FormRequest
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
           
            // 'origin' => 'required|string|max:255',
            // 'destination' => 'required|string|max:255',
            'vehicle_type_id' =>  ['required', function ($attribute, $value, $fail) {
                $vehicle_type = VehicleType::find($value);
                    if ( !$vehicle_type) {
                        $fail('The vehicle type does not exist!');
                    }
                },],
            'distance' => 'required|integer',
            'duration' => 'required|integer',
            'price' => 'required|integer',
            'passenger_number' => 'nullable|integer',

            //Location
            // 'location.latitude_origin' =>  ['required' ,  new ValidateDoubleRule() ],
            // 'location.longitude_origin' =>  ['required' ,  new ValidateDoubleRule() ],
            // 'location.latitude_destination' =>  ['required' ,  new ValidateDoubleRule() ],
            // 'location.longitude_destination' => ['required' ,  new ValidateDoubleRule() ],

            'location.origin.name' => 'required|string|max:255',
            'location.destination.name' => 'required|string|max:255',
            'location.origin.latitude' =>  ['required' ,  new ValidateDoubleRule() ],
            'location.origin.longitude' =>  ['required' ,  new ValidateDoubleRule() ],
            'location.destination.latitude' =>  ['required' ,  new ValidateDoubleRule() ],
            'location.destination.longitude' => ['required' ,  new ValidateDoubleRule() ],

            //Stoppage
            'stoppage.*.name' => [   ],
            'stoppage.*.latitude' => [ new ValidateDoubleRule() ],
            'stoppage.*.longitude' => [ new ValidateDoubleRule() ],

        ];
    }
}
