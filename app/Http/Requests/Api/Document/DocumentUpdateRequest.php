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
           
            'type' => 'required|string|max:255',        //bluebook, license, passport, citizenship, etc
            'document_number' => 'required|string|max:255',
            'issue_date' => 'required|date|max:255',
            'expiry_date' => 'required|date|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];
    }
}
