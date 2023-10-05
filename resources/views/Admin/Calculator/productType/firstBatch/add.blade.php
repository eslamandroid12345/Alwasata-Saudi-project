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
                                <form action="{{route('admin.saveNewFirstBatch')}}" method="post" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> جهة التمويل </label>
                                            <select class="form-control" name="bank_id">
                                                <option value="no">لا يوجد</option>
                                                @foreach($banks['data'] as $bank)
                                                    <option value="{{ $bank['id'] }}">{{ $bank['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="from_property_amount" class="control-label mb-1"> قيمة العقار - من </label>
                                            <input type="text" name="from_property_amount" value="{{ old('from_property_amount') }}" class="form-control">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="to_property_amount" class="control-label mb-1"> قيمة العقار - إلي </label>
                                            <input type="text" name="to_property_amount" value="{{ old('to_property_amount') }}" class="form-control">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="percent" class="control-label mb-1"> النسبة (%) </label>
                                            <input type="text" name="percent" value="{{ old('percent') }}" class="form-control">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="residence_type" class="control-label mb-1"> المسكن </label>
                                            <select class="form-control" name="residence_type">
                                                <option selected disabled>المسكن</option>
                                                <option value="0">لا يوجد</option>
                                                <option value="1">مسكن أول</option>
                                                <option value="2">مسكن ثاني</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="residence_type" class="control-label mb-1"> مضمون </label>
                                            <select class="form-control" name="secured">
                                                <option value="1">نعم</option>
                                                <option value="0">لا</option>
                                            </select>
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