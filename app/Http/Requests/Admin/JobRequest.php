<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
class JobRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title'     =>'required|string|regex:/^[A-Za-z-أ-ي-pL\s\-0-9]+$/uu|unique:job_titles,title,' . $this->id,
        ];
    }
    public function messages()
    {
        return [
            'title.required'        => 'العنوان مطلوب *',
            'title.unique'          => 'لقد تم اضافه هذا المسمى من قبل ..',
            'title.regex'           => 'لا يجب ان يحتوى اسم المسمى  ع رموز ',
        ];
    }

    public function authorize()
    {
        return true;
    }

}
