@extends('layouts.content')
@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}
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
                            <div class="card-header">
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}
                            </div>
                            <div class="card-body card-block">
                                @if(\Session::has('errors_api') )
                                    <div class="alert alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        {!! \Session::get('errors_api')  !!}
                                    </div>
                                @else
                                @endif
                                <form action="{{route('admin.saveNewCalculatorRuleItem')}}" method="post" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> جهة التمويل </label>
                                            <select class="form-control" name="bank_id">
                                                <option selected disabled>جهة التمويل</option>
                                                @foreach($banks['data'] as $bank)
                                                    <option value="{{ $bank['id'] }}">{{ $bank['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> جهة العمل / القطاع </label>
                                            <select class="form-control" name="job_position_id">
                                                <option selected disabled>جهة العمل / القطاع </option>
                                                @foreach($jobs['data'] as $job)
                                                    <option value="{{ $job['id'] }}">{{ $job['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> نوع الإشتراط </label>
                                            <select class="form-control" name="rule_type">
                                                <option selected disabled>أنواع الإشتراطات</option>
                                                @foreach($ruleTypes['data'] as $ruleType)
                                                    <option value="{{ $ruleType['id'] }}">{{ $ruleType['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> برنامج الحاسبة</label>
                                            <select class="form-control" name="calculator_program">
                                                <option selected disabled>برنامج الحاسبة</option>
                                                @foreach($calculatorPrograms['data'] as $calculatorProgram)
                                                    <option value="{{ $calculatorProgram['id'] }}">{{ $calculatorProgram['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-3 mt-4">
                                            <input type="checkbox" value="1" name="residential_support" class="js-switch">
                                            <label for="residential_support" class="control-label mb-1"> الدعم السكني </label>
                                        </div>
                                        <div class="form-group col-3 mt-4">
                                            <input type="checkbox" value="1" name="guarantees" class="js-switch">
                                            <label for="guarantees" class="control-label mb-1">الضمانات</label>
                                        </div>

                                        <div class="form-group col-3 mt-4">
                                            <input type="checkbox" value="1" name="joint" class="js-switch">
                                            <label for="joint" class="control-label mb-1">متضامن</label>
                                        </div>
                                        <div class="form-group col-3 mt-4">
                                            <input type="checkbox" value="1" name="show_result" class="js-switch">
                                            <label for="show_result" class="control-label mb-1">إظهار النتائج</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="from_salary" class="control-label mb-1"> صافي الراتب من </label>
                                            <input type="text" name="from_salary" class="form-control" value="{{ old('from_salary') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="from_salary" class="control-label mb-1"> صافي الراتب إلي </label>
                                            <input type="text" name="to_salary" class="form-control" value="{{ old('to_salary') }}">
                                        </div>

                                        <div class="form-group col-6 mt-4">
                                            <label for="from_salary" class="control-label mb-1"> الراتب الأساسي من </label>
                                            <input type="text" name="from_basic_salary" class="form-control" value="{{ old('from_basic_salary') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="from_salary" class="control-label mb-1"> الراتب الأساسي إلي </label>
                                            <input type="text" name="to_basic_salary" class="form-control" value="{{ old('to_basic_salary') }}">
                                        </div>

                                        <div class="form-group col-6 mt-4">
                                            <label for="from_salary" class="control-label mb-1"> الراتب التقاعدي من </label>
                                            <input type="text" name="from_retirement_salary" class="form-control" value="{{ old('from_retirement_salary') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="from_salary" class="control-label mb-1"> الراتب التقاعدي إلي </label>
                                            <input type="text" name="to_retirement_salary" class="form-control" value="{{ old('to_retirement_salary') }}">
                                        </div>

                                        <div class="form-group col-6 mt-4">
                                            <label for="from_salary" class="control-label mb-1"> العمر من </label>
                                            <input type="text" name="from_age" class="form-control" value="{{ old('from_age') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="from_salary" class="control-label mb-1"> العمر إلي </label>
                                            <input type="text" name="to_age" class="form-control" value="{{ old('to_age') }}">
                                        </div>

                                        <div class="form-group col-6 mt-4">
                                            <label for="from_salary" class="control-label mb-1"> مدة أشهر التقاعد من </label>
                                            <input type="text" name="from_retirement_months" class="form-control" value="{{ old('from_retirement_months') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="from_salary" class="control-label mb-1"> مدة أشهر التقاعد إلي </label>
                                            <input type="text" name="to_retirement_months" class="form-control" value="{{ old('to_retirement_months') }}">
                                        </div>

                                        <div class="form-group col-6 mt-4">
                                            <label for="from_salary" class="control-label mb-1"> مدة الخدمة بالأشهر من </label>
                                            <input type="text" name="from_job_tenure_months" class="form-control" value="{{ old('from_job_tenure_months') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="from_salary" class="control-label mb-1"> مدة الخدمة بالأشهر إلي </label>
                                            <input type="text" name="to_job_tenure_months" class="form-control" value="{{ old('to_job_tenure_months') }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-4 form-group">
                                            <button type="submit" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</button>
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
@section('scripts')
    <script>
        let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
    </script>
@endsection