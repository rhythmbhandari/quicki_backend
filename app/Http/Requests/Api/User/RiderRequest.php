<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\ValidateDoubleRule;


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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'last_name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'required|string|min:10|unique:users',
            'dob' => 'nullable',
            'gender' => 'nullable',
            'google_id' => 'nullable',
            'facebook_id' => 'nullable',

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
            'document.issue_date' => 'required',
            'document.expiry_date' => 'required',

        ];
    }
}
