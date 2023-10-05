<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
class TypeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'value'          =>'required|string|regex:/^[A-Za-z-أ-ي-pL\s\-0-9]+$/uu|unique:real_types,value,' . $this->id,
            'parent_id'      =>'required',
        ];
    }
    public function messages()
    {
        return [
            'value.required'        => ' عنوان النوع مطلوب *',
            'value.unique'          => 'لقد تم اضافه هذا النوع من قبل ..',
            'value.regex'           => 'لا يجب ان يحتوى نوع العقار ع رموز ',
            'parent_id.required'    => ' التصنيف مطلوب *',
        ];
    }

    public function authorize()
    {
        return true;
    }

}
