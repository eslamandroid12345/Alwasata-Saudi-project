@php
    $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
    $madany_works = DB::table('madany_works')->select('id', 'value')->get();
    $askary_works = DB::table('askary_works')->select('id', 'value')->get();
    $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id', auth()->user()->id)->get();
    $request_sources = DB::table('request_source')->get();
@endphp

<div class="modal fade" id="modalAddClient" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="row d-flex justify-content-center">
            <button class="btn-close ms-0 shadow-none mt-2" type="button" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="col-md-11 my-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="headER topRow text-center">
                            <i class="fas fa-user"></i>
                            <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}</h4>
                        </div>
                        <form action="{{ route('agent.addCustomerWithReqPost')}}" method="post">
                            @csrf
                            <input name="_token" value="{{ csrf_token() }}" type="hidden">
                            <div class="userFormsContainer mb-3">
                                <div class="userFormsDetails topRow">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                            <label>الإسم</label>
                                            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}">
                                            </div>
                                            @if ($errors->has('name'))
                                                <span class="help-block col-md-12">
                                                <strong style="color:red ;font-size:10pt">{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-10">
                                            <div class="form-group">
                                            <label>رقم الجوال</label>
                                            <input id="mobile-modal" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" onchange="changeMobile2()" autocomplete="mobile" autofocus placeholder="5xxxxxxxx">
                                            </div>
                                            <span class="text-danger" id="error-modal" role="alert"> </span>
                                            <span id="req-button-modal" role="alert" class="text-primary"></span>
                                            @if ($errors->has('mobile'))
                                                <span class="help-block col-md-12">
                                                    <strong style="color:red ;font-size:10pt">{{$errors->first('mobile') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-lg-2 mt-5 ">
                                            <div class="form-group">
                                                <button  id="checkMobile-modal" class="btn btn-primary w-100 py-2 px-2 rounded">تحقق</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                            <div class="d-flex align-items-center">
                                                <h5 class="result-of-check-mobile-modal" style="display:none;">هذا الرقم متاح</h5>
                                            </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                            <label>مصدر المعاملة</label>
                                            <select id="reqsour-modal" value="{{ old('reqsour') }}" class="form-control @error('reqsour') is-invalid @enderror"  name="reqsour">
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
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mb-3" id="collaboratorDiv-modal" style="display:none;">
                                            <div class="form-group">
                                            <label for="collaborator">{{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}</label>
                                            <br>
                                            <select id="collaborator-modal" class="form-control @error('collaborator') is-invalid @enderror" name="collaborator" style="width: 100%;">
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
                                    </div>
                                    <div class="form-group mb-0">
                                        <button class="btn btn-success w-100 py-2 btn-lg">اضافة</button>
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
</div>











<div class="tableBar middle-screen" style="display: none;text-align:center" id="parentTable-modal">
    <div class="dashTable">
      <table class="table table-bordred table-striped" id="request-table-modal">
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
      <div id="commentsRecord-modal" style="display:none">
        <br>
        <br>


        <span class="item pointer span-20" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="سجل الملاحظات">
          <i class="fa fa-history i-20" style="font-size: medium;"></i></span>

      </div>
      <div class="text-center">
        <form action="{{ route('agent.moveRequestWithAvalibleConditionToMe')}}" method="post" id="submit_move_button_form-modal">
          @csrf
          <input name="_token" value="{{ csrf_token() }}" type="hidden">
          <input name="mobile" type="hidden" id="customerMobile-modal">
          <input name="needAction" type="hidden" id="needAction-modal">
          <input name="reqID" type="hidden" id="reqID-modal">
          <button class="btn btn-secondary btn-small" id="submit_move_button-modal">
            سحب الطلب
          </button>
        </form>
      </div>

    </div>
  </div>








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
    //   $('#reqsour-modal').select2();
    //   $('#collaborator-modal').select2();


      $that = $('#reqsour-modal').val();
      checkCollaborator2($that);

    });


    //--------------CHECK MOBILE------------------------

    function changeMobile2() {
      document.querySelector('#checkMobile-modal').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
      $('#checkMobile-modal').removeClass('btn-success');
      $('#checkMobile-modal').removeClass('btn-danger');
      $('#checkMobile-modal').addClass('btn-info');

    }

    $(document).on('click', '#checkMobile-modal', function(e) {
        e.preventDefault();

      document.getElementById('parentTable-modal').style.display = "none";
      document.getElementById('commentsRecord-modal').style.display = "none";
      document.getElementById('customerMobile-modal').value = "";
      document.getElementById('needAction-modal').value = "";
      document.getElementById('reqID-modal').value = "";

      $('#request-table-modal tbody').empty();
      $('#checkMobile-modal').attr("disabled", true);
      document.querySelector('#checkMobile-modal').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";


      var mobile = document.getElementById('mobile-modal').value;
     /* var regex = new RegExp(/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/);*/

      //console.log(regex.test(mobile));

      if (mobile != null/* && regex.test(mobile)*/) {
        document.getElementById('error-modal').innerHTML = "";
        document.getElementById('req-button-modal').innerHTML = "";

        $.post("{{ route('all.checkMobile') }}", {
          mobile: mobile
        }, function(data) {
            if (data == "error") {
                document.getElementById('error-modal').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
                document.getElementById('error-modal').display = "block";
                document.querySelector('#checkMobile-modal').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                $('#checkMobile-modal').attr("disabled", false);
            }
          if ($.trim(data) === "no") {
            document.querySelector('#checkMobile-modal').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
            $('#checkMobile-modal').removeClass('btn-info');
            $('#checkMobile-modal').addClass('btn-success');
            $('#checkMobile-modal').attr("disabled", false);

            $('.result-of-check-mobile-modal').css('display','block');
            $('.result-of-check-mobile-modal').html(" <i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }} ").removeClass('text-danger').addClass('text-success')

          } else if (data.result == "existed_in_agent") {
            document.querySelector('#checkMobile-modal').innerHTML = "<i class='fa fa-times'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Not') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
            $('#checkMobile-modal').removeClass('btn-info');
            $('#checkMobile-modal').addClass('btn-danger');
            $('#checkMobile-modal').attr("disabled", false);
            document.getElementById('error-modal').innerHTML = "العميل بالفعل موجود في سلتك!";
            document.getElementById('error-modal').display = "block";
            let base_url = window.location.origin;
            var link = base_url + '/agent/fundingreqpageFromMsg/' + data.request.id;
            document.getElementById('req-button-modal').innerHTML = '  <a href="' + link + '">فتح الطلب</a> ';
            document.getElementById('req-button-modal').display = "block";

            $('.result-of-check-mobile-modal').css('display','block');
            $('.result-of-check-mobile-modal').html(" <i class='fa fa-times'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Not') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Available') }} ").removeClass('text-success').addClass('text-danger')


          } else if (data.result == "pending") {
            appendTable(data.request);
            document.getElementById('customerMobile-modal').value = data.request.mobile;
            document.getElementById('reqID-modal').value = data.request.id;
            document.querySelector('#checkMobile-modal').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
            $('#checkMobile-modal').removeClass('btn-info');
            $('#checkMobile-modal').addClass('btn-success');
            $('#checkMobile-modal').attr("disabled", false);
            document.getElementById('error-modal').innerHTML = "يرجى الاطلاع على بيانات الطلب بالأسفل وسحبه من زر سحب الطلب";
            document.getElementById('error-modal').display = "block";
            document.getElementById('commentsRecord-modal').style.display = "none";
            document.getElementById('parentTable-modal').style.display = "block";
          } else if (data.result === "archivedReq" || data.result === "freeze") {
            appendTable(data.request);
            updateValuesOfShowComment(data.request);
            document.getElementById('customerMobile-modal').value = data.request.mobile;
            document.getElementById('reqID-modal').value = data.request.id;
            document.querySelector('#checkMobile-modal').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
            $('#checkMobile-modal').removeClass('btn-info');
            $('#checkMobile-modal').addClass('btn-success');
            $('#checkMobile-modal').attr("disabled", false);
            document.getElementById('error-modal').innerHTML = "يرجى الاطلاع على بيانات الطلب بالأسفل وسحبه من زر سحب الطلب";
            document.getElementById('error-modal').display = "block";
            document.getElementById('commentsRecord-modal').style.display = "block";
            document.getElementById('parentTable-modal').style.display = "block";
          } else if (data.result == "needAction") {
            appendTable(data.request);
            document.getElementById('needAction-modal').value = 'yes';
            document.getElementById('customerMobile-modal').value = data.request.mobile;
            document.getElementById('reqID-modal').value = data.request.id;
            document.querySelector('#checkMobile-modal').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
            $('#checkMobile-modal').removeClass('btn-info');
            $('#checkMobile-modal').addClass('btn-success');
            $('#checkMobile-modal').attr("disabled", false);
            document.getElementById('error-modal').innerHTML = "يرجى الاطلاع على بيانات الطلب بالأسفل وسحبه من زر سحب الطلب";
            document.getElementById('error-modal').display = "block";
            document.getElementById('commentsRecord-modal').style.display = "block";
            document.getElementById('parentTable-modal').style.display = "block";
          } else if (data.result == "previous") {
            appendTable(data.request);
            document.getElementById('customerMobile-modal').value = data.request.mobile;
            document.getElementById('reqID-modal').value = data.request.id;
            document.querySelector('#checkMobile-modal').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
            $('#checkMobile-modal').removeClass('btn-info');
            $('#checkMobile-modal').addClass('btn-success');
            $('#checkMobile-modal').attr("disabled", false);
            document.getElementById('error-modal').innerHTML = "يرجى الاطلاع على بيانات الطلب بالأسفل وسحبه من زر سحب الطلب";
            document.getElementById('error-modal').display = "block";
            document.getElementById('commentsRecord-modal').style.display = "block";
            document.getElementById('parentTable-modal').style.display = "block";
          } else {
            document.querySelector('#checkMobile-modal').innerHTML = "<i class='fa fa-times'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Not') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
            $('#checkMobile-modal').removeClass('btn-info');
            $('#checkMobile-modal').addClass('btn-danger');
            $('#checkMobile-modal').attr("disabled", false);

            $('.result-of-check-mobile-modal').html(" <i class='fa fa-times'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Not') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Available') }} ").removeClass('text-success').addClass('text-danger')
            $('.result-of-check-mobile-modal').css('display','block');

          }


        }).fail(function(data) {


        });



      } else {
        document.getElementById('error-modal').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
        document.getElementById('error-modal').display = "block";
        document.querySelector('#checkMobile-modal').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
        $('#checkMobile-modal').attr("disabled", false);

      }



    });

    //--------------END CHECK MOBILE------------------------



    $(document).on('click', '#submit_move_button-modal', function(e) {
      $(this).attr('disabled','disabled');
      $('#submit_move_button_form-modal').submit();
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
    $('#reqsour-modal').on('change', function() {
      console.log('yessss')
      $this = $('#reqsour-modal').val();
      checkCollaborator2($this);

    });

    function checkCollaborator2(that) {
      if (that == 2) {


        document.getElementById("collaboratorDiv-modal").style.display = "block";


      } else {

        document.getElementById("collaboratorDiv-modal").style.display = "none";
        document.getElementById("collaborator-modal").value = "";
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

      $('#request-table-modal > tbody:last-child').append($data);
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
