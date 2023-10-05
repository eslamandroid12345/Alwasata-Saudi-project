<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CalculationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'birth_hijri'             => 'required|date_format:"Y-m-d"',
            'salary'                  => 'required|numeric',
            'work_caculater'          => 'required',
            'military_rank_caculater' => 'required_if:work_caculater,عسكري|integer',
            'product_type_id'         => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'birth_hijri.required'                => 'تاريخ الميلاد بالهجري مطلوب',
            'birth_hijri.date_format'             => 'تاريخ الميلاد يجب ان يكون هجري والتنسيق يوم / شهر / سنة',
            'salary.required'                     => 'الراتب الشهري مطلوب',
            'work_caculater.required'             => 'جهة العمل مطلوبة',
            'military_rank_caculater.required_if' => 'الرتبة العسكرية مطلوبة',
            'military_rank_caculater.integer'     => 'military rank caculater must be an integer value',
            'product_type_id.required'            => 'نوع المنتج مطلوب',
            'product_type_id.integer'             => 'product type id must be an integer value',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code'    => 422,
            'status'  => false,
            'message' => $validator->errors()->first(),
            'payload' => null,
        ], 422));
    }
}
