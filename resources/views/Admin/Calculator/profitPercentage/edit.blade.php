@extends('layouts.content')
@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }}
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
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }}
                            </div>
                            <div class="card-body card-block">
                                @if(\Session::has('errors_api') )
                                    <div class="alert alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        {!! \Session::get('errors_api')  !!}
                                    </div>
                                @else
                                @endif
                                <form action="{{route('admin.updateProfitPercentage')}}" method="post" class="">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $profitPercentage['data']['id'] }}">
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> جهة التمويل </label>
                                            <select class="form-control" name="bank_id">
                                                @foreach($banks['data'] as $bank)
                                                    <option value="{{ $bank['id'] }}" {{ $bank['id']  === $profitPercentage['data']['bank_id'] ? 'selected' : '' }}>{{ $bank['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> جهة العمل / القطاع </label>
                                            <select class="form-control" name="job_position_id">
                                                @if(($profitPercentage['data']['job_position_id']) == 0)
                                                    <option value="no">لا يوجد</option>
                                                    @foreach($jobPositions['data'] as $job)
                                                        <option value="{{ $job['id'] }}">{{ $job['text'] }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="no">لا يوجد</option>
                                                    @foreach($jobPositions['data'] as $job)
                                                        <option value="{{ $job['id'] }}" {{ $job['id']  === $profitPercentage['data']['job_position_id'] ? 'selected' : '' }}>{{ $job['text'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> السنة ( من ) </label>
                                            <input type="text" name="from_year" class="form-control" value="{{ $profitPercentage['data']['from_year'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> السنة ( إلي ) </label>
                                            <input type="text" name="to_year" class="form-control" value="{{ $profitPercentage['data']['to_year'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> صافي الراتب ( من ) </label>
                                            <input type="text" name="from_salary" class="form-control" value="{{ $profitPercentage['data']['from_salary'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> صافي الراتب ( إلي ) </label>
                                            <input type="text" name="to_salary" class="form-control" value="{{ $profitPercentage['data']['to_salary'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> النسبة </label>
                                            <input type="text" name="percentage" class="form-control" value="{{ $profitPercentage['data']['percentage'] }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-3 mt-4">
                                            <input type="checkbox" value="1" {{ $profitPercentage['data']['residential_support'] === true ? 'checked' : ''}}
                                            name="residential_support" id="isChecked" class="js-switch">
                                            <label for="profit_percentage" class="control-label mb-1"> الدعم السكني </label>
                                        </div>
                                        <div class="form-group col-3 mt-4">
                                            <input type="checkbox" value="1" {{ $profitPercentage['data']['personal'] === true ? 'checked' : ''}}
                                            name="personal" class="js-switch">
                                            <label for="profit_percentage" class="control-label mb-1"> شخصي </label>
                                        </div>
                                        <div class="form-group col-3 mt-4">
                                            <input type="checkbox" value="1" {{ $profitPercentage['data']['guarantees'] === true ? 'checked' : ''}}
                                            name="guarantees" class="js-switch">
                                            <label for="profit_percentage" class="control-label mb-1"> الضمانات </label>
                                        </div>
                                        <div class="form-group col-3 mt-4">
                                            <input type="checkbox" value="1" {{ $profitPercentage['data']['secured'] === true ? 'checked' : ''}}
                                            name="secured" class="js-switch">
                                            <label for="profit_percentage" class="control-label mb-1"> مضمون </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-4 form-group">
                                            <button type="submit" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</button>
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
        $(document).ready(function() {
            $('#stutus , #classifcations').select2();
        });
    </script>
    <script>
        let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
    </script>
@endsection