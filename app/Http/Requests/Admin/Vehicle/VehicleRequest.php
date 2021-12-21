<?php

namespace App\Http\Requests\Admin\Vehicle;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Validator;


use Illuminate\Contracts\Validation\Validator;
use \Carbon\Carbon;

//models
use App\Modules\Models\VehicleType;

class VehicleRequest extends FormRequest
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
            'make_year' => 'required|digits:4|integer|min:1950|max:' . Carbon::today()->year,

        ];
    }
}
