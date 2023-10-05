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
                                <form action="{{route('admin.updateFirstBatchItem')}}" method="post" class="">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $firstBatch['data']['id'] }}">
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> جهة التمويل </label>
                                            <select class="form-control" name="bank_id">
                                                    <option value="no">لا يوجد</option>
                                                    @foreach($banks['data'] as $bank)
                                                        <option value="{{ $bank['id'] }}" {{ $bank['id']  === $firstBatch['data']['bank_id'] ? 'selected' : '' }}>{{ $bank['text'] }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="from_property_amount" class="control-label mb-1"> قيمة العقار - من </label>
                                            <input type="text" name="from_property_amount" value="{{ $firstBatch['data']['from_property_amount'] }}" class="form-control">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="to_property_amount" class="control-label mb-1"> قيمة العقار - إلي </label>
                                            <input type="text" name="to_property_amount" value="{{ $firstBatch['data']['to_property_amount'] }}" class="form-control">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="percent" class="control-label mb-1"> النسبة (%) </label>
                                            <input type="text" name="percent" value="{{$firstBatch['data']['percent'] }}" class="form-control">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="residence_type" class="control-label mb-1"> المسكن </label>
                                            <select class="form-control" name="residence_type">
                                                <option value="0" {{ $firstBatch['data']['residence_type']  == 0 ? 'selected' : '' }}>لا يوجد</option>
                                                <option value="1" {{ $firstBatch['data']['residence_type']  == 1 ? 'selected' : '' }}>مسكن أول</option>
                                                <option value="2" {{ $firstBatch['data']['residence_type']  == 2 ? 'selected' : '' }}>مسكن ثاني</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="secured" class="control-label mb-1"> مضمون </label>
                                            <select class="form-control" name="secured">
                                                <option value="1" {{ $firstBatch['data']['secured']  == 1 ? 'selected' : '' }}>نعم</option>
                                                <option value="0" {{ $firstBatch['data']['secured']  == 0 ? 'selected' : '' }}>لا</option>
                                            </select>
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