@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}
@endsection
@section('css_style')
<!--NEW 2/2/2020 for hijri datepicker-->
<link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />

<style>

</style>

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
              <form action="{{ route('agent.addCustomerWithReqPost')}}" method="post" class="">
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
                        <span id="req-button" role="alert" class="text-primary"></span>
                        @if ($errors->has('mobile'))
                        <span class="help-block col-md-12">
                          <strong style="color:red ;font-size:10pt">{{$errors->first('mobile') }}</strong>
                        </span>
                        @endif
                      </div>
                      <div class="col-12 mb-3">
                        <div class="form-group">
                          <label for="reqsour">{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</label>
                          <select id="reqsour" value="{{ old('reqsour') }}" class="form-control @error('reqsour') is-invalid @enderror"  name="reqsour">
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
                          <select id="collaborator" class="form-control @error('collaborator') is-invalid @enderror" name="collaborator" style="width: 100%;">
                            @if (!empty($collaborators[0]))

                            @foreach ($collaborators as $collaborator )
                            <option value="{{$collaborator->collaborato_id}}" {{(old('collaborator') == $collaborator->collaborato_id ) ? 'selected' : ''}}>{{$collaborator->name}}</option>
                            @endforeach

                            @else
                            <option disabled="disabled" value="">{{ MyHelpers::admin_trans(auth()->user()->id,'No Collaborator') }}</option>
                            @endif
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


<div class="tableBar middle-screen" style="display: none;text-align:center" id="parentTable">
  <div class="dashTable">
    <table class="table table-bordred table-striped data-table" id="request-table">
      <thead>
        <tr>

          <th>رقم الطلب </th>
          <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
          <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
          <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile_number') }}</th>
          <th>{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</th>
          <th>{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</th>
          <th>{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</th>
          <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has_property') }}</th>
          <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has_joint') }}</th>
          <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</th>
          <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</th>
          <th>هل يمتلك عقار</th>
          <th>{{ MyHelpers::admin_trans(auth()->user()->id,'birth_date') .' '.
                            MyHelpers::admin_trans(auth()->user()->id,'hijri') }}</th>



        </tr>

      </thead>
      <tbody id="customerTable">
      </tbody>
    </table>
    <div id="commentsRecord" style="display:none">
      <br>
      <br>


      <span class="item pointer span-20" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="سجل الملاحظات">
        <i class="fa fa-history i-20" style="font-size: medium;"></i></span>

    </div>
    <div class="text-center">
      <form action="{{ route('agent.moveRequestWithAvalibleConditionToMe')}}" method="post" id="submit_move_button_form">
        @csrf
        <input name="_token" value="{{ csrf_token() }}" type="hidden">
        <input name="mobile" type="hidden" id="customerMobile">
        <input name="needAction" type="hidden" id="needAction">
        <input name="reqID" type="hidden" id="reqID">
        <button class="btn btn-secondary btn-small" id="submit_move_button">
          سحب الطلب
        </button>
      </form>
    </div>

  </div>
</div>

@endsection

@section('updateModel')
@include('Agent.fundingReq.req_records')
@endsection


@section('scripts')
<script>
  ///////
  var hide_negative_comment = "";
  var negative_agent = "";
  ///////

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  /////////////////////////////////////////////////////////////////
  $(document).ready(function() {
    $('#reqsour').select2();
    $('#collaborator').select2();


    $that = $('#reqsour').val();
    checkCollaborator($that);

  });


  //--------------CHECK MOBILE------------------------

  function changeMobile() {
    document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
    $('#checkMobile').removeClass('btn-success');
    $('#checkMobile').removeClass('btn-danger');
    $('#checkMobile').addClass('btn-info');

  }

  $(document).on('click', '#checkMobile', function(e) {

    document.getElementById('parentTable').style.display = "none";
    document.getElementById('commentsRecord').style.display = "none";
    document.getElementById('customerMobile').value = "";
    document.getElementById('needAction').value = "";
    document.getElementById('reqID').value = "";

    $('#request-table tbody').empty();
    $('#checkMobile').attr("disabled", true);
    document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";


    var mobile = document.getElementById('mobile').value;
   /* var regex = new RegExp(/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/);*/

    //console.log(regex.test(mobile));

    if (mobile != null/* && regex.test(mobile)*/) {
      document.getElementById('error').innerHTML = "";
      document.getElementById('req-button').innerHTML = "";

      $.post("{{ route('all.checkMobile') }}", {
        mobile: mobile
      }, function(data) {
          if (data == "error") {
              document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
              document.getElementById('error').display = "block";
              document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
              $('#checkMobile').attr("disabled", false);
          }
        if ($.trim(data) === "no") {
          document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
          $('#checkMobile').removeClass('btn-info');
          $('#checkMobile').addClass('btn-success');
          $('#checkMobile').attr("disabled", false);
        } else if (data.result == "existed_in_agent") {
          document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-times'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Not') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
          $('#checkMobile').removeClass('btn-info');
          $('#checkMobile').addClass('btn-danger');
          $('#checkMobile').attr("disabled", false);
          document.getElementById('error').innerHTML = "العميل بالفعل موجود في سلتك!";
          document.getElementById('error').display = "block";
          let base_url = window.location.origin;
          var link = base_url + '/agent/fundingreqpageFromMsg/' + data.request.id;
          document.getElementById('req-button').innerHTML = '  <a href="' + link + '">فتح الطلب</a> ';
          document.getElementById('req-button').display = "block";
        } else if (data.result == "pending") {
          appendTable(data.request);
          document.getElementById('customerMobile').value = data.request.mobile;
          document.getElementById('reqID').value = data.request.id;
          document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
          $('#checkMobile').removeClass('btn-info');
          $('#checkMobile').addClass('btn-success');
          $('#checkMobile').attr("disabled", false);
          document.getElementById('error').innerHTML = "يرجى الاطلاع على بيانات الطلب بالأسفل وسحبه من زر سحب الطلب";
          document.getElementById('error').display = "block";
          document.getElementById('commentsRecord').style.display = "none";
          document.getElementById('parentTable').style.display = "block";
        } else if (data.result === "archivedReq" || data.result === "freeze") {
          appendTable(data.request);
          updateValuesOfShowComment(data.request);
          document.getElementById('customerMobile').value = data.request.mobile;
          document.getElementById('reqID').value = data.request.id;
          document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
          $('#checkMobile').removeClass('btn-info');
          $('#checkMobile').addClass('btn-success');
          $('#checkMobile').attr("disabled", false);
          document.getElementById('error').innerHTML = "يرجى الاطلاع على بيانات الطلب بالأسفل وسحبه من زر سحب الطلب";
          document.getElementById('error').display = "block";
          document.getElementById('commentsRecord').style.display = "block";
          document.getElementById('parentTable').style.display = "block";
        } else if (data.result == "needAction") {
          appendTable(data.request);
          document.getElementById('needAction').value = 'yes';
          document.getElementById('customerMobile').value = data.request.mobile;
          document.getElementById('reqID').value = data.request.id;
          document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
          $('#checkMobile').removeClass('btn-info');
          $('#checkMobile').addClass('btn-success');
          $('#checkMobile').attr("disabled", false);
          document.getElementById('error').innerHTML = "يرجى الاطلاع على بيانات الطلب بالأسفل وسحبه من زر سحب الطلب";
          document.getElementById('error').display = "block";
          document.getElementById('commentsRecord').style.display = "block";
          document.getElementById('parentTable').style.display = "block";
        } else if (data.result == "previous") {
          appendTable(data.request);
          document.getElementById('customerMobile').value = data.request.mobile;
          document.getElementById('reqID').value = data.request.id;
          document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
          $('#checkMobile').removeClass('btn-info');
          $('#checkMobile').addClass('btn-success');
          $('#checkMobile').attr("disabled", false);
          document.getElementById('error').innerHTML = "يرجى الاطلاع على بيانات الطلب بالأسفل وسحبه من زر سحب الطلب";
          document.getElementById('error').display = "block";
          document.getElementById('commentsRecord').style.display = "block";
          document.getElementById('parentTable').style.display = "block";
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



  $(document).on('click', '#submit_move_button', function(e) {
    $(this).attr('disabled','disabled');
    $('#submit_move_button_form').submit();
  });

  $(document).on('click', '#record', function(e) {

    var coloum = $(this).attr('data-id');
    var reqID = document.getElementById("reqID").value;

    var to_count_historiy = 0;
    // var body = document.getElementById("records");

    $.get("{{ route('all.reqRecords') }}", {
      coloum: coloum,
      reqID: reqID
    }, function(data) {

      $('#records').empty();



      //console.log(data);

      if (data.status == 1) {




        $.each(data.histories, function(i, value) {


          if (hide_negative_comment == 1) {
            if (value.user_id != negative_agent) {
              to_count_historiy++;
              var fn = $("<tr/>").attr('id', value.id);

              var name = '';
              if (value.switch != null)
                name = value.switch+' / ' + value.name;
              else
                name = value.name;

              fn.append($("<td/>", {
                text: name
              })).append($("<td/>", {
                text: value.value
              })).append($("<td/>", {
                text: value.updateValue_at
              }));

              $('#records').append(fn);
            }
          } else {
            to_count_historiy++;
            var fn = $("<tr/>").attr('id', value.id);

            var name = '';
            if (value.switch != null)
              name = value.switch+' / ' + value.name;
            else
              name = value.name;

            fn.append($("<td/>", {
              text: name
            })).append($("<td/>", {
              text: value.value
            })).append($("<td/>", {
              text: value.updateValue_at
            }));

            $('#records').append(fn);
          }

        });


        if (to_count_historiy == 0) {

          var fn = $("<tr/>");

          fn.append($("<td/>", {
            text: ""
          })).append($("<td/>", {
            text: 'لايوجد تحديثات'
          })).append($("<td/>", {
            text: ""
          }));

          $('#records').append(fn);
        }


        // body.append(fn)

        $('#myModal').modal('show');

      }
      if (data.status == 0) {

        var fn = $("<tr/>");

        fn.append($("<td/>", {
          text: ""
        })).append($("<td/>", {
          text: data.message
        })).append($("<td/>", {
          text: ""
        }));



        $('#records').append(fn);
        $('#myModal').modal('show');

      }



    }).fail(function(data) {


      document.getElementById('archiveWarning').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}!";
      document.getElementById('archiveWarning').style.display = "block";


    });


  })
  //////////////////////////////
  $('#reqsour').on('change', function() {
    $this = $('#reqsour').val();
    checkCollaborator($this);

  });

  function checkCollaborator(that) {
    if (that == 2) {


      document.getElementById("collaboratorDiv").style.display = "block";


    } else {

      document.getElementById("collaboratorDiv").style.display = "none";
      document.getElementById("collaborator").value = "";
    }
  }

  function appendTable(request) {

    $data = '<tr>';
    $data = $data + '<td>' + request.id + '</td>';
    $data = $data + '<td>' + request.created_at + '</td>';
    $data = $data + '<td>' + checkNull(request.name) + '</td>';
    $data = $data + '<td>' + checkNull(request.mobile) + '</td>';
    $data = $data + '<td>' + checkNull(request.work) + '</td>';
    $data = $data + '<td>' + checkValue(request.is_supported) + '</td>';
    $data = $data + '<td>' + checkNull(request.salary) + '</td>';
    $data = $data + '<td>' + checkValue(request.has_property) + '</td>';
    $data = $data + '<td>' + checkValue(request.has_joint) + '</td>';
    $data = $data + '<td>' + checkValue(request.has_obligations) + '</td>';
    $data = $data + '<td>' + checkValue(request.has_financial_distress) + '</td>';
    $data = $data + '<td>' + checkValue(request.owning_property) + '</td>';
    $data = $data + '<td>' + checkNull(request.birth_date_higri) + '</td>';

    $('#request-table > tbody:last-child').append($data);
  }

  function updateValuesOfShowComment(request) {
    //SHOW COMMENTS OR NOT (NEGAIVE Class)
    $.get("{{ route('all.getNegativeCommentWithAgent') }}", {
      req_id: request.id,
      comment: request.comment,
      user_id: request.user_id,
      class_id_agent: request.class_id_agent,
    }, function(data) {
      hide_negative_comment = data[1];
      negative_agent = data[0];
    });

    ////////////////////////////////////////////////////////
  }

  function checkValue(value) {
    if (value == 'yes')
      $data = 'نعم';
    else if (value == 'no')
      $data = 'لا';
    else
      $data = '';

    return $data;
  }

  function checkNull(value) {
    if (value == null)
      $data = '';
    else
      $data = value;

    return $data;
  }
  //----------------------------
</script>


@endsection
