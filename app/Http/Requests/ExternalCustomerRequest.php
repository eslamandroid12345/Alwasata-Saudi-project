<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExternalCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules= [
            'name' =>'required|min:10|max:40',
            'mobile' =>'required|starts_with:05|digits:10|numeric',
            'id_number' =>'required|digits:10|numeric',
            'basic_salary' =>'required|numeric|min:0|max:9999999',
            'has_obligations' =>'required',
            'obligations_value' =>'required_if:has_obligations,yes',
            'duration_of_obligations' =>'required_if:has_obligations,yes',
            'work' =>'required',
            'askary_id' =>'required_if:work,1',
            'military_rank'=>'required_if:work,1',
            'is_support' =>'required',
            'hiring_date' =>'required|date',
            'salary' =>'required|numeric|min:0|max:9999999',


            'madany_work'=>'required_if:work,2',
            'job_title'=>'required_if:madany_work,'.$this->madany_work,
            'salary_source'=>'required',
            'birth' =>'required|date',

            'notes' =>'min:10|max:300',
        ];

        if($this->has_obligations == 'yes')
        {
            $rules=array_merge($rules,[
                'obligations_value'=>'numeric|min:0|max:9999999',
                'duration_of_obligations'=>'numeric|min:0|max:9999999',
            ]);
        }

        if(isset($this->job_title))
        {
            $rules=array_merge($rules,[
                'job_title'=>'min:4|max:50',
            ]);
        }

        return $rules;

    }

    public function messages()
    {
        return[
            'required' => 'من فضلك أدخل الحقل',
            'name.min' => 'يجب الا يقل الاسم عن 10 احرف',
            'mobile.starts_with' => 'رقم الجوال يجب أن يبدأب 05',
            'mobile.digits' => 'رقم الجوال يجب ان يتكوم من 10 ارقام',
            'id_number.digits' => "رقم الهوية يجب ان يتكون من 10أرقام",
            'digits:9' => 'يجب ان يتكون رقم الجوال من 9 أرقام',
            'numeric' => 'يجب ان يكون ارقام وليس احرف',
            'salary.min' => 'يجب ان تكون القيمة موجبة وليست سالبه',
            'salary.max' => 'يجب الا يتخطي الرقم حاجز المليون',
            'basic_salary.min' => 'يجب ان تكون القيمة موجبة وليست سالبه',
            'basic_salary.max' => 'يجب الا يتخطي الرقم حاجز المليون',
        ];
    }
}
