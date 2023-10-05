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
                                <form action="{{route('admin.saveNewSalaryDeductionItem')}}" method="post" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="available_extend" class="control-label mb-1"> جهة التمويل </label>
                                            <select class="form-control" name="bank_id">
                                                <option selected disabled>جهة التمويل</option>
                                                @foreach($banks['data'] as $bank)
                                                    <option value="{{ $bank['id'] }}">{{ $bank['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="available_extend" class="control-label mb-1"> جهة العمل / القطاع </label>
                                            <select class="form-control" name="job_position_id">
                                                <option selected disabled>جهة العمل / القطاع</option>
                                                @foreach($jobs['data'] as $job)
                                                    <option value="{{ $job['id'] }}">{{ $job['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="available_extend" class="control-label mb-1">نسبة إستقطاع صافي الراتب %</label>
                                            <input type="text" name="salary_deduction" class="form-control" value="{{ old('salary_deduction') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="available_extend" class="control-label mb-1">صافي الراتب من</label>
                                            <input type="text" name="from_salary" class="form-control" value="{{ old('from_salary') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="available_extend" class="control-label mb-1">صافي الراتب إلي</label>
                                            <input type="text" name="to_salary" class="form-control" value="{{ old('to_salary') }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-2 mt-4">
                                            <input type="checkbox" value="1" name="guarantees" class="js-switch">
                                            <label for="available_extend" class="control-label mb-1"> الضمانات </label>
                                        </div>
                                        <div class="form-group col-2 mt-4">
                                            <input type="checkbox" value="1" name="residential_support" class="js-switch">
                                            <label for="available_extend" class="control-label mb-1"> الدعم السكني </label>
                                        </div>
                                        <div class="form-group col-2 mt-4">
                                            <input type="checkbox" value="1" name="personal" class="js-switch">
                                            <label for="available_extend" class="control-label mb-1"> شخصي </label>
                                        </div>
                                        <div class="form-group col-2 mt-4">
                                            <input type="checkbox" value="1" name="flexible" class="js-switch">
                                            <label for="available_extend" class="control-label mb-1"> مرن </label>
                                        </div>
                                        <div class="form-group col-2 mt-4">
                                            <input type="checkbox" value="1" name="secured" class="js-switch">
                                            <label for="available_extend" class="control-label mb-1"> مضمون </label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-4 form-group mt-3">
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