@extends('testWebsite.layouts.master')

@section('title') الدعم الفني @endsection


@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
<section class="calc">
    <div class="container">
        <div class="head-div text-center">
            <h1>تواصل مع الدعم الفني</h1>
        </div>
        <div class="calc-form funding_request_wrapper">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="calc-cont">

                        <form id="help_desk">
                            <p class="message-box alert"></p>
                            {{ csrf_field() }}

                            <div class="row first-calc">

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                    <label for="name">{{ MyHelpers::guest_trans('Full name') }}</label>
                                    <input type="text" name="name" class="form-control" id="inputnameError" placeholder="{{ MyHelpers::guest_trans('Full name') }}">
                                    <span class="text-danger" style="color:red;" id="nameError" role="alert"> </span>
                                </div>


                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                    <label for="mobile">{{ MyHelpers::guest_trans('mobile') }}</label>
                                    <input id="inputmobileError" name="mobile" class="form-control" type="number" autocomplete="mobile" placeholder="5xxxxxxxxx">
                                    <span class="text-danger" style="color:red;" id="mobileError" role="alert"> </span>
                                </div>


                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                    <label for="email">{{ MyHelpers::guest_trans('Email') }}</label>
                                    <input type="email" name="email" class="form-control" id="inputemailError" placeholder="example@example.com">
                                    <span class="text-danger" style="color:red;" id="emailError" role="alert"> </span>
                                </div>

                            </div>




                            <div class="row sec-calc mx-0">

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


                                    <label for="descrebtion">الوصف</label>

                                    <textarea class="form-control" name="descrebtion" rows="3" ></textarea>

                                    <span class="text-danger" style="color:red;" id="descrebtionError" role="alert"> </span>

                                </div>

                            </div>
                        


                        </form>

                        <div class="send-btn">
                            <button id="saveBtn"><i class="fas fa-paper-plane ml-2"></i> ارسال</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


@section('scripts')

<script type="text/javascript">
    

    jQuery(function(e) {

        $(document).on('click', '#saveBtn', function(e) {
            e.preventDefault();
            var loader = $('.message-box');
            $('#nameError').addClass("d-none");
            $('#mobileError').addClass("d-none");
            $('#emailError').addClass("d-none");
            $('#descrebtionError').addClass("d-none");

            var btn = $(this);

            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: "{{ route('postHelpDesk') }}",
                data: $('#help_desk').serialize(),
                beforeSend: function() {
                    btn.attr('disabled', true);
                    loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> {{ MyHelpers::guest_trans('loading Send') }}").removeClass('hide alert-danger').addClass('alert-success');
                },

                error: function(data) {
                    console.log(data);
                    loader.html('').removeClass('alert-success');

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
                    console.log(data);
                    btn.attr('disabled', false);
                    if (data.status == 1) {
                        loader.html('').removeClass('alert-danger').addClass('hide');
                        $('#help_desk')[0].reset();
                        let slug = "{{ route('thanksForHelpDesk') }}";
                        window.location.replace(slug);
                    } 
                    else {
                        loader.html('حدث خطأ ، حاول مجددا').removeClass('alert-success').addClass('alert-danger');
                    }

                }
            });
        });
    });
</script>
@endsection