<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Rules\ValidateDoubleRule;
use App\Modules\Models\VehicleType;

class RiderRequest extends FormRequest
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
       // dd("data",$this->all());
        return [
           
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'last_name' => 'required|string|max:255',
            // 'username' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'emergency_contacts'=>'nullable',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'required|string|min:10|unique:users',
            'dob' => 'nullable',
            'gender' => 'nullable',
            'google_id' => 'nullable',
            'facebook_id' => 'nullable',
            'social_image_url'=>'nullable',

            //Rider's fields
            'rider.experience' => 'required',
            'rider.trained' => 'nullable',

            //Vehicle's fields
            'vehicle.vehicle_type_id' =>  ['required','integer', function ($attribute, $value, $fail) {
                if($value>0)
                {
                    $vehicle_type = VehicleType::find($value);
                    if ( !$vehicle_type) {
                        $fail('The vehicle type does not exist!');
                    }
                }else {$fail('The vehicle type does not exist!');}
            },],
            'vehicle.vehicle_number' => 'required',
            'vehicle.make_year' => 'nullable',
            'vehicle.vehicle_color' => 'nullable',
            'vehicle.brand' => 'nullable',
            'vehicle.model' => 'nullable',

            //Document's fields
            'document.document_number' => 'required',
            'document.type' => 'required',
            'document.image' => 'nullable',
            'document.issue_date' => 'required',
            'document.expiry_date' => 'required',

        ];
    }
}
