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


<section class="new-content mt-0">
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
                            <form action="{{ route('proper.customer.store')}}" method="post" class="">
                                @csrf
                                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                <div class="userFormsContainer mb-3">
                                    <div class="userFormsDetails topRow">
                                        <div class="row">
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
{{--                                            <div class="col-12 mb-3">--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label for="password">الرقم السري</label>--}}
{{--                                                    <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" autocomplete="password" autofocus placeholder="الرقم السري">--}}
{{--                                                </div>--}}
{{--                                                @if ($errors->has('password'))--}}
{{--                                                    <span class="help-block col-md-12">--}}
{{--                                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('password') }}</strong>--}}
{{--                                                </span>--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
                                            <div class="col-12 mb-3">
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
                                            </div>

                                            <div class="col-12 mb-3">
                                                <div class="form-group">
                                                    <label for="mobile">
                                                        {{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}
                                                    </label>
                                                    <input id="salary" name="salary" type="number" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary') }}" >
                                                </div>
                                                <span class="text-danger" id="error" role="alert"> </span>
                                                @if ($errors->has('salary'))
                                                    <span class="help-block col-md-12">
                                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('salary') }}</strong>
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

    //--------------CHECK MOBILE------------------------

    function changeMobile() {
        document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
        $('#checkMobile').removeClass('btn-success');
        $('#checkMobile').removeClass('btn-danger');
        $('#checkMobile').addClass('btn-info');

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
                        $('#checkMobile').html(data.errors.mobile[0])
                    }
                }
                if ($.trim(data) == "no") {
                    document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                    $('#checkMobile').removeClass('btn-info');
                    $('#checkMobile').addClass('btn-success');
                    $('#checkMobile').attr("disabled", false);
                } else {
                    document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-times'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Not') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                    $('#checkMobile').removeClass('btn-info');
                    $('#checkMobile').addClass('btn-danger');
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

</script>

@endsection
