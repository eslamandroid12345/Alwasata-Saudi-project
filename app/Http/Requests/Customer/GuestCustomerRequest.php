<?php

namespace App\Http\Requests\Customer;

use App\Traits\ResponseAPI;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GuestCustomerRequest extends FormRequest
{
    use ResponseAPI;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'mobile'   => 'required|numeric|starts_with:5|digits:9',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'mobile.required'    => 'حقل رقم الجوال إلزامي',
            'mobile.numeric'     => 'رقم الجوال لابد ان يكون أرقام',
            'mobile.digits'      => 'رقم الجوال لابد ان يكون 9 أرقام',
            'mobile.starts_with' => 'رقم الجوال يجب أن يبدأ ب رقم 5',
            'password.required'  => 'حقل كلمة المرور إلزامي',
        ];
    }

    public function failedValidation(Validator $validator) { throw new HttpResponseException($this->error($validator->errors()->first(), 422)); }
}
