<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
class JobApplicationTypeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title'     =>'required|string|regex:/^[A-Za-z-أ-ي-pL\s\-0-9]+$/uu|unique:job_application_types,title,' . $this->id,
        ];
    }
    public function messages()
    {
        return [
            'title.required'        => 'العنوان مطلوب *',
            'title.unique'          => 'لقد تم اضافه هذا التصنيف من قبل ..',
            'title.regex'           => 'لا يجب ان يحتوى التصنيف ع رموز ',
        ];
    }

    public function authorize()
    {
        return true;
    }

}
