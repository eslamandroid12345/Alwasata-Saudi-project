@extends('testWebsite.layouts.master')

@section('title') تعديل رقم الجوال @endsection


@section('style')
<style>
    #countdown {
        padding-top: 2%;
        color: #396f8f;
    }

    #timeleft {
        font-weight: bold;
    }

    #waiting {
        color: #4e6f49;
    }

    #sent {
        color: #38a149;
    }

    #error {
        color: #800000;
    }
</style>
@endsection

@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
<div class="myOrders">
    <div class="container">
        <div class="head-div text-center">
            <h1>تعديل رقم الجوال</h1>

            <div class="order-my requstform">
                <p>
                    الرجاء إدخال رقم صحيح مكون من 9 أرقام ويبدأ ب 5
                </p>

                <form id="modifyMobileNumberForm">
                    <p class="message-box alert hide"></p>

                    {{ csrf_field() }}

                    <input type="number" name="mobile" id="newMobile" placeholder="5xxxxxxxx">
                    <br>
                    <span class="text-danger" style="color:red;" id="mobileError" role="alert"> </span>

                    <input type="hidden" name="oldMobile" id="oldMobile" value="{{$mobileNumber}}">

                    <button id="sendBtn" type="button" class="srchbtn"><i class="fas fa-paper-plane ml-2 mt-3"></i> تحديث</button>

                </form>



            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
@include('testWebsite.Pages.mobileNumberCheckModal')
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).on('click', '#sendBtn', function(e) {

        let newMobile = document.getElementById("newMobile").value;
        e.preventDefault();
        var loader = $('.message-box');
        $('#mobileError').addClass("d-none");
        var btn = $(this);
        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: "{{ route('modifyMobilePost') }}",
            data: $('#modifyMobileNumberForm').serialize(),
            beforeSend: function() {
                btn.attr('disabled', true);
                loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> {{ MyHelpers::guest_trans('loading Send') }}").removeClass('hide alert-danger').addClass('alert-success');
            },

            error: function(data) {

                loader.addClass('d-none');
                btn.attr('disabled', false);

                var errors = data.responseJSON;

                if ($.isEmptyObject(errors) == false) {

                    $.each(errors.errors, function(key, value) {

                        var ErrorID = '#' + key + 'Error';
                        var ErrorFiledID = '#input' + key + 'Error';
                        var ErrorMandotoryID = '#required' + key;

                        $(ErrorFiledID).addClass("errorFiled");

                        $(ErrorID).removeClass("d-none");
                        $(ErrorID).text(value);

                        $(ErrorMandotoryID).text("({{MyHelpers::guest_trans('Mandatory') }})");

                    })

                }


            },

            success: function(data) {
                btn.attr('disabled', false);
                loader.addClass('d-none');
                if (data.status == 1) {
                    document.getElementById("mobileNumberChek").innerHTML = newMobile;
                    document.getElementById("oldMobile").value = data.newMobile;
                    $("#mi-modal").modal({backdrop: "static"});
                } else
                    loader.html("حدث خطأ حاول مجددا").removeClass('hide alert-success').addClass('alert-danger');

            }

        });

    });


    $(document).on('click', '#yes_mobileNumberChek', function(e) {


        $.get("{{ route('mobileOTP')}}", {}, function(data) {
            if (data.status == 1) {
                let slug = "{{ route('mobileOTPPage') }}";
                //let slug = "{{ route('thankyou') }}?key=mobileOTPPage";
                window.location.replace(slug);
            } else {
                $("#mi-modal").modal('hide');
                alert('لاتستطيع إكمال العملية.. نعتذر على ذلك');
                let slug = "{{ url('/') }}";
                window.location.replace(slug);

            }
        });

    });

    $(document).on('click', '#no_mobileNumberChek', function(e) {

        let slug = "{{ route('modifyMobilePage') }}";
        window.location.replace(slug);

    });
</script>
@endsection