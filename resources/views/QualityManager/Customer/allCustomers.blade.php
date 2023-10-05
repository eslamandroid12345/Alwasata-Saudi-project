@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Search Customer') }}
@endsection

@section('css_style')

<style>
    .middle-screen {
        height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }
</style>
<!--NEW 2/2/2020 for hijri datepicker-->
<link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />

{{--    NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">

@endsection

@section('customer')


<section class="new-content mt-5">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-6 offset-md-3">
                <div class="row">
                    <div class="col-lg-12   mb-md-0">
                        <div class="userFormsInfo  ">
                            <div class="headER topRow text-center">
                                <i class="fas fa-user"></i>
                                <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Search Customer') }} </h4>
                            </div>
                            <div class="userFormsContainer mb-3">
                                <div class="userFormsDetails topRow">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <div class="form-group">
                                                <label>رقم العميل</label>
                                                <input id="mobile" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" autocomplete="mobile" autofocus placeholder="5xxxxxxxx">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="Green d-block border-0 w-100 py-2 rounded text-light addUserClient" id="checkMobile">
                                                <i class="fa fa-search"></i>
                                                {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                                            </button>
                                        </div>

                                        <small class="text-danger" id="error" role="alert" style="text-align:center;  display: flex;flex-direction: column;justify-content: center;"> </small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

<br>


<div class="tableBar" style="display:none;" id="parrentTable">
    <div class="dashTable">
        <table class="table table-bordred table-striped data-table" id="mycustomer-table">
            <thead>
            <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }} <br /> {{ MyHelpers::admin_trans(auth()->user()->id,'in agent') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'agent comment') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification agent') }}</th>
                <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
            </tr>

            </thead>
            <tbody id="customerTable">

            </tbody>
        </table>
    </div>
</div>
@endsection
@section('updateModel')
@include('QualityManager.Customer.filterReqs')
@endsection
@section('confirmMSG')
@include('QualityManager.Customer.confirmationMsg')
@endsection
@section('scripts')
<script>
    $(document).on('click', '#checkMobile', function(e) {
        document.getElementById('parrentTable').style.display = "none";
        $('#customerTable').empty();
        document.getElementById('error').innerHTML = "";
        document.getElementById('error').display = "none";
        $('#checkMobile').attr("disabled", true);
        document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";
        var mobile = document.getElementById('mobile').value;
        /*var regex = new RegExp(/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/);*/
        if (mobile != null /*&& regex.test(mobile)*/) {
            document.getElementById('error').innerHTML = "";
            $.get("{{ route('quality.manager.searchCustomer') }}", {
                mobile: mobile
            }, function(data) {
                if (data.status != 0) {
                    @if(auth()->user()->role !=9 )
                    var optiones = "<div class='tableAdminOption'>  <span class='item pointer' id='add' data-toggle='tooltip' data-id= " + data.customer.id + "  data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}'><i class='fas fa-plus'></i> </span></div>"
                    @else
                    var optiones = "-";
                    @endif
                    var fn = $("<tr/>").attr('id', data.customer.id).addClass("tr-shadow");
                    fn.append($("<td/>", {
                        text: data.customer.user_name
                    })).append($("<td/>", {
                        text: data.customer.name
                    })).append($("<td/>", {
                        text: data.customer.mobile
                    })).append($("<td/>", {
                        text: data.customer.statusReq
                    })).append($("<td/>", {
                        text: data.customer.comment
                    })).append($("<td/>", {
                        text: data.customer.value
                    })).append($("<td/>", {
                        html: optiones
                    }));
                    document.getElementById('error').innerHTML = "";
                    document.getElementById('error').display = "none";
                    document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-search'></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                    $('#checkMobile').attr("disabled", false);
                    (fn).appendTo($('#customerTable'));
                    document.getElementById('parrentTable').style.display = "block";
                } else {
                    document.getElementById('error').innerHTML = data.message;
                    document.getElementById('error').display = "block";
                    document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-search'></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                    $('#checkMobile').attr("disabled", false);
                }
            }).fail(function(data) {
                console.log(data);
            });
        } else {
            document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
            document.getElementById('error').display = "block";
            document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-search'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
            $('#checkMobile').attr("disabled", false);
        }
    });

    //--------------END CHECK MOBILE------------------------

    $(document).on('click', '#add', function(e) {
        var id = $(this).attr('data-id');
        $.get("{{route('quality.manager.addReqToQuality')}}", {
            id: id
        }, function(data) {
            //console.log(data);
            if (data.status != 0) {
                $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

            } else
                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);


        })
    });
</script>


@endsection
