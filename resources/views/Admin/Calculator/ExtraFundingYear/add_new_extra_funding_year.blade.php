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
                                <form action="{{route('admin.saveNewExtraFunding')}}" method="post" class="">
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
                                                <option selected disabled>جهة العمل / القطاع</option>
                                                @foreach($jobPositions['data'] as $jobPosition)

                                                    <option value="{{ $jobPosition['id'] }}">{{ $jobPosition['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> السنوات </label>
                                            <input type="text" name="years" class="form-control" value="{{ old('years') }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-2 mt-4">
                                            <input type="checkbox" value="1" name="residential_support" class="js-switch">
                                            <label for="extra_funding_years" class="control-label mb-1"> الدعم السكني </label>
                                        </div>
                                        <div class="form-group col-2 mt-4">
                                            <input type="checkbox" value="1" name="personal" class="js-switch">
                                            <label for="extra_funding_years" class="control-label mb-1"> شخصي </label>
                                        </div>
                                        <div class="form-group col-2 mt-4">
                                            <input type="checkbox" value="1" name="guarantees" class="js-switch">
                                            <label for="extra_funding_years" class="control-label mb-1"> الضمانات </label>
                                        </div>
                                        <div class="form-group col-2 mt-4">
                                            <input type="checkbox" value="1" name="extended" class="js-switch">
                                            <label for="extra_funding_years" class="control-label mb-1"> ممتد </label>
                                        </div>
                                        <div class="form-group col-2 mt-4">
                                            <input type="checkbox" value="1" name="after_retirement" class="js-switch">
                                            <label for="extra_funding_years" class="control-label mb-1"> بعد التقاعد </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> صافي الراتب من </label>
                                            <input type="text" name="from_salary" class="form-control" value="{{ old('from_salary') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> صافي الراتب إلي </label>
                                            <input type="text" name="to_salary" class="form-control" value="{{ old('to_salary') }}">
                                        </div>

                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> عمر العميل من </label>
                                            <input type="text" name="from_age" class="form-control" value="{{ old('from_age') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> عمر العميل إلي </label>
                                            <input type="text" name="to_age" class="form-control" value="{{ old('to_age') }}">
                                        </div>

                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> مدة أشهر التقاعد من </label>
                                            <input type="text" name="from_retirement_months" class="form-control" value="{{ old('from_retirement_months') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> مدة أشهر التقاعد إلي </label>
                                            <input type="text" name="to_retirement_months" class="form-control" value="{{ old('to_retirement_months') }}">
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