<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class JobRequest extends FormRequest
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
        return [
            'first_name'        =>'required|string|min:3',
            'sur_name'          =>'required|string|min:3',
            'date_of_birth'     =>['required','date_format:Y-m-d','before:now'],
            'email'             =>'required|email',
            'phone'             =>['required','numeric','regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'salary'            =>'required',
            'graduation_date'   =>['nullable','date_format:Y-m-d','before:now'],
           // 'grade'             =>'nullable',
            'notes'             =>'nullable',
           // 'specialization'    =>'nullable',
            'possible_start_date'=>['required','date_format:Y-m-d','after:now'],

            'city_id'           =>['required'],//,'exists:cities,id'
            'nationality_id'    =>['required'],//,'integer','exists:nationalities,id'
            'university_id'     =>['nullable'],//,'exists:universities,id'
            'job_id'            =>['nullable','exists:job_titles,id'],

            'gender'              =>['nullable'],
            'level'               =>['nullable'],
            //'level_specialization'=>['nullable'],
            'duration'            =>['nullable'],
            'experance_years'     =>['nullable'],

            // 'courses.*'             =>'required',
            // 'course_start_date.*'   =>'required|date_format:Y-m-d|before:course_end_date',
            // 'course_end_date.*'     =>'required|date_format:Y-m-d|after:course_start_date',
            // 'experances.*'          =>'required',
            // 'experance_start_date.*'=>'required|date_format:Y-m-d|before:experance_end_date',
            // 'experance_end_date.*'  =>'required|date_format:Y-m-d|after:experance_start_date',
        ];

    }
    public function messages()
    {
        return [
            'first_name.required'   => 'الاسم الاول إلزامي',
            'first_name.string'   => 'الاسم الاول لا يجب ان يكون ارقام فقط',
            'first_name.min'   => 'يجب ان يزيد الاسم الاول عن 3 احرف',
            'sur_name.required'     => 'اسم العائله إلزامي',
            'sur_name.string'   => 'اسم العائله لا يجب ان يكون ارقام فقط',
            'sur_name.min'   => 'يجب ان يزيد اسم العائله عن 3 احرف',
            'email.required'        => 'البريد الالكترونى إلزامي',
            'email.email'           => 'صيغة البريد الالكتروني غير صحيحة',
            'date_of_birth.required'    => 'حقل تاريخ الميلاد إلزامي',
            'date_of_birth.date_format' => ' صيغة التاريخ يجب ان تكون Y-m-d',
            'date_of_birth.before' => '  تاريخ الميلاد يجب ان يكون تاريخ  سابق لتاريخ الان',
            'phone.required' => 'رقم الجوال الزامى',
            'phone.regex' => '  تاكد من ادخال رقم الجوال بشكل صحيح',
            'salary.required' => 'الراتب المتوقع  الزامى',
            'graduation_date.required'    => 'حقل تاريخ التخرج إلزامي',
            'graduation_date.date_format' => ' صيغة تاريخ التخرج يجب ان تكون Y-m-d',
            'graduation_date.before' => '  تاريخ التخرج يجب ان يكون تاريخ  سابق لتاريخ الان',
            'grade.required'=>'التقدير الزامى',
            'notes.required'=>'قم بادخال كلمه عن نفسك :)',
            'level_specialization.required'=>'تخصص المؤهل الزامى',
            'specialization.required'=>'التخصص المرغوب الزامى',
            'level.required'=>' المؤهل الزامى',
            'duration.required'=>' طبيعه الدوام الزامى',
            'experance_years.required'=>'  سنوات الخبره  الزامى',

            'possible_start_date.required'    => 'حقل التاريخ المناسب لاستلام الوظيفه إلزامي',
            'possible_start_date.date_format' => ' صيغة تاريخ  يجب ان تكون Y-m-d',
            'possible_start_date.after' => '  التاريخ المناسب لاستلام الوظيفه يجب ان يكون تاريخ  لاحق لتاريخ الان',

            'city_id.required'=>'اختيار الدوله الزامى',
            'nationality_id.required'=>'اختيار الجنسيه الزامى',
            'university_id.required'=>'اختيار الجامعه الزامى',
            'job_id.required'=>'اختيار المُسمى الوظيفى الزامى',

            // 'courses.*.required'=>'تاكد من ادخال الدورات',
            // 'course_start_date.*.required'    => 'تاكد من ادخال تاريخ بدايه الدورات',
            // 'course_start_date.*.date_format' => ' صيغة تاريخ بدايه الدورات  يجب ان تكون Y-m-d',
            // 'course_start_date.*.before' => '  تاريخ بدايه الدورات  يجب ان يكون تاريخ  سابق لتاريخ نهايه الدورات',
            // 'course_end_date.*.required'    => 'تاكد من ادخال تاريخ نهايه الدورات',
            // 'course_end_date.*.date_format' => ' صيغة تاريخ نهايه الدورات  يجب ان تكون Y-m-d',
            // 'course_end_date.*.after' => '  تاريخ نهايه الدورات  يجب ان يكون تاريخ  لاحق لتاريخ بدايه الدورات',

            // 'experances.*.required'=>'تاكد من ادخال الخبرات',
            // 'experance_start_date.*.required'    => 'تاكد من ادخال تاريخ بدايه الخبرات',
            // 'experance_start_date.*.date_format' => ' صيغة تاريخ بدايه الخبرات  يجب ان تكون Y-m-d',
            // 'experance_start_date.*.before' => '  تاريخ بدايه الخبرات  يجب ان يكون تاريخ  سابق لتاريخ نهايه الخبرات',
            // 'experance_end_date.*.required'    => 'تاكد من ادخال تاريخ نهايه الخبرات',
            // 'experance_end_date.*.date_format' => ' صيغة تاريخ نهايه الخبرات  يجب ان تكون Y-m-d',
            // 'experance_end_date.*.after' => '  تاريخ نهايه الخبرات  يجب ان يكون تاريخ  لاحق لتاريخ بدايه الخبرات',
        ];
    }

    // public function failedValidation(Validator $validator) { throw new HttpResponseException($this->error($validator->errors()->first(), 422)); }

}
