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
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            @if($prefix =='askforconsultant')
                            طلب استشارة
                            @elseif($prefix =='askforfunding')
                            طلب تمويل
                            @elseif($prefix =='realEstateCalculator')
                            الحاسبة العقارية
                            @elseif($prefix =='request_validation')
                            شروط الطلبات
                            @elseif($prefix =='customerReq')
                            محتوى طلب العميل
                            @endif
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('admin.settings.form.update')}}" method="post" class="">
                                @csrf
                                @if($prefix !='request_validation')

                                <div class="row">
                                @foreach($fields as $field)
                                <div class="col-3">
                                <div class="form-group">
                                   
                                    <input type="hidden" name="{{$field->option_name}}" value="hidden">
                                    <input type="checkbox" id="{{$field->option_name}}" name="{{$field->option_name}}" @if($field->option_value == 'show') checked @endif value="show">
                                    <label for="{{$field->option_name}}" class="control-label mb-1">{{$field->display_name}}</label>
                                    
                                </div>
                                </div>
                                @endforeach
                                </div>

                                
                                @else
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display: block;" id="birth_gerous">
                                        <div class="form-group">
                                            <label for="birth_date">@lang('language.from birth date'): </label>
                                            <div class="col-md-12">
                                                <input type="date" id="birth" name="request_validation_from_birth_date" value="{{$fields->where('option_name','request_validation_from_birth_date')->first()->option_value??''}}" class="form-control" placeholder="يوم/شهر/سنة" max="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display: block;" id="birth_gerous">
                                        <div class="form-group">
                                            <label for="birth_date"> @lang('language.to birth date'): </label>
                                            <div class="col-md-12">
                                                <input type="date" id="birth" name="request_validation_to_birth_date" class="form-control" value="{{$fields->where('option_name','request_validation_to_birth_date')->first()->option_value??''}}" placeholder="يوم/شهر/سنة" max="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="birth_hijri">
                                        <div class="form-group">
                                            <label for="birth_date"> @lang('language.from birth date hijri'): </label>
                                            <div class="col-md-12">
                                                <input type="text" name="request_validation_from_birth_hijri" style="text-align: right;" value="{{$fields->where('option_name','request_validation_from_birth_hijri')->first()->option_value??''}}" class="form-control hijri-date" placeholder="يوم/شهر/سنة" id="hijri-date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="birth_hijri">
                                        <div class="form-group">
                                            <label for="birth_date"> @lang('language.to birth date hijri'): </label>
                                            <div class="col-md-12">
                                                <input type="text" name="request_validation_to_birth_hijri" style="text-align: right;" value="{{$fields->where('option_name','request_validation_to_birth_hijri')->first()->option_value??''}}" class="form-control hijri-date" placeholder="يوم/شهر/سنة" id="hijri-date1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" <div class="form-group">
                                        <label for="birth_date">@lang('language.from_salary'): </label>
                                        <div class="col-md-12">
                                            <input type="number" name="request_validation_from_salary" style="text-align: right;" value="{{$fields->where('option_name','request_validation_from_salary')->first()->option_value??''}}" class="form-control" placeholder="@lang('language.salary')">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="birth_date">@lang('language.to_salary'): </label>
                                            <div class="col-md-12">
                                                <input type="number" name="request_validation_to_salary" style="text-align: right;" value="{{$fields->where('option_name','request_validation_to_salary')->first()->option_value??''}}" class="form-control" placeholder="@lang('language.salary')">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

                                            <select id="work" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('work') is-invalid @enderror" name="request_validation_to_work">


                                                <option value="">---</option>
                                                @foreach ($worke_sources as $worke_source )
                                                @if($fields->where('option_name','request_validation_to_work')->first()->option_value == $worke_source->id)
                                                <option value="{{$worke_source->id}}" selected>{{$worke_source->value}}</option>
                                                @else
                                                <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                                                @endif
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="support" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</label>

                                            <select id="support" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('support') is-invalid @enderror" name="request_validation_to_support">


                                                <option value="">---</option>
                                                <option value="yes" @if ($fields->where('option_name','request_validation_to_support')->first()->option_value == 'yes') selected="selected" @endif>نعم</option>
                                                <option value="no" @if ($fields->where('option_name','request_validation_to_support')->first()->option_value == 'no') selected="selected" @endif>لا</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="property" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has_property') }}؟</label>

                                            <select id="property" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('property') is-invalid @enderror" name="request_validation_to_hasProperty">


                                                <option value="">---</option>
                                                <option value="yes" @if ($fields->where('option_name','request_validation_to_hasProperty')->first()->option_value == 'yes') selected="selected" @endif>نعم</option>
                                                <option value="no" @if ($fields->where('option_name','request_validation_to_hasProperty')->first()->option_value == 'no') selected="selected" @endif>لا</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="joint" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has_joint') }}؟</label>

                                            <select id="joint" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('joint') is-invalid @enderror" name="request_validation_to_hasJoint">


                                                <option value="">---</option>
                                                <option value="yes" @if ($fields->where('option_name','request_validation_to_hasJoint')->first()->option_value == 'yes') selected="selected" @endif>نعم</option>
                                                <option value="no" @if ($fields->where('option_name','request_validation_to_hasJoint')->first()->option_value == 'no') selected="selected" @endif>لا</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="obligations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}؟</label>

                                            <select id="obligations" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('obligations') is-invalid @enderror" name="request_validation_to_has_obligations">


                                                <option value="">---</option>
                                                <option value="yes" @if ($fields->where('option_name','request_validation_to_has_obligations')->first()->option_value == 'yes') selected="selected" @endif>نعم</option>
                                                <option value="no" @if ($fields->where('option_name','request_validation_to_has_obligations')->first()->option_value == 'no') selected="selected" @endif>لا</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="distress" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}؟</label>

                                            <select id="distress" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('distress') is-invalid @enderror" name="request_validation_to_has_financial_distress">


                                                <option value="">---</option>
                                                <option value="yes" @if ($fields->where('option_name','request_validation_to_has_financial_distress')->first()->option_value == 'yes') selected="selected" @endif>نعم</option>
                                                <option value="no" @if ($fields->where('option_name','request_validation_to_has_financial_distress')->first()->option_value == 'no') selected="selected" @endif>لا</option>

                                            </select>
                                        </div>
                                    </div>

                                </div>

                                @endif
                                <div class="form-actions form-group">
                                    <button type="submit" class="btn btn-info btn-block">حفظ الاعدادات</button>
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