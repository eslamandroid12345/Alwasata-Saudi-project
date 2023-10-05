@extends('layouts.content')

@section('title')
سجل حاسبة التمويل
@endsection

@section('css_style')

<style>
    .textCard {
        font-weight: bold;
        color: #003366;
        font-size: small;
    }

    .subtextCard {
        color: black;
        font-size: small;
    }
</style>
@endsection

@section('customer')


@if ($histories->count() == 0)

<br><br>
<h3 style="text-align: center;">لايوجد أي سجلات</h3>


@else
@foreach($histories as $history)

<div class="card text-center">
    <div class="card-header">
        {{$history->user_name}} - {{$history->switch_name}}
    </div>

    <div class="card-body">

        @if ($history->is_there_result == 0)
        <h5 style="text-align: center;">لايوجد عروض تمويلية</h5>
        @else


        <div class="row">
            <div class="col-md-3 ">
                <span class="textCard">
                 البرنامج:
                </span>
                <span class="subtextCard">
                    {{$history->program_name}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    تاريخ الميلاد:
                </span>
                <span class="subtextCard">
                    {{$history->birth_hijri}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    صافي الراتب:
                </span>
                <span class="subtextCard">
                    {{$history->salary}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    جهة العمل:
                </span>
                <span class="subtextCard">
                    {{$history->work}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    {{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}:
                </span>
                <span class="subtextCard">
                    {{$history->military_rank}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    جهة نزول الراتب:
                </span>
                <span class="subtextCard">
                    {{$history->salary_bank_id}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    الدعم السكني:
                </span>
                <span class="subtextCard">
                    {{$history->residential_support}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    اضافة قسط الدعم الى الراتب:
                </span>
                <span class="subtextCard">
                    {{$history->add_support_installment_to_salary}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    الضمانات:
                </span>
                <span class="subtextCard">
                    {{$history->guarantees}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    الراتب الاساسي:
                </span>
                <span class="subtextCard">
                    {{$history->basic_salary}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    بدون تحويل الراتب:
                </span>
                <span class="subtextCard">
                    {{$history->without_transfer_salary}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    نسبة استقطاع صافي الراتب (شخصي):
                </span>
                <span class="subtextCard">
                    {{$history->personal_salary_deduction}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    مدة التمويل بالسنوات(شخصي):
                </span>
                <span class="subtextCard">
                    {{$history->personal_funding_months != null ? ($history->personal_funding_months / 12) : $history->personal_funding_months}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    نسبة الاستقطاع:
                </span>
                <span class="subtextCard">
                    {{$history->salary_deduction}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    مدة التمويل بالسنوات:
                </span>
                <span class="subtextCard">
                    {{$history->funding_months != null ? ($history->funding_months / 12) : $history->funding_months}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    قيمة العقار :
                </span>
                <span class="subtextCard">
                    {{$history->property_amount}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    حالة العقار :
                </span>
                <span class="subtextCard">
                    {{$history->property_completed}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    المسكن :
                </span>
                <span class="subtextCard">
                    {{$history->residence_type}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    المسكن :
                </span>
                <span class="subtextCard">
                    {{$history->residence_type}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    مبلغ السداد المبكر :
                </span>
                <span class="subtextCard">
                    {{$history->early_repayment}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    نسبة الدفعة الاولى (%) :
                </span>
                <span class="subtextCard">
                    {{$history->first_batch_percentage}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    توفير الدفعة الاولى :
                </span>
                <span class="subtextCard">
                    {{$history->provide_first_batch}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    نسبة ربح الدفعة الاولى (%) :
                </span>
                <span class="subtextCard">
                    {{$history->first_batch_profit}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    رسوم ادارية :
                </span>
                <span class="subtextCard">
                    {{$history->fees}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    خصم العميل :
                </span>
                <span class="subtextCard">
                    {{$history->discount}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    يوجد متضامن :
                </span>
                <span class="subtextCard">
                    {{$history->have_joint}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    (تاريخ الميلاد (متضامن :
                </span>
                <span class="subtextCard">
                    {{$history->joint_birth_hijri}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    (صافي الراتب (متضامن :
                </span>
                <span class="subtextCard">
                    {{$history->joint_salary}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    (جهة العمل (متضامن :
                </span>
                <span class="subtextCard">
                    {{$history->joint_work}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    ({{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }} (متضامن :
                </span>
                <span class="subtextCard">
                    {{$history->joint_military_rank}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    (جهة نزول الراتب (متضامن :
                </span>
                <span class="subtextCard">
                    {{$history->joint_salary_bank_id}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    (الدعم السكني (متضامن :
                </span>
                <span class="subtextCard">
                    {{$history->joint_residential_support}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    (اضافة قسط الدعم الى الراتب (متضامن :
                </span>
                <span class="subtextCard">
                    {{$history->joint_add_support_installment_to_salary}}
                </span>
            </div>

            <div class="col-md-3 ">
                <span class="textCard">
                    (مبلغ السداد المبكر (متضامن :
                </span>
                <span class="subtextCard">
                    {{$history->joint_early_repayment}}
                </span>
            </div>

        </div>

        @endif
    </div>


    <div class="card-footer text-muted">
        {{$history->created_at->diffForHumans()}}
    </div>
</div>

<br>
@endforeach

@endif

@endsection

@section('scripts')


@endsection