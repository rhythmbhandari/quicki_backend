<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidImageRatio implements Rule
{
    protected $min_val, $max_val, $uploaded_val, $key;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($key="key", $min_val, $max_val, $uploaded_val)
    {
        $this->min_val = $min_val;                      //Minimum allowed image ratio
        $this->max_val = $max_val;                      //Maximum allowed image ratio
        $this->uploaded_val = $uploaded_val;           //The image ratio of uploaded image
        $this->key = $key;
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
        //dd("IMAGE RATION VALIDATION: VALUE:",$this->uploaded_val," MIN:",$this->min_val, " MAX: ", $this->max_val);
        return $this->uploaded_val  >= $this->min_val && $this->uploaded_val  <= $this->max_val;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The image ratio of '.$this->key.' must be between '.$this->min_val.' and '.$this->max_val.'! Uploaded Image\'s Ratio was: '.$this->uploaded_val ;
    }
}
