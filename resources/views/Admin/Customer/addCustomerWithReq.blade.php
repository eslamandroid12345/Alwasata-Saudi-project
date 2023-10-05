@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}
@endsection
@section('css_style')
<!--NEW 2/2/2020 for hijri datepicker-->
<link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />

@endsection

@section('customer')


<div>
    @if (session('msg'))
    <div id="msg" class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('msg') }}
    </div>
    @endif
</div>


<section class="new-content mt-5">
    <div class="container-fluid">

        <div class="row ">
            <div class="col-md-6 offset-md-3">
                <div class="row">
                    <div class="col-lg-12   mb-md-0">
                        <div class="userFormsInfo  ">
                            <div class="headER topRow text-center">
                                <i class="fas fa-user"></i>
                                <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}</h4>
                            </div>
                            <form action="{{ route('admin.addCustomerWithReqPost')}}" method="post" class="">
                                @csrf
                                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                <div class="userFormsContainer mb-3">
                                    <div class="userFormsDetails topRow">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <div class="form-group">
                                                    <label for="agents">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agents') }}</label>
                                                    <select id="agents" value="{{ old('agent') }}" class="form-control @error('agent') is-invalid @enderror" name="agent">
                                                        <option value="">---</option>
                                                        @foreach ($salesAgents as $salesAgent )
                                                        <option value="{{$salesAgent->id}}" {{(old('agent') == $salesAgent->id ) ? 'selected' : ''}}> {{$salesAgent->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger" id="agentError" role="alert"> </span>
                                                </div>
                                                @if ($errors->has('agent'))
                                                <span class="help-block col-md-12">
                                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('agent') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                            <div class="col-12 mb-3">
                                                <div class="form-group">
                                                    <label for="name">{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</label>
                                                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}">
                                                </div>
                                                @if ($errors->has('name'))
                                                <span class="help-block col-md-12">
                                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('name') }}</strong>
                                                </span>
                                                @endif
                                            </div>

                                            {{-- <div class="col-12 mb-3">
                                                <div class="form-group">
                                                    <label for="mobile">
                                                        {{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}
                                                        <small id="checkMobile" role="button" type="button" class="item badge badge-info pointer has-tooltip " title="{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                                                        </small>
                                                    </label>
                                                    <input id="mobile" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="5xxxxxxxx">
                                                </div>
                                                <span class="text-danger" id="error" role="alert"> </span>
                                                @if ($errors->has('mobile'))
                                                <span class="help-block col-md-12">
                                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('mobile') }}</strong>
                                                </span>
                                                @endif
                                            </div> --}}

                                            <div class="col-12 mb-3">
                                                <label for="mobile">{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }} </label>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <input id="mobile" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" onkeyup="changeMobile()" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="5xxxxxxxx">
                                                        <span class="text-danger" id="error" role="alert"> </span>
                                                        @if ($errors->has('mobile'))
                                                            <span class="help-block col-md-12">
                                                                <strong style="color:red ;font-size:10pt">{{ $errors->first('mobile') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <button id="checkMobile" class="btn btn-info p-1  has-tooltip " title="{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                                                        </button>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="" id="result-of-checking" role="alert"> </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 mb-3">
                                                <div class="form-group">
                                                    <label for="reqsour">{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</label>
                                                    <select id="reqsour" value="{{ old('reqsour') }}" class="form-control select2-request @error('reqsour') is-invalid @enderror" name="reqsour">
                                                        <option value="">---</option>
                                                        @foreach ($request_sources as $request_source )
                                                        @if ((old('reqsour') == $request_source->id) )
                                                        <option value="{{$request_source->id}}" selected>{{$request_source->value}}</option>
                                                        @else
                                                        <option value="{{$request_source->id}}">{{$request_source->value}}</option>
                                                        @endif
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <span class="text-danger" id="reqsourceError" role="alert"> </span>
                                                @if ($errors->has('reqsour'))
                                                <span class="help-block col-md-12">
                                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('reqsour') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                            <div class="col-12 mb-3" id="collaboratorDiv" style="display:none;">
                                                <div class="form-group">
                                                    <label for="collaborator">{{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}</label>
                                                    <br>
                                                    <select id="collaborator" style="width: 100%;" class="form-control @error('collaborator') is-invalid @enderror" name="collaborator">
                                                    </select>
                                                </div>
                                                @if ($errors->has('collaborator'))
                                                <span class="help-block col-md-12">
                                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('collaborator') }}</strong>
                                                </span>
                                                @endif
                                            </div>


                                            <div class="col-12">
                                                <button type="submit" class="Green d-block border-0 w-100 py-2 rounded text-light addUserClient">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</section>


@endsection

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    /////////////////////////////////////////////////////////////////
    $(document).ready(function() {
        $('#reqsour').select2();
        $('#collaborator').select2();
        $('#agents').select2();


        $that = $('#reqsour').val();
        checkCollaborator($that);

        $this = $('#agents').val();
        addCollaborator($this);

    });


    //--------------CHECK MOBILE------------------------

    function changeMobile() {
        document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
        $('#checkMobile').removeClass('btn-success');
        $('#checkMobile').removeClass('btn-danger');
        $('#checkMobile').addClass('btn-info');
        document.querySelector('#result-of-checking').innerHTML =" "

    }

    $(document).on('click', '#checkMobile', function(e) {



        $('#checkMobile').attr("disabled", true);
        document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";


        var mobile = document.getElementById('mobile').value;
        /*var regex = new RegExp(/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/);

        console.log(regex.test(mobile));*/

        if (mobile != null /*&& regex.test(mobile)*/) {
            document.getElementById('error').innerHTML = "";

            $.post("{{ route('all.checkMobile') }}", {
                mobile: mobile
            }, function(data) {
                if (data.errors) {
                    if (data.errors.mobile) {
                        // $('#checkMobile').html(data.errors.mobile[0])
                        document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                    }
                }
                if ($.trim(data) == "no") {
                    document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                    document.querySelector('#result-of-checking').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                    $('#checkMobile').removeClass('btn-info');
                    $('#checkMobile').addClass('btn-success');
                    $('#result-of-checking').removeClass('text-danger');
                    $('#result-of-checking').addClass('text-success');
                    $('#checkMobile').attr("disabled", false);
                } else {
                    document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                    document.querySelector('#result-of-checking').innerHTML = "<i class='fa fa-times'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Not') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                    $('#checkMobile').removeClass('btn-info');
                    $('#checkMobile').addClass('btn-danger');
                    $('#result-of-checking').removeClass('text-success');
                    $('#result-of-checking').addClass('text-danger');
                    $('#checkMobile').attr("disabled", false);
                }


            }).fail(function(data) {


            });



        } else {
            document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
            document.getElementById('error').display = "block";
            document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
            $('#checkMobile').attr("disabled", false);

        }



    });

    //--------------END CHECK MOBILE------------------------


    $('#agents').on('change', function() {

        $this = $('#agents').val();
        addCollaborator($this);

        $that = $('#reqsour').val();
        checkCollaborator($that);

    });

    $('#reqsour').on('change', function() {

        $this = $('#reqsour').val();
        checkCollaborator($this);

    });

    function addCollaborator(that) {
        var agentID = that;


        $.get('{{route('admin.getAgentCollberators')}}', {
                agentID: agentID
            },
            function(response) {
                if (response.count == 0)
                    $data = '<option disabled="disabled" value="">{{ MyHelpers::admin_trans(auth()->user()->id,"No Collaborator") }}</option>';
                else {

                    $data = '<option value="">---</option>';
                    response.collaborators.forEach(($collaborator, $index) => {
                        $data += '<option value="' + $collaborator.collaborato_id + '"' +
                            '>' + $collaborator.name + '</option>';

                    });

                }

                $('#collaborator').html($data);
            });


    }



    function checkCollaborator(that) {

        var currentAgent = document.getElementById("agents").value;
        if (that == 2 && currentAgent != '') {


            document.getElementById("collaboratorDiv").style.display = "block";


        } else {

            document.getElementById("collaboratorDiv").style.display = "none";
            document.getElementById("collaborator").value = "";
        }
    }
    // Test new repo
    //----------------------------
</script>

@endsection
