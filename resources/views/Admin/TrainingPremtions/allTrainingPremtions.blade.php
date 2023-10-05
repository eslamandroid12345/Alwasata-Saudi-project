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

                    @if ($errors->has('trainings'))
                    <div class="alert alert-error">
                        <ul>
                            <li style="color:red ;">{{ $errors->first('trainings') }}</li>
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
                    صلاحيات الأكاديميين
                </div>

                <div class="card-body card-block">


                    @if ($traning_premtions_agent_count > 0 || $traning_premtions_type_count > 0)

                    @foreach($trainig_users as $trainig_user)

                    @if (in_array($trainig_user->id,$traning_premtions_agent_array) || in_array($trainig_user->id,$traning_premtions_type_array))

                    <form action="{{route('admin.updatePremtion')}}" method="post" class="">
                        @csrf


                        <div class="row d-flex">

                            <input readonly type="hidden" name="trainID" id="trainID" value="{{$trainig_user->id}}" class="form-control">

                            <div id="traindiv" class="form-group col-lg-2">
                                <label for="training" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Training') }}</label>

                                <input readonly type="text" name="trainName" id="trainName" value="{{$trainig_user->name}}" class="form-control">

                                @if ($errors->has('trainID'))
                                <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('trainID') }}</strong>
                                </span>
                                @endif

                            </div>


                            <div id="agnetdiv" class="form-group col-lg-4">
                                <label for="agent" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agents') }}</label>

                                <?php $traning_premtions_agent = DB::table('training_and_agent'); ?>

                                <select id="agents" name="agents[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control tokenizeable" multiple>
                                    @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ in_array($agent->id, $traning_premtions_agent->where('training_id',$trainig_user->id)->pluck('agent_id')->toArray()) ? 'selected' : '' }}>{{ $agent->name }}</option>
                                    @endforeach

                                </select>

                                @if ($errors->has('agents'))
                                <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('agents') }}</strong>
                                </span>
                                @endif
                            </div>


                            <div id="" class=" form-group col-lg-4">
                                <label for="type" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>

                                <?php $traning_premtions_type = DB::table('training_and_req_types'); ?>

                                <select id="type" name="type[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control type" multiple>

                                    <option value="0" {{ in_array( 0,$traning_premtions_type->where('training_id',$trainig_user->id)->pluck('type')->toArray() ) ? 'selected' : '' }}>{{ MyHelpers::admin_trans(auth()->user()->id,'purchase_form') }}</option>
                                    <option value="1" {{ in_array( 1,$traning_premtions_type->where('training_id',$trainig_user->id)->pluck('type')->toArray() ) ? 'selected' : '' }}>{{ MyHelpers::admin_trans(auth()->user()->id,'request_mortgage') }}</option>
                                    <option value="2" {{in_array( 2,$traning_premtions_type->where('training_id',$trainig_user->id)->pluck('type')->toArray() ) ? 'selected' : '' }}>{{ MyHelpers::admin_trans(auth()->user()->id,'pur-prep') }}</option>
                                    <option value="3" {{ in_array( 3,$traning_premtions_type->where('training_id',$trainig_user->id)->pluck('type')->toArray() ) ? 'selected' : '' }}>{{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }}</option>

                                </select>

                                @if ($errors->has('type'))
                                <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('type') }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>

                        <div style="text-align:left">

                            <button type="submit" class="btn"><i id="updatePremtion" class="fas fa-save" data-id="{{$trainig_user->id}}" style="font-size:20px;color:blue" title="حفظ التعديلات"></i></button>
                            <i id="removePremtion" class="fas fa-trash-alt p-2" data-id="{{$trainig_user->id}}" style="font-size:23px;color:red" title="حذف"></i>

                        </div>

                    </form>


                    <hr>

                    @endif
                    @endforeach

                    @endif


                    <div style="text-align:center" id="addNew" onclick="showPremtionDiv()">
                        <i class="fa fa-plus-circle" style="font-size:20px;color:#005580" title="إضافة صلاحية جديدة"></i> إضافة
                    </div>

                    <br>


                    <div id="newPremtion" style="display:none;">

                        <form action="{{route('admin.newPremtion')}}" method="post" class="">
                            @csrf

                            <div class="row d-flex">


                                <div class="form-group col-lg-2">
                                    <label for="train" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Training') }}</label>

                                    <select id="train" name="train" style="width: 80%;" class="form-control">
                                        <option value="">---</option>
                                        @foreach($trainig_users as $trainig_user)
                                        @if  (!(in_array($trainig_user->id, $traning_premtions_agent_array)) && !(in_array($trainig_user->id, $traning_premtions_type_array)) )
                                        <option value="{{ $trainig_user->id }}">{{ $trainig_user->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>

                                    @if ($errors->has('train'))
                                    <span class="help-block">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('train') }}</strong>
                                    </span>
                                    @endif
                                </div>


                                <div id="agnetdiv" class="form-group col-lg-4">
                                    <label for="agent" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agents') }}</label>

                                    <select id="agents" name="agents[]"  style="width: 80%;" class="form-control tokenizeable" multiple>
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


                                <div id="typediv" class=" form-group col-lg-4">
                                    <label for="type" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'type') }} </label>

                                    <select id="types" name="types[]"  style="width: 80%;" class="form-control type" multiple>

                                        <option value="0">{{ MyHelpers::admin_trans(auth()->user()->id,'purchase_form') }}</option>
                                        <option value="1">{{ MyHelpers::admin_trans(auth()->user()->id,'request_mortgage') }}</option>
                                        <option value="2">{{ MyHelpers::admin_trans(auth()->user()->id,'pur-prep') }}</option>
                                        <option value="3">{{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }}</option>

                                    </select>

                                    @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('type') }}</strong>
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

    $(document).ready(function() {
        $('.tokenizeable,.type').tokenize2();
        $(".tokenizeable,.type").on("tokenize:select", function() {
        $(this).trigger('tokenize:search', "");
    });
    });

    ///////////////////////////////////////////////////

    function showPremtionDiv() {
        var x = document.getElementById("newPremtion");
        var y = document.getElementById("addNew");

        if (x.style.display === "none") {
            y.innerHTML = " <i class='fa fa-angle-down' style='font-size:20px;color:#005580' title='إخفاءالشرط'></i> إخفاء";
            x.style.display = "block";
        } else {

            x.style.display = "none";
            y.innerHTML = " <i class='fa fa-plus-circle' style='font-size:20px;color:#005580' title='إضافة صلاحية جديدة'></i> إضافة";

        }
    }

    ////////////////////////////////////////////////////


    $(document).on('click', '#removePremtion', function(e) {

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

                $.get("{{ route('admin.removePremtion') }}", {
                    id: id
                }, function(data) {

                    console.log(data);
                    var url = '{{ route("admin.trainingPremtions") }}';

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