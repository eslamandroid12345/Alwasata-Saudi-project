<?php

namespace App\Http\Requests\Customer;

use App\HelperFunctions\Helper;
use App\Traits\ResponseAPI;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AskForFundingWeb extends FormRequest
{
    use ResponseAPI;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name'                      => 'required',
            'email'                     => 'email|nullable',/*|unique:customers*/
            'mobile'                    => 'required|numeric|starts_with:5|digits:9',
            'check_source'              => 'required|in:1,0',
            'net_loan_total'            => 'required_if:check_source,1|numeric',
            'installment'               => 'required_if:check_source,1|numeric',
            'funding_years'             => 'required_if:check_source,1|numeric',
            'personal_salary_deduction' => 'required_if:check_source,1|numeric',
            'installment_after_support' => 'required_if:check_source,1|numeric',
            'personal_installment'      => 'required_if:check_source,1|numeric',
            'profit'                    => 'required_if:check_source,1|numeric',
            'personal_profit'           => 'required_if:check_source,1|numeric',
            'personal_net_loan_total'   => 'required_if:check_source,1|numeric',
            'funding_source'            => 'required_if:check_source,1',
            'flexible_loan_total'       => 'required_if:check_source,1',
            'salary_deduction'          => 'required_if:check_source,1',
            'funding_months'            => 'required_if:check_source,1',
            'personal_funding_months'   => 'required_if:check_source,1',
            'product_type_code'         => 'required_if:check_source,1',
        ];
        if ((Helper::checkOptionValue('askforfunding_salary') === 'show') && (Helper::checkValidationValue('request_validation_from_salary') == true) || (Helper::checkValidationValue('request_validation_to_salary') == true)) {
            $rules['salary'] = 'required|integer|min:4';
        }
        else {
            $rules['salary'] = 'nullable|integer|min:4';
        }

        if ((Helper::checkOptionValue('askforfunding_salaryId') === 'show')) {
            $rules['salary_id'] = 'required|integer';
        }
        else {
            $rules['salary_id'] = 'nullable|integer';
        }

        if ((Helper::checkOptionValue('askforfunding_birthDate') == 'show') && (Helper::checkValidationValue('request_validation_from_birth_hijri') == true) || (Helper::checkValidationValue('request_validation_to_birth_hijri') == true)) {
            $rules['birth_hijri'] = 'required|date_format:Y-m-d';
        }
        else {
            $rules['birth_hijri'] = 'nullable|date_format:Y-m-d';
        }

        if ((Helper::checkOptionValue('askforfunding_work') == 'show') && (Helper::checkValidationValue('request_validation_to_work') == true)) {
            $rules['work'] = 'required';
        }
        else {
            $rules['work'] = 'nullable';
        }

        if ((Helper::checkOptionValue('askforfunding_isSupported') == 'show') && (Helper::checkValidationValue('request_validation_to_support') == true)) {
            $rules['is_supported'] = 'required|in:yes,no';
        }
        else {
            $rules['is_supported'] = 'nullable|in:yes,no';
        }

        if ((Helper::checkOptionValue('askforfunding_has_obligations') == 'show') && (Helper::checkValidationValue('request_validation_to_has_obligations') == true)) {
            $rules['has_obligations'] = 'required|in:yes,no';
        }
        else {
            $rules['has_obligations'] = 'nullable|in:yes,no';
        }

        if ((Helper::checkOptionValue('askforfunding_has_financial_distress') == 'show') && (Helper::checkValidationValue('request_validation_to_has_financial_distress') == true)) {
            $rules['has_financial_distress'] = 'required|in:yes,no';
        }
        else {
            $rules['has_financial_distress'] = 'nullable|in:yes,no';
        }

        if ((Helper::checkOptionValue('askforfunding_owning_property') == 'show') && (Helper::checkValidationValue('request_validation_to_owningProperty') == true)) {
            $rules['owning_property'] = 'required|in:yes,no';
        }
        else {
            $rules['owning_property'] = 'nullable|in:yes,no';
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'name.required' => 'الإسم بالكامل إلزامي',
            'email.email'   => 'صيغة البريد الالكتروني غير صحيحة',
            /*'email.unique'  => ' البريد الالكتروني مسجل لدينا بالفعل الرجاء ادخال بريد مختلف',*/
            // calculator requests

            'request_source.required' => 'حقل مصدر المعاملة مطلوب',
            'check_source.required'   => 'مصدر المعاملة مطلوب',
            'check_source.in'         => 'مصدر المعاملة يجب ان يكون اما 0 / 1',

            'net_loan_total.required_if' => 'صافي مبلغ التمويل مطلوب',
            'net_loan_total.numeric'     => 'صافي مبلغ التمويل يقبل أرقام فقط',

            'installment.required_if' => 'القسط الشهري مطلوب',
            'installment.numeric'     => 'القسط الشهري يقبل أرقام فقط',

            'funding_years.required_if' => 'مدة التمويل بالسنوات مطلوب',
            'funding_years.numeric'     => 'مدة التمويل بالسنوات يقبل أرقام فقط',

            'personal_salary_deduction.required_if' => 'نسبة استقطاع صافي الراتب (شخصي) مطلوب',
            'personal_salary_deduction.numeric'     => 'نسبة استقطاع صافي الراتب (شخصي) يقبل أرقام فقط',

            'installment_after_support.required_if' => 'القسط الشهري بعد الدعم مطلوب',
            'installment_after_support.numeric'     => 'القسط الشهري بعد الدعم يقبل أرقام فقط',

            'personal_installment.required_if' => 'القسط الشهري بعد الدعم مطلوب',
            'personal_installment.numeric'     => 'القسط الشهري بعد الدعم يقبل أرقام فقط',

            'profit.required_if' => 'نسبة المرابحة مطلوب',
            'profit.numeric'     => 'نسبة المرابحة يقبل أرقام فقط',

            'personal_profit.required_if' => 'نسبة المرابحة (شخصي) مطلوب',
            'personal_profit.numeric'     => 'نسبة المرابحة (شخصي) يقبل أرقام فقط',

            'personal_net_loan_total.required_if' => 'صافي مبلغ التمويل الشخصي مطلوب',
            'personal_net_loan_total.numeric'     => 'صافي مبلغ التمويل الشخصي يقبل أرقام فقط',

            'flexible_loan_total.required_if' => 'التمويل العقاري (مرن) مطلوب',
            'flexible_loan_total.numeric'     => 'التمويل العقاري مرن يقبل أرقام فقط',

            'salary_deduction.required_if' => 'نسبة استقطاع صافي الراتب مطلوب',
            'salary_deduction.numeric'     => 'نسبة استقطاع صافي الراتب يقبل أرقام فقط',

            'funding_months.required_if' => 'مدة التمويل بالأشهر مطلوب',
            'funding_months.numeric'     => 'مدة التمويل بالأشهر يقبل أرقام فقط',

            'personal_funding_months.required_if' => 'مدة التمويل بالأشهر شخصي مطلوب',
            'personal_funding_months.numeric'     => 'مدة التمويل بالأشهر شخصي يقبل أرقام فقط',

            // end calculator requests
            'mobile.required'                     => 'حقل رقم الجوال الزامي',
            'mobile.numeric'                      => 'رقم الجوال لابد ان يكون أرقام',
            'mobile.digits'                       => 'رقم الجوال لابد ان يكون 9 أرقام',
            'mobile.starts_with'                  => 'رقم الجوال يجب أن يبدأ ب رقم 5',
            'salary.required'                     => 'حقل راتب العميل إلزامي',
            'salary.integer'                      => 'حقل راتب العميل يقبل أرقام فقط',
            'salary_id.required'                  => 'حقل جهة نزول الراتب إلزامي',
            'salary_id.integer'                   => 'حقل جهة نزول الراتب يقبل رقم صحيح فقط',
            'salary.min'                          => 'حقل راتب العميل يجب ان يحتوي على الأقل على أربعة أرقام',
            'birth_hijri.required'                => 'حقل تاريخ الميلاد الزامي',
            'birth_hijri.date_format'             => ' صيغة تاريخ الميلاد يجب ان تكون Y-m-d',
            'work.required'                       => 'جهة العمل مطلوبة',
            'is_supported.required'               => 'حقل مستحقي الدعم إلزامي',
            'is_supported.in'                     => 'حقل مستحقي الدعم يقبل yes / no فقط',
            'has_obligations.required'            => 'حقل الالتزام من عدمه مطلوب',
            'has_obligations.in'                  => 'حقل الالتزام من عدمه يقبل yes / no فقط',
            'has_financial_distress.required'     => 'حقل التعثرات من عدمه مطلوب',
            'has_financial_distress.in'           => 'حقل التعثرات من عدمه يقبل yes / no فقط',
            'owning_property.required'            => 'حقل امتلاك العقار من عدمه مطلوب',
            'owning_property.in'                  => 'حقل امتلاك العقار من عدمه يقبل yes / no فقط',

        ];
        return $messages;
    }

    public function failedValidation(Validator $validator) { throw new HttpResponseException($this->error($validator->errors()->first(), 422)); }
}
