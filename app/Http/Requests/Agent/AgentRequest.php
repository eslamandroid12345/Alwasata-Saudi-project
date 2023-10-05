<?php

namespace App\Http\Requests\Agent;

use App\Models\Classification;
use Illuminate\Foundation\Http\FormRequest;

class AgentRequest extends FormRequest
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
        $rules=[
            // 'financing_or_tsaheel' => 'required_if:realeva,نعم',
            // 'evaluation_amount' => 'required_if:realeva,نعم'
        ];

        // $positive_classification_ids=Classification::where('type',1)->pluck('id')->toArray();

        // if(in_array($this->reqclass,$positive_classification_ids)){
        //     $rules=array_merge($rules,[
        //         'reqtyp' => 'required',
        //     ]);
        // }

        return $rules;
    }

    public function messages()
    {
        return[
            'reqtyp.required' => 'نوع الطلب مطلوب ف حالة التصنيف ايجابي',
            'financing_or_tsaheel.required_if' => 'التقييم مطلوب ف حالة تم التقييم',
            'evaluation_amount.required_if' => 'مبلغ التقييم مطلوب ف حالة تم التقييم'
        ];
    }
}
