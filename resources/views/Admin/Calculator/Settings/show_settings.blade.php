@extends('layouts.content')
@section('title')
    الضبط
@endsection
@section('css_style')
@endsection
@section('customer')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header" style="background-color: #0a6ebd; color: white;">
                                الضبط
                            </div>
                            <div class="card-body card-block">
                                @if(\Session::has('errors_api') )
                                    <div class="alert alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        {!! \Session::get('errors_api')  !!}
                                    </div>
                                @else
                                @endif
                                @if(\Session::has('success_api'))
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        {!! \Session::get('success_api')  !!}
                                    </div>
                                @endif
                                <form action="{{route('admin.updateCalculatorSetting')}}" method="post" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1"> مبلغ احتساب قسط الدعم </label>
                                            <input type="text" name="support_calc_installment" class="form-control" value="{{ $setting['data']['support_calc_installment'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1"> حد صافي الراتب لاحتساب نسبة استقطاع الراتب </label>
                                            <input type="text" name="max_salary_to_deduction" class="form-control" value="{{ $setting['data']['max_salary_to_deduction'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1"> نسبة التأمينات % </label>
                                            <input type="text" name="insurance_percentage" class="form-control" value="{{ $setting['data']['insurance_percentage'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1"> نسبة السداد المبكر % </label>
                                            <input type="text" name="early_repayment_percentage" class="form-control" value="{{ $setting['data']['early_repayment_percentage'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1"> اقصى نسبة استقطاع صافي الراتب التمويل الشخصي % </label>
                                            <input type="text" name="max_personal_salary_deduction" class="form-control" value="{{ $setting['data']['max_personal_salary_deduction'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1"> اقصى نسبة استقطاع صافي الراتب بدون دعم % </label>
                                            <input type="text" name="max_salary_deduction" class="form-control" value="{{ $setting['data']['max_salary_deduction'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1"> اقصى نسبة استقطاع صافي الراتب مع الدعم % </label>
                                            <input type="text" name="max_support_salary_deduction" class="form-control" value="{{ $setting['data']['max_support_salary_deduction'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1"> ادنى نسبة استقطاع صافي الراتب بدون دعم % </label>
                                            <input type="text" name="min_salary_deduction" class="form-control" value="{{ $setting['data']['min_salary_deduction'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1"> أدنى نسبة استقطاع صافي الراتب مرن % </label>
                                            <input type="text" name="min_flexible_salary_deduction" class="form-control" value="{{ $setting['data']['min_flexible_salary_deduction'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1"> اقصى عدد أشهر التمويل مع الدعم </label>
                                            <input type="text" name="max_support_funding_months" class="form-control" value="{{ $setting['data']['max_support_funding_months'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1"> اقصى عدد اشهر التمويل بدون دعم </label>
                                            <input type="text" name="max_funding_months" class="form-control" value="{{ $setting['data']['max_funding_months'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1">اقصى عدد اشهر التمويل (شخصي) </label>
                                            <input type="text" name="max_personal_funding_months" class="form-control" value="{{ $setting['data']['max_personal_funding_months'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1">نسبة السعي %</label>
                                            <input type="text" name="quest_percentage" class="form-control" value="{{ $setting['data']['quest_percentage'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1">الضريبة %</label>
                                            <input type="text" name="vat" class="form-control" value="{{ $setting['data']['vat'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1">نسبة ضريبة الشراء %</label>
                                            <input type="text" name="purchase_tax_percentage" class="form-control" value="{{ $setting['data']['purchase_tax_percentage'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1">نسبة آلية صافي راتب سهل %</label>
                                            <input type="text" name="shl_salary_percentage" class="form-control" value="{{ $setting['data']['shl_salary_percentage'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1">نسبة آلية صافي راتب سهل مع المتضامن %</label>
                                            <input type="text" name="shl_joint_salary_percentage" class="form-control" value="{{ $setting['data']['shl_joint_salary_percentage'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1">صافي راتب المضمون من</label>
                                            <input type="text" name="secured_from" class="form-control" value="{{ $setting['data']['secured_from'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-2">
                                            <label for="settings" class="control-label mb-1">صافي راتب المضمون إلي</label>
                                            <input type="text" name="secured_to" class="form-control" value="{{ $setting['data']['secured_to'] }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-4 form-group">
                                            <button type="submit" class="btn btn-info btn-block">حفظ</button>
                                        </div>
                                        <div class="col-4"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
