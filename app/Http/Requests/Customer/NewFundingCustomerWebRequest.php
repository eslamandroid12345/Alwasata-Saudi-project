<?php

namespace App\Http\Requests\Customer;

use App\Traits\ResponseAPI;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewFundingCustomerWebRequest extends FormRequest
{
    use ResponseAPI;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'          => 'required',
            'mobile'        => 'required|numeric|starts_with:5|digits:9',
            'birth_date'    => 'required',
            'salary'        => 'required|numeric',
            'work'          => 'required',
            'email'         => 'required',
            //            'net_loan_total'    => 'required|numeric',
            //            'installment'       => 'required|numeric',
            //            'funding_years'       => 'required|numeric',
            //            'personal_salary_deduction'       => 'required|numeric',
            //            'installment_after_support'       => 'required|numeric',
            //            'personal_installment'       => 'required|numeric',
            //            'profit'       => 'required|numeric',
            //            'personal_profit'       => 'required|numeric',
            //            'personal_net_loan_total'       => 'required|numeric',
            //            'funding_source'       => 'required',
            //            'flexible_loan_total'       => 'required',
            //            'salary_deduction'       => 'required',
            //            'funding_months'       => 'required',
            //            'personal_funding_months'       => 'required',
            //            'product_type_code'       => 'required',
            //            'first_batch'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'             => 'حقل الإسم إلزامي',
            'mobile.required'           => 'حقل رقم الجوال الزامي',
            'mobile.numeric'            => 'رقم الجوال لابد ان يكون أرقام',
            'mobile.digits'             => 'رقم الجوال لابد ان يكون 9 أرقام',
            'mobile.starts_with'        => 'رقم الجوال يجب أن يبدأ ب رقم 5',
            'birth_date.required'       => 'تاريخ الميلاد الهجري الزامي',
            'salary.required'           => 'حقل الراتب الزامي',
            'salary.numeric'            => 'حقل الراتب يجب ان يكون عدداً صحيحا',
            'work.required'             => 'حقل جهة العمل إلزامي',
            'military_rank.required_if' => 'حقل جهة العمل العسكرية إلزامي',
            'email.required'            => 'حقل البريد الإلكتروني إلزامي',
            //            'product_type_id.required'   => 'حقل نوع المنتج إلزامي',
            //            'net_loan_total.required'         => 'صافي مبلغ التمويل مطلوب',
            //            'net_loan_total.numeric'         => 'صافي مبلغ التمويل يقبل أرقام فقط',
            //            'installment.required'         => 'القسط الشهري مطلوب',
            //            'installment.numeric'         => 'القسط الشهري يقبل أرقام فقط',
            //            'funding_years.required'         => 'مدة التمويل بالسنوات مطلوب',
            //            'funding_years.numeric'         => 'مدة التمويل بالسنوات يقبل أرقام فقط',
            //            'personal_salary_deduction.required'         => 'نسبة استقطاع صافي الراتب (شخصي) مطلوب',
            //            'personal_salary_deduction.numeric'         => 'نسبة استقطاع صافي الراتب (شخصي) يقبل أرقام فقط',
            //            'installment_after_support.required'         => 'القسط الشهري بعد الدعم مطلوب',
            //            'installment_after_support.numeric'         => 'القسط الشهري بعد الدعم يقبل أرقام فقط',
            //            'personal_installment.required'         => 'القسط الشهري بعد الدعم مطلوب',
            //            'personal_installment.numeric'         => 'القسط الشهري بعد الدعم يقبل أرقام فقط',
            //            'profit.required'         => 'نسبة المرابحة مطلوب',
            //            'profit.numeric'         => 'نسبة المرابحة يقبل أرقام فقط',
            //            'personal_profit.required'         => 'نسبة المرابحة (شخصي) مطلوب',
            //            'personal_profit.numeric'         => 'نسبة المرابحة (شخصي) يقبل أرقام فقط',
            //            'personal_net_loan_total.required'         => 'صافي مبلغ التمويل الشخصي مطلوب',
            //            'personal_net_loan_total.numeric'         => 'صافي مبلغ التمويل الشخصي يقبل أرقام فقط',
            //            'flexible_loan_total.required'         => 'التمويل العقاري (مرن) مطلوب',
            //            'flexible_loan_total.numeric'         => 'التمويل العقاري مرن يقبل أرقام فقط',
            //            'salary_deduction.required'         => 'نسبة استقطاع صافي الراتب مطلوب',
            //            'salary_deduction.numeric'         => 'نسبة استقطاع صافي الراتب يقبل أرقام فقط',
            //            'funding_months.required'         => 'مدة التمويل بالأشهر مطلوب',
            //            'funding_months.numeric'         => 'مدة التمويل بالأشهر يقبل أرقام فقط',
            //            'personal_funding_months.required'         => 'مدة التمويل بالأشهر شخصي مطلوب',
            //            'personal_funding_months.numeric'         => 'مدة التمويل بالأشهر شخصي يقبل أرقام فقط',
            //            'first_batch.required'         => 'حقل قيمة الدفعة الأولي مطلوب',
            //            'first_batch.numeric'         => 'حقل قيمة الدفعة الأولي يقبل أرقام فقط',

        ];
    }

    public function failedValidation(Validator $validator) { throw new HttpResponseException($this->error($validator->errors()->first(), 422)); }
}
