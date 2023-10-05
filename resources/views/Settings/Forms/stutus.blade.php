@extends('layouts.content')


@section('css_style')
<link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
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

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
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

                    @if ($errors->has('salesAgents'))
                    <div class="alert alert-error">
                        <ul>
                            <li style="color:red ;">{{ $errors->first('salesAgents') }}</li>
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
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Conditions') }} {{ MyHelpers::admin_trans(auth()->user()->id,'The Request') }}
                </div>
                <div class="card-body card-block">

                    <div>
                    @if ($qualityCon && $qualityCon->option_value == "false")
                        <span id="toggleText" style="color: red;">غير مفعل</span>
                        @else
                        <span id="toggleText" style="color: green;">مفعل</span>
                        @endif
                        <label class="switch">
                            <input name="isActive" type="checkbox" {{($qualityCon && $qualityCon->option_value == "true")? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                    </div> 

                    <hr>
                    @foreach($request_conditions as $request_condition)

                    <form action="{{route('admin.updateCondition')}}" method="post" class="">
                        @csrf

                        <input type="hidden" name="condID" id="condID" value="{{$request_condition->id}}">

                        <div class="row d-flex">
                            <p class="p-2">{{$request_condition->id}} :</p>

                            <div id="stutusdiv" class=" form-group col-lg-3">
                                <label for="stutus" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'statuses') }} {{ MyHelpers::admin_trans(auth()->user()->id,'The Request') }}</label>

                                <select id="stutus" name="stutus[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control tokenizeable" multiple="multiple" >


                                    @if ($condition_status->where('cond_id',$request_condition->id)->count() > 0)

                                    @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}" {{ $condition_status->where('cond_id',$request_condition->id)->where('status',$key)->count() > 0 ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach

                                    @else
                                    @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}">{{ $status }}</option>
                                    @endforeach
                                    @endif





                                </select>

                                @if ($errors->has('stutus'))
                                <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('stutus') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div id="classifcationsdiv" class="form-group col-lg-3">
                                <label for="classifcations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'classifcations') }} {{ MyHelpers::admin_trans(auth()->user()->id,'The Request') }}</label>

                                <select id="classifcations" name="classifcations[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control tokenizeable" multiple="multiple">

                                    @if($condition_class->where('cond_id',$request_condition->id)->count() > 0)

                                    @foreach($classifcations as $item)
                                    <option value="{{ $item->id }}" {{ $condition_class->where('cond_id',$request_condition->id)->where('class_id',$item->id)->count() > 0 ? 'selected' : '' }}>{{ $item->value }}</option>
                                    @endforeach

                                    @else
                                    @foreach($classifcations as $item)
                                    <option value="{{ $item->id }}">{{ $item->value }}</option>
                                    @endforeach
                                    @endif


                                </select>

                                @if ($errors->has('classifcations'))
                                <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('classifcations') }}</strong>
                                </span>
                                @endif
                            </div>


                            <div id="agnetdiv" class="form-group col-lg-3">
                                <label for="agent" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agents') }}</label>

                                <select id="agents" name="agents[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control tokenizeable" multiple="multiple">

                                    @if($condition_agent->where('cond_id',$request_condition->id)->count() > 0)

                                    @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ $condition_agent->where('user_id', $agent->id)->where('cond_id',$request_condition->id)->count() > 0 ? 'selected' : '' }}>{{ $agent->name }}</option>
                                    @endforeach

                                    @else
                                    @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                    @endforeach
                                    @endif


                                </select>

                                @if ($errors->has('agents'))
                                <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('agents') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div id="timeDaydiv" class="form-group col-lg-2">
                                <label for="timeday" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'recived date counter') }}</label>

                                <input type="number" name="timeday" id="timeday" value="{{$request_condition->timeDays}}" class="form-control" placeholder="مثال : 5 أيام">

                                @if ($errors->has('timeday'))
                                <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('timeday') }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>

                        <div style="text-align:left">

                            <button type="submit" class="btn"><i id="updateCondition" class="fas fa-save" data-id="{{$request_condition->id}}" style="font-size:23px;color:blue" title="حفظ التعديلات"></i></button>
                            <i id="removeCondition" class="fas fa-trash-alt p-2 pointer" data-id="{{$request_condition->id}}" style="font-size:23px;color:red" title="حذف"></i>


                        </div>

                    </form>


                    <hr>

                    @endforeach


                    <div style="text-align:center" id="addNew" onclick="showConditionDiv()">
                        <i class="fa fa-plus-circle" style="font-size:20px;color:#005580" title="إضافة شرط جديد"></i> إضافة
                    </div>

                    <br>


                    <div id="newCondition" style="display:none;">

                        <form action="{{route('admin.newCondition')}}" method="post" class="">
                            @csrf

                            <div class="row d-flex">

                                <div id="stutusdiv" class=" form-group col-lg-3">
                                    <label for="stutus" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'statuses') }} {{ MyHelpers::admin_trans(auth()->user()->id,'The Request') }}</label>
                                    <br>
                                    <select id="stutus" name="stutus[]" style=" width:75%; " class="form-control" multiple="multiple">

                                        @foreach($statuses as $key => $status)

                                        <option value="{{ $key }}">{{ $status }}</option>

                                        @endforeach

                                    </select>

                                    @if ($errors->has('stutus'))
                                    <span class="help-block">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('stutus') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div id="classifcationsdiv" class="form-group col-lg-3">
                                    <label for="classifcations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'classifcations') }} {{ MyHelpers::admin_trans(auth()->user()->id,'The Request') }}</label>
                                    <br>
                                    <select id="classifcations" style=" width:75%; " name="classifcations[]" class="form-control" multiple="multiple">
                                        @foreach($classifcations as $item)
                                        <option value="{{ $item->id }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('classifcations'))
                                    <span class="help-block">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('classifcations') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div id="agnetdiv" class="form-group col-lg-3">
                                    <label for="agent" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agents') }}</label>
                                    <br>
                                    <select id="agents" name="agents[]" style=" width:75%; " class="form-control" multiple="multiple">
                                        @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('agents'))
                                    <span class="help-block">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('agents') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div id="timeDaydiv" class="form-group col-lg-2">
                                    <label for="timeday" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'recived date counter') }}</label>

                                    <input type="number" name="timeday" id="timeday" class="form-control" placeholder="مثال : 5 أيام">

                                    @if ($errors->has('timeday'))
                                    <span class="help-block">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('timeday') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>


                            <br>

                            <div class="row">
                                <div class="col-4"></div>

                                <div class="col-4 form-group">
                                    <button type="submit" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</button>
                                </div>

                                <div class="col-4"></div>
                            </div>

                            <hr>

                        </form>

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

<script src="{{ asset('js/tokenize2.min.js') }}"></script>
<script>
    ////////////////////////////////////////////////////
    $('.tokenizeable').tokenize2();
    $(document).ready(function() {

        $('#stutus , #classifcations, #agents').select2();

    });

    ///////////////////////////////////////////////////

    function showConditionDiv() {
        var x = document.getElementById("newCondition");
        var y = document.getElementById("addNew");

        if (x.style.display === "none") {
            y.innerHTML = " <i class='fa fa-angle-down' style='font-size:20px;color:#005580' title='إخفاءالشرط'></i> إخفاء";
            x.style.display = "block";
        } else {

            x.style.display = "none";
            y.innerHTML = " <i class='fa fa-plus-circle' style='font-size:20px;color:#005580' title='إضافة شرط جديد'></i> إضافة";

        }
    }

    ////////////////////////////////////////////////////

    ///////////////////////////////////////////////////
    var checkbox = document.querySelector("input[name=isActive]");

    var toggleText = document.getElementById("toggleText");
    checkbox.addEventListener('change', function() {

        toggleText.style.color = '';
        toggleText.classList.remove("activeColor");
        toggleText.classList.remove("notactiveColor");

        if (this.checked) {

            $.get("{{route('admin.updateQualityRequestActive')}}", {}, function(data) {
                //  console.log(data);
                if (data.status != 0) {
                    toggleText.innerHTML = 'مفعل';
                    toggleText.classList.add("activeColor");
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                } else
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
            });

        } else {
            $.get("{{route('admin.updateQualityRequestActive')}}", {}, function(data) {
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

    ///////////////////////////////////

    ///////////////////////////////////

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

                $.get("{{ route('admin.removeCondition') }}", {
                    id: id
                }, function(data) {

                    console.log(data);
                    var url = '{{ route("admin.stutusRequestPage") }}';

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

    /////////////////////////////////////////
</script>
@endsection
