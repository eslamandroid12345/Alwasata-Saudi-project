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
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}
                            </div>
                            <div class="card-body card-block">
                                <form action="{{route('admin.saveNewSalaryEquationItem')}}" method="post" class="">
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
                                            @if ($errors->has('bank_id'))
                                                <span class="help-block col-md-12">
                                                 <strong style="color:red ;font-size:10pt">{{ $errors->first('bank_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="available_extend" class="control-label mb-1"> جهة العمل / القطاع </label>
                                            <select class="form-control" name="job_position_id">
                                                <option selected disabled>جهة العمل / القطاع</option>
                                                @foreach($jobs['data'] as $job)
                                                    <option value="{{ $job['id'] }}">{{ $job['text'] }}</option>
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
                                            <label for="available_extend" class="control-label mb-1">آلية حساب الراتب</label>
{{--                                            <input type="text" name="equation" class="form-control" value="{{ old('equation') }}">--}}
                                            <select class="form-control" name="equation">
                                                <option selected disabled>آلية حساب الراتب</option>
                                                @foreach($salaryEquations['data'] as $salaryEquation)
                                                    <option value="{{ $salaryEquation['id'] }}">{{ $salaryEquation['text'] }}</option>
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
