@extends('Customer.fundingReq.customerReqLayout')


@section('title') الدعم الفني @endsection

@section('content')

<div style="text-align: left; padding: 2% ; font-size:large">
        <a href="{{url('/customer') }}">
            الرئيسية
            <i class="fa fa-home"> </i>
        </a>
        |
        <a href="{{ url()->previous() }}">
            رجوع
            <i class="fa fa-arrow-circle-left"> </i>
        </a>

    </div>

<div class="container mt-5">
  <div class="privateData">
    <div class="head-div text-center wow fadeInUp mb-5">
      <h1>تواصل مع الدعم الفني</h1>

    </div>

    <div id="msg2" class="alert alert-dismissible" style="display:none;">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div class="container">

      <div class="row flex-lg-nowrap">


        <div class="col">
          <div class="row">
            <div class="col mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="e-profile">

                    <div class="tab-content pt-3">
                      <div class="tab-pane active">
                        <form id="help_desk">
                          <p class="message-box alert"></p>
                          @csrf
                          <div class="row">
                            <div class="col">
                              <div class="row">
                                <div class="col">
                                  <div class="form-group">
                                    <label for="name">{{ MyHelpers::guest_trans('Full name') }}</label>
                                    <input readonly type="text" name="name" class="form-control" id="inputnameError" placeholder="{{ MyHelpers::guest_trans('Full name') }}" value="{{auth()->guard('customer')->user()->name}}">
                                    <span class="text-danger" style="color:red;" id="nameError" role="alert"> </span>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="form-group">
                                    <label for="mobile">{{ MyHelpers::guest_trans('mobile') }}</label>
                                    <input readonly id="inputmobileError" value="{{auth()->guard('customer')->user()->mobile}}" name="mobile" class="form-control" type="number" autocomplete="mobile" placeholder="5xxxxxxxxx">
                                    <span class="text-danger" style="color:red;" id="mobileError" role="alert"> </span>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="form-group">
                                    <label for="email">{{ MyHelpers::guest_trans('Email') }}</label>
                                    <input type="email" name="email" class="form-control" id="inputemailError" value="{{auth()->guard('customer')->user()->email}}" placeholder="example@example.com">
                                    <span class="text-danger" style="color:red;" id="emailError" role="alert"> </span>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="form-group">
                                    <label for="descrebtion">الوصف</label>

                                    <textarea class="form-control" name="descrebtion" rows="3"></textarea>

                                    <span class="text-danger" style="color:red;" id="descrebtionError" role="alert"> </span>

                                  </div>
                                </div>
                              </div>

                            </div>
                          </div>

                          <div class="row">
                            <div class="col d-flex justify-content-end">
                              <button class="btn btn-primary" id="saveBtn">إرسال</button>
                            </div>
                          </div>
                        </form>

                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>
      </div>
    </div>

  </div>
</div>
@endsection


@section('scripts')
<script>
  jQuery(function(e) {

    $(document).on('click', '#saveBtn', function(e) {
      e.preventDefault();
      var loader = $('.message-box');
      $('#nameError').addClass("d-none");
      $('#mobileError').addClass("d-none");
      $('#emailError').addClass("d-none");
      $('#descrebtionError').addClass("d-none");
      $('#msg2').addClass("d-none");


      var btn = $(this);

      $.ajax({
        dataType: 'json',
        type: 'POST',
        url: "{{ route('postHelpDesk') }}",
        data: $('#help_desk').serialize(),
        beforeSend: function() {
          btn.attr('disabled', true);
          loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> {{ MyHelpers::guest_trans('loading Send') }}").removeClass('hide alert-danger').addClass('alert-primary');
        },

        error: function(data) {
          console.log(data);
          loader.html('').removeClass('alert-success alert-primary');

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
            loader.html("تم إرسال طلبك ، سيتم التواصل بك قريبا").removeClass('hide alert-danger alert-primary').addClass('alert-success');
            $('#help_desk')[0].reset();

          } else {
            loader.html('حدث خطأ ، حاول مجددا').removeClass('alert-success alert-primary').addClass('alert-danger');
          }

        }
      });
    });
  });
</script>

@endsection