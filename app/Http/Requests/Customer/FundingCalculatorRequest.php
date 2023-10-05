<?php

namespace App\Http\Requests\Customer;

use App\Traits\ResponseAPI;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FundingCalculatorRequest extends FormRequest
{
    use ResponseAPI;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'birth_date_hijri' => 'required|date_format:Y-m-d',
            'work_source'      => 'required',
            'military_rank'    => 'required_if:work_source,عسكري',
            'salary'           => 'required|numeric',
            'product_type_id'  => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'birth_date_hijri.required'    => 'حقل تاريخ الميلاد الهجري إلزامي',
            'birth_date_hijri.date_format' => ' صيغة التاريخ يجب ان تكون Y-m-d',
            'work_source.required'         => 'جهة العمل مطلوبة',
            'military_rank.required_if'    => 'الرتب العسكرية مطلوبة',
            'salary.required'              => 'الراتب الشهري مطلوب',
            'salary.numeric'               => 'الراتب الشهري يجب ان يكون أرقام',
            'product_type_id.required'     => 'نوع المنتج مطلوب',
            'product_type_id.integer'      => 'نوع المنتج يجب ان يكون رقم صحيح',
        ];
    }

    public function failedValidation(Validator $validator) { throw new HttpResponseException($this->error($validator->errors()->first(), 422)); }

}
