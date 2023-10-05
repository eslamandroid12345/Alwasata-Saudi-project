<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VacancyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'is_salary_deduction' => 'required',
            'is_vacations_deduction' => 'required',
            'type' => 'required',
            'gender' => 'required',
            'days' => 'required',
            'days_commitment' => 'required',
            'count' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => ' إسم الأجازة مطلوب *',
            'is_salary_deduction.required' => ' هل تخصم من الراتب مطلوبة مطلوب *',
            'is_vacations_deduction.required' => ' هل تخصم من رصيد الأجازات مطلوبة *',
            'type.required' => ' نوع الأجازة مطلوبة *',
            'gender.required' => ' نوع المستخدم مطلوب *',
            'days.required' => ' عدد أيام الأجازة مطلوبة *',
            'days_commitment.required' => ' حالة عدد الأيام مطلوبة *',
            'count.required' => ' عدد مرات طلب الأجازة مطلوب *',
        ];
    }

    public function authorize()
    {
        return true;
    }

}
