@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Conditions') }} {{ MyHelpers::admin_trans(auth()->user()->id,'The request move') }}
@endsection

@section('css_style')

    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .activeColor {
            color: green;
        }

        .notactiveColor {
            color: red;
        }
    </style>

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

                <div class="card">
                    <div class="card-header">
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Conditions') }} {{ MyHelpers::admin_trans(auth()->user()->id,'The request move') }}
                    </div>
                    <div class="card-body card-block">

                        <div>

                            @if ($askConditione->where('option_name','askRequest_active')->where('option_value', 'false')->first() != null)
                                <span id="toggleText" style="color: red;">غير مفعل</span>
                            @else
                                <span id="toggleText" style="color: green;">مفعل</span>
                            @endif

                            <label class="switch">
                                <input name="isActive" type="checkbox" {{$askConditione->where('option_name','askRequest_active')->where('option_value', 'false')->first() == null ? 'checked' : ''}}>
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <hr>

                        <form action="{{route('admin.updateAskRequestCondition')}}" method="post" class="">
                            @csrf

                            <div class="row d-flex">

                                <div class="form-group col-lg-1"></div>

                                <div id="" class="form-group col-lg-3">
                                    <label for="eachDay" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Each Day Ask Request') }}</label>

                                    <input type="number" name="eachDay" id="eachDay" value="{{old('eachDay',$eachDay->option_value) }}" class="form-control">

                                    @if ($errors->has('eachDay'))
                                        <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('eachDay') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div id="" class="form-group col-lg-3">
                                    <label for="noRequest" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Number Moved Request') }}</label>

                                    <input type="number" name="noRequest" id="noRequest" value="{{old('noRequest',$noRequest->option_value) }}" class="form-control">

                                    @if ($errors->has('noRequest'))
                                        <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('noRequest') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-3">
                                    <label for="timehour" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'recived date counter') }}</label>

                                    <input type="number" name="timehour" id="timehour" value="{{old('timehour',$hours->option_value) }}" class="form-control" placeholder="مثال : 5 ساعات">

                                    @if ($errors->has('timehour'))
                                        <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('timehour') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-1"></div>

                            </div>

                            <div style="text-align:left">

                                <button type="submit" class="btn btn-info">حفظ التعديلات</button>

                            </div>

                        </form>

                    </div>
                </div>
                <br> <br>
                {{--MOVE PENDING REQS BY AGENT--}}
                <div class="card">
                    <div class="card-header">
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Conditions') }} {{ MyHelpers::admin_trans(auth()->user()->id,'The request move') }} - المعلق
                    </div>
                    <div class="card-body card-block">

                        <div>

                            @if ($movePendingConditione->where('option_name','movePendingByAgent_active')->where('option_value', 'false')->first() != null)
                                <span id="movePending_toggleText" style="color: red;">غير مفعل</span>
                            @else
                                <span id="movePending_toggleText" style="color: green;">مفعل</span>
                            @endif

                            <label class="switch">
                                <input name="movePending_isActive" type="checkbox" {{$movePendingConditione->where('option_name','movePendingByAgent_active')->where('option_value', 'false')->first() == null ? 'checked' : ''}}>
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <hr>

                        <form action="{{route('admin.updateMovePendingRequestCondition')}}" method="post" class="">
                            @csrf

                            <div class="row d-flex">

                                <div class="form-group col-lg-4"></div>

                                <div id="" class="form-group col-lg-4">
                                    <label for="movePending_noReqs" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Each Day Ask Request') }}</label>

                                    <input type="number" name="movePending_noReqs" id="movePending_noReqs" value="{{old('movePending_noReqs',$movePending_noRequest->option_value) }}" class="form-control">

                                    @if ($errors->has('movePending_noReqs'))
                                        <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('movePending_noReqs') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="form-group col-lg-4"></div>

                            </div>

                            <div style="text-align:left">

                                <button type="submit" class="btn btn-info">حفظ التعديلات</button>

                            </div>

                        </form>

                    </div>
                </div>
                <br/>

                {!! Form::formGroup(setting()->all(),[
                    "route"     => ['V2.Admin.Setting.updateTransBasketSetting'],
                    "method"    => "POST",
                ])!!}
                <div class="card">
                    <div class="card-header">@lang('global.setting_ask_request_trans_basket')</div>
                    <div class="card-body card-block">
                        <div>
                            <span id="for_active_trans_basket">{{$setting['active_trans_basket']??null ? __("global.active") : __("global.disabled") }}</span>
                            <label class="switch">
                                <input id="active_trans_basket" value="1" name="active_trans_basket" type="checkbox" {{$setting['active_trans_basket']??null ? 'checked' : ''}}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <hr>
                        <div class="row d-flex">
                            <div class="form-group col-lg-4">
                                <label for="trans_basket_per_day" class="control-label mb-1">@lang("attributes.trans_basket_per_day")</label>
                                <input value="{{$setting['trans_basket_per_day']}}" type="number" name="trans_basket_per_day" id="trans_basket_per_day" class="form-control">
                                @if ($errors->has('trans_basket_per_day'))
                                    <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('trans_basket_per_day') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="trans_basket_request_count" class="control-label mb-1">@lang("attributes.trans_basket_request_count")</label>
                                <input value="{{$setting['trans_basket_request_count']}}" type="number" name="trans_basket_request_count" id="trans_basket_request_count" class="form-control">
                                @if ($errors->has('trans_basket_request_count'))
                                    <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('trans_basket_request_count') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div style="text-align:left">
                            <button type="submit" class="btn btn-info">حفظ التعديلات</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function () {
            $('#stutus , #classifcations, #agents').select2();
        });

        var checkbox = document.querySelector("input[name=isActive]");
        var toggleText = document.getElementById("toggleText");
        checkbox.addEventListener('change', function () {
            toggleText.style.color = '';
            toggleText.classList.remove("activeColor");
            toggleText.classList.remove("notactiveColor");

            if (this.checked) {
                $.get("{{route('admin.updateAskRequestActive')}}", {}, function (data) {
                    //  console.log(data);
                    if (data.status != 0) {
                        toggleText.innerHTML = 'مفعل';
                        toggleText.classList.add("activeColor");
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                    } else
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                });

            } else {
                $.get("{{route('admin.updateAskRequestActive')}}", {}, function (data) {
                    if (data.status != 0) {
                        //   console.log(data);
                        toggleText.innerHTML = 'غير مفعل';
                        toggleText.classList.add("notactiveColor");
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                    } else
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                });
            }
        });

        $(document).on('change', '#active_trans_basket', function (d) {
            const elm = $(this);
            const val = elm.prop('checked') ? 1: 0;
            const span = $("#for_active_trans_basket");
            $.post("{{route('V2.Admin.Setting.updateTransBasketSetting')}}", {[elm.attr('name')]: val}, function (data) {
                if(val){
                    span.addClass("activeColor");
                    span.removeClass("text-danger");
                    span.text('{{__("global.active")}}')
                }else{
                    span.removeClass("activeColor");
                    span.addClass("text-danger");
                    span.text('{{__("global.disabled")}}')
                }
                alertSuccess(data.message);
            });
        })

        var movePending_checkbox = document.querySelector("input[name=movePending_isActive]");
        var movePending_toggleText = document.getElementById("movePending_toggleText");
        movePending_checkbox.addEventListener('change', function () {

            movePending_toggleText.style.color = '';
            movePending_toggleText.classList.remove("activeColor");
            movePending_toggleText.classList.remove("notactiveColor");

            if (this.checked) {

                $.get("{{route('admin.updateMovePendingRequestActive')}}", {}, function (data) {
                    //  console.log(data);
                    if (data.status != 0) {
                        movePending_toggleText.innerHTML = 'مفعل';
                        movePending_toggleText.classList.add("activeColor");
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                    } else
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                });

            } else {
                $.get("{{route('admin.updateMovePendingRequestActive')}}", {}, function (data) {
                    if (data.status != 0) {
                        //   console.log(data);
                        movePending_toggleText.innerHTML = 'غير مفعل';
                        movePending_toggleText.classList.add("notactiveColor");
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                    } else
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                });
            }
        });

        /////////////////////////////////////////
    </script>
@endsection
