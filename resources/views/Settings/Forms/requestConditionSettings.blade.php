@extends('layouts.content')


@section('css_style')
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


                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session()->get('message') }}
                    </div>
                    @endif


                    <div id="msg2" class="alert alert-dismissible" style="display:none;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>

                </div>
            </div>

            <div style="text-align:center;font-size:medium" id="addNew">
                <a href="{{route('admin.addNewRequestConditionsPage')}}">
                    <i class="fa fa-plus-circle" style="font-size:20px;color:#005580" title="إضافة شرط جديد"></i> إضافة
                </a>
            </div>

            <br>

            <div class="card">
                <div class="card-header">
                    شروط الطلبات
                </div>
                <div class="card-body card-block">


                    @if ($request_conditions->count()>0)

                    @foreach($request_conditions as $request_condition)

                    <form action="{{route('admin.updateRequestCondition')}}" method="post" class="">
                        @csrf

                        <input type="hidden" name="condID" id="condID" value="{{$request_condition->id}}">


                        <div style="text-align:left">

                            <p class="p-2" style="text-align:right">{{$request_condition->id}} :</p>

                            <button type="submit" class="btn"><i class="fas fa-save" id="updateCondition" data-id="{{$request_condition->id}}" style="font-size:22px;color:blue" title="حفظ التعديلات"></i></button>

                            <i id="removeCondition" class="fas fa-trash-alt p-2 pointer" data-id="{{$request_condition->id}}" style="font-size:23px;color:red" title="حذف"></i>

                        </div>


                        <div class="row">

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5" style="display: block;" id="birth_gerous">
                                <div class="form-group">
                                    <label for="birth_date">@lang('language.from birth date'): </label>
                                    <div class="col-md-12">
                                        <input type="date" id="birth" name="request_validation_from_birth_date" value="{{$request_condition->request_validation_from_birth_date}}" class="form-control" placeholder="يوم/شهر/سنة" max="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5" style="display: block;" id="birth_gerous">
                                <div class="form-group">
                                    <label for="birth_date"> @lang('language.to birth date'): </label>
                                    <div class="col-md-12">
                                        <input type="date" id="birth" name="request_validation_to_birth_date" class="form-control" value="{{$request_condition->request_validation_to_birth_date}}" placeholder="يوم/شهر/سنة" max="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>


                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5" id="birth_hijri">
                                <div class="form-group">
                                    <label for="birth_date"> @lang('language.from birth date hijri'): </label>
                                    <div class="col-md-12">
                                        <input type="text" name="request_validation_from_birth_hijri" style="text-align: right;" value="{{$request_condition->request_validation_from_birth_hijri}}" class="form-control hijri-date" placeholder="يوم/شهر/سنة" id="hijri-date{{$request_condition->id}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5" id="birth_hijri">
                                <div class="form-group">
                                    <label for="birth_date"> @lang('language.to birth date hijri'): </label>
                                    <div class="col-md-12">
                                        <input type="text" name="request_validation_to_birth_hijri" style="text-align: right;" value="{{$request_condition->request_validation_to_birth_hijri}}" class="form-control hijri-date" placeholder="يوم/شهر/سنة" id="hijri-date-{{$request_condition->id}}">
                                    </div>
                                </div>
                            </div>


                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5" class="form-group">
                                <label for="birth_date">@lang('language.from_salary'): </label>
                                <div class="col-md-12">
                                    <input type="number" name="request_validation_from_salary" style="text-align: right;" value="{{$request_condition->request_validation_from_salary}}" class="form-control" placeholder="@lang('language.salary')">
                                </div>
                            </div>

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                <div class="form-group">
                                    <label for="birth_date">@lang('language.to_salary'): </label>
                                    <div class="col-md-12">
                                        <input type="number" name="request_validation_to_salary" style="text-align: right;" value="{{$request_condition->request_validation_to_salary}}" class="form-control" placeholder="@lang('language.salary')">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                <div class="form-group">
                                    <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

                                    <select id="work" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('work') is-invalid @enderror" name="request_validation_to_work">


                                    <option value="">---</option>

                                    @foreach ($worke_sources as $worke_source )

                                    <option value="{{$worke_source->id}}" {{$worke_source->id == $request_condition->request_validation_to_work ?"selected" : ""}}>{{$worke_source->value}}</option>

                                    @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                <div class="form-group">
                                    <label for="support" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</label>

                                    <select id="support" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('support') is-invalid @enderror" name="request_validation_to_support">


                                        <option value="">---</option>
                                        <option value="yes" @if ($request_condition->request_validation_to_support == 'yes') selected="selected" @endif>نعم</option>
                                        <option value="no" @if ($request_condition->request_validation_to_support == 'no') selected="selected" @endif>لا</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                <div class="form-group">
                                    <label for="property" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has_property') }}؟</label>

                                    <select id="property" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('property') is-invalid @enderror" name="request_validation_to_hasProperty">


                                        <option value="">---</option>
                                        <option value="yes" @if ($request_condition->request_validation_to_hasProperty == 'yes') selected="selected" @endif>نعم</option>
                                        <option value="no" @if ($request_condition->request_validation_to_hasProperty == 'no') selected="selected" @endif>لا</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                <div class="form-group">
                                    <label for="joint" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has_joint') }}؟</label>

                                    <select id="joint" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('joint') is-invalid @enderror" name="request_validation_to_hasJoint">


                                        <option value="">---</option>
                                        <option value="yes" @if ($request_condition->request_validation_to_hasJoint == 'yes') selected="selected" @endif>نعم</option>
                                        <option value="no" @if ($request_condition->request_validation_to_hasJoint == 'no') selected="selected" @endif>لا</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                <div class="form-group">
                                    <label for="obligations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}؟</label>

                                    <select id="obligations" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('obligations') is-invalid @enderror" name="request_validation_to_has_obligations">


                                        <option value="">---</option>
                                        <option value="yes" @if ($request_condition->request_validation_to_has_obligations == 'yes') selected="selected" @endif>نعم</option>
                                        <option value="no" @if ($request_condition->request_validation_to_has_obligations == 'no') selected="selected" @endif>لا</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                <div class="form-group">
                                    <label for="distress" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}؟</label>

                                    <select id="distress" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('distress') is-invalid @enderror" name="request_validation_to_has_financial_distress">


                                        <option value="">---</option>
                                        <option value="yes" @if ($request_condition->request_validation_to_has_financial_distress == 'yes') selected="selected" @endif>نعم</option>
                                        <option value="no" @if ($request_condition->request_validation_to_has_financial_distress == 'no') selected="selected" @endif>لا</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                <div class="form-group">
                                    <label for="owning_property" class="control-label mb-1">هل يمتلك عقار حاليا؟</label>

                                    <select id="owning_property" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('owning_property') is-invalid @enderror" name="request_validation_to_owningProperty">


                                        <option value="">---</option>
                                        <option value="yes" @if ($request_condition->request_validation_to_owningProperty == 'yes') selected="selected" @endif>نعم</option>
                                        <option value="no" @if ($request_condition->request_validation_to_owningProperty == 'no') selected="selected" @endif>لا</option>

                                    </select>
                                </div>
                            </div>

                        </div>


                    </form>

                    <hr>

                    @endforeach

                    @else

                    <p style="text-align: center;"> لايوجد شروط</p>

                    @endif


                </div>

            </div>
        </div>


    </div>
</div>
</div>




@endsection

@section('confirmMSG')
@include('Settings.Forms.confirmationMsg')

@endsection

@section('scripts')

<script src="{{ url("/") }}/js/bootstrap-hijri-datetimepicker.min.js"></script>

<script>
    ////////////////////////////////////////////////////


    $(document).on('click', '#removeCondition', function(e) {

        var id = $(this).attr('data-id');

        var modalConfirm = function(callback) {


            $("#mi-modal").modal('show');


            $("#modal-btn-si").on("click", function() {
                callback(true);
                $("#mi-modal").modal('hide');
            });

            $("#modal-btn-no").on("click", function() {
                callback(false);
                $("#mi-modal").modal('hide');
            });
        };

        modalConfirm(function(confirm) {
            if (confirm) {

                $.get("{{ route('admin.removeRequestCondition') }}", {
                    id: id
                }, function(data) {

                    //console.log(data);
                    var url = '{{ route("admin.requestConditionSettings") }}';

                    if (data.status == 1) {

                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        window.location.href = url;

                    } else
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                })



            } else {
                //No delete
            }
        });


    });
</script>

<script type="text/javascript">
    /////////////////////////////////////////

    var array = @json($request_conditions_id);

    $(function() {

        for (var afnan of array) {

            $("#hijri-date" + afnan).hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: true,
                showClose: true
            });

            $("#hijri-date-" + afnan).hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: true,
                showClose: true
            });

        }

    });
</script>
@endsection
