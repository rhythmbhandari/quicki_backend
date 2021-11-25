<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\ValidateDoubleRule;


class UserRequest extends FormRequest
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
            'last_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'required|string|min:10|unique:users',
            'dob' => 'nullable',
            'gender' => 'nullable',
            'google_id' => 'nullable|unique:users',
            'facebook_id' => 'nullable|unique:users'

        ];
    }
}
