<?php

namespace App\Http\Requests\Api\Document;

use Illuminate\Foundation\Http\FormRequest;



class DocumentRequest extends FormRequest
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
           
            'documentable_type' => //allowed
                ['required', function ($attribute, $value, $fail) {
                            if($value == "rider")
                            {
                               $value = "App\Modules\Models\Rider";
                            }
                            else if($value == "vehicle")
                            {
                                $value = "App\Modules\Models\Vehicle";
                            }
                            else if($value == "customer" || $value == "user")
                            {
                                $value = "App\Modules\Models\User";
                            }
                            else{
                                $fail('Invalid value given for the documentable type! Acceptable values  are user, rider, vehicle or customer!');
                            }
                        },],
            'documentable_id' => 'required|integer', 
            'type' => 'required|string|max:255',        //bluebook, license, passport, citizenship, etc
            'document_number' => 'required|string|max:255',
            'issue_date' => 'nullable|date|max:255',
            'expiry_date' => 'nullable|date|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];
    }
}
