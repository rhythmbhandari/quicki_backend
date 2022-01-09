<?php

namespace App\Http\Requests\Admin\NewsletterSubscription;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Validator;
use App\Rules\ValidateDoubleRule;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class UpdateNewsletterRequest extends FormRequest
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
           
            'title'=>'required|string|max:50',
            'body'=>'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048'
          

        ];
    }
}
