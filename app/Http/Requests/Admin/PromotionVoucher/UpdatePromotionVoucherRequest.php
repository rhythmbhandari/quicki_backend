<?php

namespace App\Http\Requests\Admin\PromotionVoucher;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Validator;
use App\Rules\ValidateDoubleRule;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

//models
use App\Modules\Models\VehicleType;
use App\Modules\Models\User;

class UpdatePromotionVoucherRequest extends FormRequest
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
           
        //    'user_type' => 'required|string|in:customer,rider',
        //    'code'=>'required|string|unique:promotion_vouchers|max:20',
           'name'=>'required|string|max:30|unique:promotion_vouchers,name,'.$this->route('promotion_voucher'),
           'type'=>'required|string|max:30',
           'max_uses'=>'nullable|integer',
        //   'max_uses_user'=>'integer',
           'worth'=>'required|integer',
        //    'is_fixed'=>'required|boolean',
           'starts_at'=>'required|date',
           'expires_at'=>'required|date',
        //    'status'=>'required|in:active,in_active',
           'description'=>'nullable|string|max:500',
           'price_eligibility'=>'nullable|json',
           'distance_eligibility'=>'nullable|json',
           'eligible_user_ids'=>['nullable','array',function ($attribute, $value, $fail) {
                        $unique_uids = array_unique( $value );
                        $user_count = User::whereIn('id', $unique_uids )->count();
                        if($user_count !== count($unique_uids)){
                            $fail('One of the user id does not exist in DB!');
                        }
                },],


            // 'user_type' =>  ['required', function ($attribute, $value, $fail) {
            //     $vehicle_type = VehicleType::find($value);
            //         if ( !$vehicle_type) {
            //             $fail('The vehicle type does not exist!');
            //         }
            //     },],
          

        ];
    }
}
