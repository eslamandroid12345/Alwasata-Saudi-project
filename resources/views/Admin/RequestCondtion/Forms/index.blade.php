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
                      إضافة شرط لحظي
                        </div>
                        <div class="card-body card-block">
                            <form action="{{url('admin/pending/request/condition')}}" method="post" class="">
                                @csrf
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display: block;" id="birth_gerous">
                                        <div class="form-group">
                                            <label for="birth_date">@lang('language.from birth date'):  </label>
                                            <div class="col-md-12">
                                                <input type="date" id="birth" name="request_validation_from_birth_date" class="form-control" value="{{old('request_validation_from_birth_date')}}" placeholder="يوم/شهر/سنة" max="{{ date('Y-m-d') }}">
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
                                            <label for="birth_date"> @lang('language.from birth date hijri'):  </label>
                                            <div class="col-md-12">
                                                <input type="text" name="request_validation_from_birth_hijri" style="text-align: right;" value="{{old('request_validation_from_birth_hijri')}}" class="form-control hijri-date" placeholder="يوم/شهر/سنة" id="hijri-date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="birth_hijri">
                                        <div class="form-group">
                                            <label for="birth_date"> @lang('language.to birth date hijri'):  </label>
                                            <div class="col-md-12">
                                                <input type="text" name="request_validation_to_birth_hijri" style="text-align: right;"value="{{old('request_validation_to_birth_hijri')}}"  class="form-control hijri-date" placeholder="يوم/شهر/سنة"  id="hijri-date1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                        <div class="form-group">
                                            <label for="birth_date">@lang('language.from_salary'):  </label>
                                            <div class="col-md-12">
                                                <input type="number" name="request_validation_from_salary" style="text-align: right;" value="{{old('request_validation_from_salary')}}" class="form-control" placeholder="@lang('language.salary')"  >
                                            </div>
                                        </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
                                        <div class="form-group">
                                            <label for="birth_date">@lang('language.to_salary'):  </label>
                                            <div class="col-md-12">
                                                <input type="number" name="request_validation_to_salary" style="text-align: right;" value="{{old('request_validation_to_salary')}}" class="form-control" placeholder="@lang('language.salary')"  >
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                    <div class="form-actions form-group">
                                                        <button type="submit" class="btn btn-info btn-block">تنفيذ  </button>
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
