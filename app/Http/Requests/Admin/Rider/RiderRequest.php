<?php

namespace App\Http\Requests\Admin\Rider;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Validator;


use Illuminate\Contracts\Validation\Validator;
use \Carbon\Carbon;


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
        // dd($this->route('rider'));
        return [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'username' => 'required|string|max:255|unique:users,username,' . $this->route('rider'),
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->route('rider'),
            'phone' => 'required|integer|unique:users,phone,' . $this->route('rider'),
            'dob' => 'nullable',
            'gender' => 'nullable',
        ];
    }
}
