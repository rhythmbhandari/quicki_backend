<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateDoubleRule implements Rule
{
    protected $attribute, $value;
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;
        $this->value = $value;
        //Check if the value is double
        try{
           // return ($test = floatval($value))?true:false;
           return preg_match("/^[0-9]+(\.[0-9]{1,10})?$/",$value)?true:false;
        }catch(Throwable $e){
            return false;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->attribute.' must be a numeric/float value!';
    }
}
