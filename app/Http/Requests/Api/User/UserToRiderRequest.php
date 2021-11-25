<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\ValidateDoubleRule;


class UserToRiderRequest extends FormRequest
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
           
            //Rider's fields
            'rider.experience' => 'required',
            'rider.trained' => 'nullable',

            //Vehicle's fields
            'vehicle.vehicle_type_id' => 'required',
            'vehicle.vehicle_number' => 'required',
            'vehicle.make_year' => 'nullable',
            'vehicle.vehicle_color' => 'nullable',
            'vehicle.brand' => 'nullable',
            'vehicle.model' => 'nullable',

            //Document's fields
            'document.document_number' => 'required',
            'document.type' => 'required',
            'document.image' => 'required',
            'document.issue_date' => 'required|date',
            'document.expiry_date' => 'required|date',

        ];
    }
}
