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
                                <form action="{{route('admin.updateAvailableExtendedItem')}}" method="post" class="">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $availableExtend['data']['id'] }}">
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="support_installment" class="control-label mb-1"> جهة التمويل </label>
                                            <select class="form-control" name="bank_id">
                                                @foreach($banks['data'] as $bank)
                                                    <option value="{{ $bank['id'] }}" {{ $bank['id']  === $availableExtend['data']['bank_id'] ? 'selected' : '' }}>{{ $bank['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> جهة العمل / القطاع </label>
                                            <select class="form-control" name="job_position_id">
                                                @foreach($jobPositions['data'] as $jobPosition)

                                                    <option value="{{ $jobPosition['id'] }}" {{ $jobPosition['id'] === $availableExtend['data']['job_position_id'] ? 'selected' : '' }}>{{ $jobPosition['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="support_installment" class="control-label mb-1"> عمر التمويل </label>
                                            <input type="text" name="funding_age" class="form-control" value="{{ $availableExtend['data']['funding_age'] }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <input type="checkbox" value="1" {{ $availableExtend['data']['basic_salary'] === true ? 'checked' : ''}}
                                            name="basic_salary" id="isChecked" class="js-switch">
                                            <label for="basic_salary" class="control-label mb-1"> استبدال الراتب الأساسي بدلا من التقاعدي </label>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <input type="checkbox" value="1" {{ $availableExtend['data']['residential_support'] === true ? 'checked' : ''}}
                                            name="residential_support" id="isChecked" class="js-switch">
                                            <label for="residential_support" class="control-label mb-1"> الدعم السكني </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-4 form-group mt-3">
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
        let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
    </script>
    <script>
        $(document).ready(function(){
            $('#isChecked').change(function(){
                if($(this).prop('checked'))
                {
                    $('#txt').show();
                }else{
                    $('#txt').hide();
                }
            })
        });
    </script>
@endsection