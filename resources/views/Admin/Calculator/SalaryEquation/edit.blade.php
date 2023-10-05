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
                    <div class="col-md-12">
                        @if (session('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{!! session('success') !!}</li>
                                </ul>
                            </div>
                        @elseif(session('error'))
                            <div class="alert alert-error">
                                <ul>
                                    <li>{!! session('error') !!}</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }}
                            </div>
                            <div class="card-body card-block">
                                <form action="{{route('admin.updateSalaryEquationItem')}}" method="post" class="">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $SalaryEquation['data']['id'] }}">
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="support_installment" class="control-label mb-1"> جهة التمويل </label>
                                            <select class="form-control" name="bank_id">
                                                @foreach($banks['data'] as $bank)
                                                    <option value="{{ $bank['id'] }}" {{ $bank['id']  === $SalaryEquation['data']['bank_id'] ? 'selected' : '' }}>{{ $bank['text'] }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('bank_id'))
                                                <span class="help-block col-md-12">
                                                 <strong style="color:red ;font-size:10pt">{{ $errors->first('bank_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> جهة العمل / القطاع </label>
                                            <select class="form-control" name="job_position_id">
                                                @foreach($jobPositions['data'] as $jobPosition)

                                                    <option value="{{ $jobPosition['id'] }}" {{ $jobPosition['id'] === $SalaryEquation['data']['job_position_id'] ? 'selected' : '' }}>{{ $jobPosition['text'] }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('job_position_id'))
                                                <span class="help-block col-md-12">
                                                 <strong style="color:red ;font-size:10pt">{{ $errors->first('job_position_id') }}</strong>
                                                </span>
                                            @elseif(\Session::has('job_compatible'))
                                                <span class="help-block col-md-12">
                                                 <strong style="color:red ;font-size:10pt">{!! \Session::get('job_compatible') !!}</strong>
                                                </span>
                                            @else
                                            @endif
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="support_installment" class="control-label mb-1"> آلية حساب الراتب</label>
{{--                                            <input type="text" name="equation" class="form-control" value="{{ $SalaryEquation['data']['equation'] }}">--}}
                                            <select class="form-control" name="equation">
                                                @foreach($salaryEquationsItems['data'] as $salaryEquationsItem)

                                                    <option value="{{ $salaryEquationsItem['id'] }}" {{ $salaryEquationsItem['id'] === $SalaryEquation['data']['equation'] ? 'selected' : '' }}>{{ $salaryEquationsItem['text'] }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('equation'))
                                                <span class="help-block col-md-12">
                                                 <strong style="color:red ;font-size:10pt">{{ $errors->first('equation') }}</strong>
                                                </span>
                                            @endif
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
