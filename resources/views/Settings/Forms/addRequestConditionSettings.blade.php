@extends('layouts.content')


@section('css_style')
<!--NEW 2/2/2020 for hijri datepicker-->
<link href="{{ url("/") }}/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />

@endsection

@section('customer')
<!-- MAIN CONTENT-->
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

                    @if ($errors->has('from_birth_date'))
                    <div class="alert alert-error">
                        <ul>
                            <li style="color:red ;">{{ $errors->first('from_birth_date') }}</li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            شروط الطلبات
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('admin.addNewRequestConditions')}}" method="post" class="">
                                @csrf

                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display: block;" id="birth_gerous">
                                        <div class="form-group">
                                            <label for="birth_date">@lang('language.from birth date'): </label>
                                            <div class="col-md-12">
                                                <input type="date" id="birth" name="request_validation_from_birth_date" value="{{old('request_validation_from_birth_date')}}" class="form-control" placeholder="يوم/شهر/سنة" max="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display: block;" id="birth_gerous">
                                        <div class="form-group">
                                            <label for="birth_date"> @lang('language.to birth date'): </label>
                                            <div class="col-md-12">
                                                <input type="date" id="birth" name="request_validation_to_birth_date" class="form-control" value="{{old('request_validation_to_birth_date')}}" placeholder="يوم/شهر/سنة" max="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="birth_hijri">
                                        <div class="form-group">
                                            <label for="birth_date"> @lang('language.from birth date hijri'): </label>
                                            <div class="col-md-12">
                                                <input type="text" name="request_validation_from_birth_hijri" style="text-align: right;" value="{{old('request_validation_from_birth_hijri')}}" class="form-control hijri-date" placeholder="يوم/شهر/سنة" id="hijri-date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="birth_hijri">
                                        <div class="form-group">
                                            <label for="birth_date"> @lang('language.to birth date hijri'): </label>
                                            <div class="col-md-12">
                                                <input type="text" name="request_validation_to_birth_hijri" style="text-align: right;" value="{{old('request_validation_to_birth_hijri')}}" class="form-control hijri-date" placeholder="يوم/شهر/سنة" id="hijri-date1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" class="form-group">
                                        <label for="birth_date">@lang('language.from_salary'): </label>
                                        <div class="col-md-12">
                                            <input type="number" name="request_validation_from_salary" style="text-align: right;" value="{{old('request_validation_from_salary')}}" class="form-control" placeholder="@lang('language.salary')">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="birth_date">@lang('language.to_salary'): </label>
                                            <div class="col-md-12">
                                                <input type="number" name="request_validation_to_salary" style="text-align: right;" value="{{old('request_validation_to_salary')}}" class="form-control" placeholder="@lang('language.salary')">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

                                            <select id="work" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('work') is-invalid @enderror" name="request_validation_to_work">


                                            @foreach ($worke_sources as $worke_source )
                                             <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                                             @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="support" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</label>

                                            <select id="support" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('support') is-invalid @enderror" name="request_validation_to_support">


                                                <option value="">---</option>
                                                <option value="yes" @if (old( 'request_validation_to_support' )=='yes' ) selected="selected" @endif>نعم</option>
                                                <option value="no" @if (old( 'request_validation_to_support' )=='no' ) selected="selected" @endif>لا</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="property" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has_property') }}؟</label>

                                            <select id="property" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('property') is-invalid @enderror" name="request_validation_to_hasProperty">


                                                <option value="">---</option>
                                                <option value="yes" @if (old( 'request_validation_to_hasProperty' )=='yes' ) selected="selected" @endif>نعم</option>
                                                <option value="no" @if (old( 'request_validation_to_hasProperty' )=='no' ) selected="selected" @endif>لا</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="joint" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has_joint') }}؟</label>

                                            <select id="joint" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('joint') is-invalid @enderror" name="request_validation_to_hasJoint">


                                                <option value="">---</option>
                                                <option value="yes" @if (old( 'request_validation_to_hasJoint' )=='yes' ) selected="selected" @endif>نعم</option>
                                                <option value="no" @if (old( 'request_validation_to_hasJoint' )=='no' ) selected="selected" @endif>لا</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="obligations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}؟</label>

                                            <select id="obligations" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('obligations') is-invalid @enderror" name="request_validation_to_has_obligations">


                                                <option value="">---</option>
                                                <option value="yes" @if (old( 'request_validation_to_has_obligations' )=='yes' ) selected="selected" @endif>نعم</option>
                                                <option value="no" @if (old( 'request_validation_to_has_obligations' )=='no' ) selected="selected" @endif>لا</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="distress" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}؟</label>

                                            <select id="distress" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('distress') is-invalid @enderror" name="request_validation_to_has_financial_distress">


                                                <option value="">---</option>
                                                <option value="yes" @if (old( 'request_validation_to_has_financial_distress' )=='yes' ) selected="selected" @endif>نعم</option>
                                                <option value="no" @if (old( 'request_validation_to_has_financial_distress' )=='no' ) selected="selected" @endif>لا</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="owning_property" class="control-label mb-1">هل يمتلك عقار حاليا؟</label>

                                            <select id="owning_property" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('owning_property') is-invalid @enderror" name="request_validation_to_owningProperty">


                                                <option value="">---</option>
                                                <option value="yes" @if (old( 'request_validation_to_owningProperty' )=='yes' ) selected="selected" @endif>نعم</option>
                                                <option value="no" @if (old( 'request_validation_to_owningProperty' )=='no' ) selected="selected" @endif>لا</option>

                                            </select>
                                        </div>
                                    </div>

                                  

                                </div>

                                <div class="form-actions form-group">
                                    <button type="submit" class="btn btn-info btn-block">إضافة</button>
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
<script src="{{ url("/") }}/js/bootstrap-hijri-datetimepicker.min.js"></script>
<script type="text/javascript">
    $(function() {
        $("#hijri-date").hijriDatePicker({
            hijri: true,
            format: "YYYY/MM/DD",
            hijriFormat: 'iYYYY-iMM-iDD',
            showSwitcher: false,
            showTodayButton: true,
            showClose: true
        });
        $("#hijri-date1").hijriDatePicker({
            hijri: true,
            format: "YYYY/MM/DD",
            hijriFormat: 'iYYYY-iMM-iDD',
            showSwitcher: false,
            showTodayButton: true,
            showClose: true
        });
    });
</script>
@endsection