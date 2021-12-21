<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Validator;


use Illuminate\Contracts\Validation\Validator;
use \Carbon\Carbon;


class CustomerRequest extends FormRequest
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
            'phone' => 'required|integer|unique:users,phone,' . $this->route('customer'),
        ];
    }
}
