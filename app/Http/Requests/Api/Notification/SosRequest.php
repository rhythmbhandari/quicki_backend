<?php

namespace App\Http\Requests\Api\Notification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidateDoubleRule;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Modules\Models\Booking;

class SosRequest extends FormRequest
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
           
            'title'=>'required|string',
            'message'=>'required|string',
            'booking_id' =>  ['required', function ($attribute, $value, $fail) use ($user_id, $rider_id) {
                $booking = Booking::where('id', $value)->where(function($q) use ($user_id, $rider_id){
                    $q->where('user_id',$user_id)
                    ->orWhere('rider_id',$rider_id);
                })->first();
                if ( !$booking) {
                    $fail('The booking does not exist!');
                }
                },],
            //Stoppage
            'location.name' => [ 'nullable','string'  ],
            'location.latitude' => [ new ValidateDoubleRule() ],
            'location.longitude' => [ new ValidateDoubleRule() ],
        ];
    }
}
