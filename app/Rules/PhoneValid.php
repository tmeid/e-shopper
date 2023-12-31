<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneValid implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        //
        $reg = '/(\+84|0)[35789]([0-9]{8})$/';
        return preg_match($reg, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Số điện thoại không hợp lệ';
    }
}
