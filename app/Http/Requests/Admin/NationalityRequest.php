<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
class NationalityRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title'     =>'required|string|regex:/^[A-Za-z-أ-ي-pL\s\-0-9]+$/uu|unique:nationalities,title,' . $this->id,
        ];
    }
    public function messages()
    {
        return [
            'title.required'        => 'العنوان مطلوب *',
            'title.unique'          => 'لقد تم اضافه هذه الجنسيه من قبل ..',
            'title.regex'           => 'لا يجب ان يحتوى الجنسيه  ع رموز ',
        ];
    }

    public function authorize()
    {
        return true;
    }

}
