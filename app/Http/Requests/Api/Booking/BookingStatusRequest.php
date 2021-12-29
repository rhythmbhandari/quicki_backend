<?php

namespace App\Http\Requests\Api\Booking;

use Illuminate\Foundation\Http\FormRequest;
// use App\Rules\ValidateDoubleRule;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

//models
use App\Modules\Models\Booking;
use App\Modules\Models\User;
use App\Modules\Models\Rider;

class BookingStatusRequest extends FormRequest
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
        return [
           
            'booking_id'=> ['required', function ($attribute, $value, $fail) {
                $booking = Booking::find($value);
                if ( !$booking) {
                    $fail('Booking not found!');
                }
            },],
            'new_status'=>'required|string|in:accepted,running,completed,cancelled',
            'optional_data.rider_id'  => 
                        ['nullable', function ($attribute, $value, $fail) {
                            $rider = Rider::find($value);
                            if ( !$rider) {
                                $fail('Rider not found!');
                            }
                        },],
            'optional_data.cancelled_by_id'  => 
                        ['nullable', function ($attribute, $value, $fail) {
                            $user = User::find($value);
                            if ( !$user) {
                                $fail('User not found!');
                            }
                        },],
            'optional_data.cancelled_by_type'  =>  
                        ['nullable', function ($attribute, $value, $fail) {
                            if ( !($value=="customer" || $value=="rider")) {
                                $fail('The booking can only be cancelled by "customer" or "rider"!');
                            }
                        },],
            'optional_data.cancel_message'  => 'nullable|string',
            'optional_data.distance'=>'integer',
            // 'optional_data.location'

        ];
    }
}
