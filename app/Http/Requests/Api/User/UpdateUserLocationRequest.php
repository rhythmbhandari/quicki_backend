<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use App\Rules\ValidateDoubleRule;


class UpdateUserLocationRequest extends FormRequest
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
           
            'location.home.name' => 'required_with:location.home.latitude,location.home.latitude|string|max:255',
            'location.home.latitude' => 'required_with:location.home.longitude,location.home.name|numeric',
            'location.home.longitude' => 'required_with:location.home.latitude,location.home.name|numeric',

            'location.work.name' => 'required_with:location.work.latitude,location.work.latitude|string|max:255',
            'location.work.latitude' => 'required_with:location.work.longitude,location.work.name|numeric',
            'location.work.longitude' => 'required_with:location.work.latitude,location.work.name|numeric',


        ];
    }

    public function messages()
    {
        return [
            'location.home.name.required' => 'Home address name is required!',
            'location.home.latitude.required' => 'Home latitude is required!',
            'location.home.longitude.required' => 'Home longitude is required!',

            'location.work.name.required' => 'Work address name is required!',
            'location.work.latitude.required' => 'Work address name is required!',
            'location.work.longitude.required' => 'Work address name is required!',
        ];
    }

}
