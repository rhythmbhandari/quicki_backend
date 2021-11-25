<?php

namespace App\Http\Requests\Api\Vehicle;

use Illuminate\Foundation\Http\FormRequest;


//models
use App\Modules\Models\VehicleType;

class VehicleRequest extends FormRequest
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
            'vehicle_type_id' =>  ['required', function ($attribute, $value, $fail) {
                $vehicle_type = VehicleType::find($value);
                    if ( !$vehicle_type) {
                        $fail('The vehicle type does not exist!');
                    }
                },],
            "vehicle_number"=>'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            "make_year"=>'nullable|string',
            "brand"=>"required|string",
            "model"=>"required|string",
     
        ];
    }
}
