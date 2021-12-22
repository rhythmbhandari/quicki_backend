<?php

namespace App\Http\Requests\Api\Notification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidateDoubleRule;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Modules\Models\Sos;

class EventRequest extends FormRequest
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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $rider_id = -999;
        if($user->rider)
        {
            $rider_id=$user->rider->id;
        }
        return [
           
            'message'=>'required|string',
            'sos_id' =>  ['required', function ($attribute, $value, $fail)  {
                $sos = Sos::find($value);
                if ( !$sos) {
                    $fail('The sos does not exist!');
                }
                },],
        ];
    }
}
