<?php

namespace App\Http\Requests\Admin\Booking;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Modules\Models\User;
//models


class BookingRequest extends FormRequest
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
            'user_id' =>  ['required', function ($attribute, $value, $fail) {
                $rider = User::find($value)->rider;
                if ($rider && $rider->id == request('rider_id'))
                    $fail("User and rider cannot be same!");
            },],
            'rider_id' => 'required_if:status,accepted|required_if:status,running|required_if:status,completed',
            'vehicle_type_id' => 'required',
            'start_location' => 'required',
            'end_location' => 'required',
            'start_coordinate.longitude' => 'required',
            'start_time' => 'required_if:status,running|required_if:status,completed|date',
            'end_time' => 'required_if:status,completed|date|after:start_time',
            'start_coordinate.latitude' => 'required',
            'end_coordinate.longitude' => 'required',
            'end_coordinate.latitude' => 'required',
            'duration' => 'required',
            'distance' => 'required',
            'status' => ['required']

        ];
    }
}


// function ($attribute, $value, $fail) {
//     switch ($value) {
//         case 'accepted':
//             if (request('rider_id') == null) {
//                 $fail("Rider field is required when status accepted!");
//             }
//             break;
//         case 'running':
//             if (request('rider_id') == null || request('start_time') == null) {
//                 $fail("Rider and start time is required when status is running!");
//             }
//             break;
//         case 'completed':
//             if (request('rider_id') == null || request('start_time') == null || request('end_time' == null)) {
//                 $fail("Rider, start time and end time is required when status is completed!");
//             }
//             break;
//     }
// },