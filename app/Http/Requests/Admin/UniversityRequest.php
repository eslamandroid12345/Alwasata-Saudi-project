<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
class UniversityRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title'     =>'required|string|regex:/^[A-Za-z-أ-ي-pL\s\-0-9]+$/uu|unique:universities,title,' . $this->id,
        ];
    }
    public function messages()
    {
        return [
            'title.required'        => 'العنوان مطلوب *',
            'title.unique'          => 'لقد تم اضافه هذه الجامعه من قبل ..',
            'title.regex'           => 'لا يجب ان يحتوى اسم الجامعه  ع رموز ',
        ];
    }

    public function authorize()
    {
        return true;
    }

}
