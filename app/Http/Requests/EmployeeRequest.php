<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'company_contract' =>'file|mimes:jpg,jpeg,png,bmp,tiff,doc,docx,pdf,docx,zip',
            'contract_file' =>'file|mimes:jpg,jpeg,png,bmp,doc,pdf,docx,zip',
            'control_section_id'    => 'required',
            'control_subsection_id'    => 'required',
            'control_guaranty_id'    => 'required',
            'control_company_id'    => 'required',
            'control_identity_id'    => 'required',
            'control_insurances_id'    => 'required',
            'control_work_id'    => 'required',
            'control_medical_id'    => 'required',
            'work_date'    => 'required',
            'work_date_2'    => 'required',
            'work_end_date'    => 'required',
            'direct_date'    => 'required',
            'job'    => 'required',
            'job_number'    => 'required',
            'job_application'    => 'required',
            'residence_number'    => 'required',
            'residence_end_date'    => 'required',
            'notes'    => 'required',
            'custody'    => 'required',
        ];
    }
    public function messages()
    {
        $messages = [
            'control_section_id.required'     => 'التاريخ مطلوب',
            'control_subsection_id.required'     => 'التاريخ مطلوب',
            'control_guaranty_id.required'     => 'التاريخ مطلوب',
            'control_company_id.required'     => 'التاريخ مطلوب',
            'control_identity_id.required'     => 'التاريخ مطلوب',
            'control_insurances_id.required'     => 'التاريخ مطلوب',
            'control_work_id.required'     => 'التاريخ مطلوب',
            'control_medical_id.required'     => 'التاريخ مطلوب',
            'work_date.required'     => 'التاريخ مطلوب',
            'work_date_2.required'     => 'التاريخ مطلوب',
            'work_end_date.required'     => 'التاريخ مطلوب',
            'direct_date.required'     => 'التاريخ مطلوب',
            'job.required'     => 'التاريخ مطلوب',
            'job_number.required'     => 'التاريخ مطلوب',
            'job_application.required'     => 'التاريخ مطلوب',
            'residence_number.required'     => 'التاريخ مطلوب',
            'residence_end_date.required'     => 'التاريخ مطلوب',
            'notes.required'     => 'التاريخ مطلوب',
            'custody.required'     => 'التاريخ مطلوب',
        ];
    }
    public function authorize()
    {
        return true;
    }
}
