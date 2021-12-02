<?php

namespace App\Http\Requests\Api\Review;

use Illuminate\Foundation\Http\FormRequest;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Rules\ValidateDoubleRule;
use App\Modules\Models\VehicleType;

use App\Modules\Models\Booking;

class ReviewRequest extends FormRequest
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
       // dd("data",$this->all());
        return [
           
            'rate' => 'required|numeric',
            'comment' => 'required|string',
            'reviewed_by_role' =>  ['required', function ($attribute, $value, $fail) {
                if ( ! ($value=="customer" || $value=="rider")  ) {
                    $fail('The review can only be made by either the customer or the rider!');
                }
            },],
            'booking_id' =>  ['required', function ($attribute, $value, $fail) {
                $booking = Booking::find($value);
                if ( ! $booking  ) {
                    $fail('No booking found for the given id!');
                }
                // Check if booking status is completed/cancelled (optional)
                else if( ! ($booking->status == "completed" || $booking->status == "cancelled" ) )
                {
                    $fail('Review cannot be created as the booking is still active!');
                }
                else{}
            },],

        ];
    }
}
