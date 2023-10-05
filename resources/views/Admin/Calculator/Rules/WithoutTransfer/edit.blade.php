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
                                <form action="{{route('admin.updateWithoutTransferRuleItem')}}" method="post" class="">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $rule['data']['id'] }}">
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="bank_id" class="control-label mb-1"> جهة التمويل </label>
                                            <select class="form-control" name="bank_id">
                                                @foreach($banks['data'] as $bank)
                                                    <option value="{{ $bank['id'] }}" {{ $bank['id']  === $rule['data']['bank_id'] ? 'selected' : '' }}>{{ $bank['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position_id" class="control-label mb-1"> جهة العمل / القطاع </label>
                                            <select class="form-control" name="job_position_id">
                                                @foreach($jobs['data'] as $job)
                                                    <option value="{{ $job['id'] }}" {{ $job['id']  === $rule['data']['job_position_id'] ? 'selected' : '' }}>{{ $job['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="first_batch_percentage" class="control-label mb-1"> نسبة الدفعة الأولي </label>
                                            <input type="text" name="first_batch_percentage" class="form-control" value="{{ $rule['data']['first_batch_percentage'] }}">
                                            @if ($errors->has('first_batch_percentage'))
                                                <span class="help-block col-md-12">
                                                 <strong style="color:red ;font-size:10pt">{{ $errors->first('first_batch_percentage') }}</strong>
                                                </span>
                                            @endif
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