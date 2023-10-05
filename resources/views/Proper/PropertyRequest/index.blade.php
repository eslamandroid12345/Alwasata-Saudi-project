@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Properties Request') }}
@endsection
@section('css_style')

    <link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">

    <style>
        .middle-screen {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        table {
            width: 100%;
            text-align: center;
        }

        td {
            width: 15%;
        }

        .reqNum {
            width: 0.5%;
        }

        .reqDate {
            text-align: center;
        }

        .loadingButton {
            background-color: #0088cc;
            color: azure;
            cursor: not-allowed;
        }

        .reqType {
            width: 2%;
        }

        tr:hover td {
            background: #d1e0e0
        }

        .newReq {
            background: rgba(98, 255, 0, 0.4) ! important;
        }

        .needFollow {
            background: rgba(12, 211, 255, 0.3) ! important;
        }

        .noNeed {
            background: rgba(0, 0, 0, 0.2) ! important;
        }

        .wating {
            background: rgba(255, 255, 0, 0.2) ! important;
        }

        .watingReal {
            background: rgba(0, 255, 42, 0.2) ! important;
        }

        .rejected {
            background: rgba(255, 12, 0, 0.2) ! important;
        }
    </style>

    {{-- NEW STYLE   --}}
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">

@endsection

@section('customer')

    <div id="alertDiv" class="alertDiv alr">

    </div>
<div>
  @if (session('msg'))
  <div id="msg" class="alert @if (session('type')) alert-{{ session('type') }} @endif ">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session('msg') }}
  </div>
  @endif

</div>

    <div class="tableBar">
        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">

                <div class="col-lg-10 ">
                    <div class="tableUserOption  flex-wrap ">
                        <div class="input-group col-md-7 mt-lg-0 mt-3">
                            <input class="form-control py-2" type="search" placeholder="ابحث هنا" id="example-search-input">
                            <span class="input-group-append">
                              <button class="btn btn-outline-info" type="button">
                                  <i class="fa fa-search"></i>
                              </button>
                            </span>
                        </div>

                    </div>
                </div>
                <div class="col-lg-2 mt-lg-0 mt-3">
                    <div  id="dt-btns" class="tableAdminOption">

                    </div>
                </div>
            </div>
        </div>
        <div class="dashTable">
            <table id="myusers-table" class="table table-bordred table-striped data-table">
                <thead>
                <tr>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'request responsible') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'customer name') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Property Info') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'status') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'agent classification') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>
                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>

                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
    @section('updateModel')
        @include('Proper.PropertyRequest.edit')
    @endsection



@section('scripts')
    <script>
     $(document).ready(function() {
                var url = "{{ url('/properties-requests/list') }}" ;
                var action = "{{ url('/properties-requests/update-property-request') }}" ;
           var table = $('.data-table').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}"
                },
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                dom: 'Bfrtip',
                buttons: [
                    // 'copyHtml5',
                    'excelHtml5',
                    // 'csvHtml5',
                    // 'pdfHtml5' ,
                    'print',
                    'pageLength'
                ],
                processing: true,
                serverSide: true,
                ajax: url,
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'request_responsible', name: 'request_responsible' },
                    { data: 'customer_name', name: 'customer_name' },
                    { data: 'customer_mobile', name: 'customer_mobile' },
                    { data: 'property_info', name: 'property_info' },
                    { data: 'status', name: 'status' },
                    { data: 'classification', name: 'classification' },
                    { data: 'comment', name: 'comment' },
                    { data: 'action', name: 'action' },
                ],
               "initComplete": function(settings, json) {
                   $(".paginate_button").addClass("pagination-circle");
                   /* To Adaptive with New Design */
                   $('#example-search-input').keyup(function(){
                       table.search($(this).val()).draw() ;
                   })

                   table.buttons().container()
                       .appendTo( '#dt-btns' );

                   $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr( 'title', 'تصدير' );
                   $('.buttons-print').html('<i class="fas fa-print"></i>').attr( 'title', 'طباعة' ) ;
                   $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr( 'title', 'عرض' );

                   $('.buttons-excel').addClass('no-transition custom-btn');
                   $('.buttons-print').addClass('no-transition custom-btn');
                   $('.buttons-collection').addClass('no-transition custom-btn');


                   $('.tableAdminOption span').tooltip(top)
                   $('button.dt-button').tooltip(top)

                   /* To Adaptive with New Design */
               }

           });

        });
    </script>

    <script>
        // Execute something when the modal window is shown.
        $('#edit_property_request').on('show.bs.modal', function (event) {
            /* Clear DATA Froml MODAL */
            document.getElementById("EditPropertyRequest").reset();
            $('.error-note').html('');

            /* Detect Action Route */
            loc = window.location.href ;
            var action = "{{ url('/properties-requests/update-property-request') }}" ;
            $('#EditPropertyRequest').attr('action',action);


            console.log(action)
            var button = $(event.relatedTarget); // Button that triggered the modal
            let id = button.data('id'); // Extract info from data-* attributes
            let status = button.data('status'); // Extract info from data-* attributes
            let comment = button.data('comment'); // Extract info from data-* attributes
            let classification_id = button.data('classification'); // Extract info from data-* attributes


            select_option_id = '#option'+status;
            $('#property_request_id').val(id);
            $(select_option_id).attr('selected','selected');
            $('#update_comment').val(comment);
            // $('#update_statusReq').val(object.statusReq);

            $.ajax({
                type: "GET",
                url: '/all/classifications' ,
                data: { },
                success: function(data){
                    var classifications = data ;
                    $(".classID").empty();
                    $(".classID").append('<option disabled value="">'+ '-----' +'</option>');
                    $.each(classifications,function(index, classification)
                    {
                        if(classification.id == classification_id){
                            var sel ='selected';

                        }else{
                            var sel ='';
                        }
                        $(".classID")
                            .append('<option '+ sel +' value=' + classification.id + '>' + classification.value + '</option>');
                    });
                },
                error: function(data) {
                    var errors = data.responseJSON;
                },
                complete: function() {
                    $("body").css("padding-right", "0px !important");
                }
            });
        });

        // console.log(action)
        $('.update_form').submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: $('.update_form').attr("action") ,
                data: new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data){
                    swal.fire({
                        title: data.msg,
                        type: data.type
                    }).then((result) => {
                        if (result.value) {
                            console.log(data)
                            var alert_message = '';
                            alert_message += '<div id="msg" class="alert alert-'+ data.type +' ">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                data.msg +' </div>';
                            $('.modal').trigger("click");
                            console.log(alert_message)
                            if($('.data-table').length > 0) {
                                $('.data-table').DataTable().ajax.reload();
                            }
                            if($('.alr').length > 0){
                                console.log('found')
                                $('.alr').append(alert_message);
                            }

                            $('html, body').animate({ scrollTop: 0 }, 'fast');
                        }
                    });

                },
                error: function(data) {

                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) == false) {
                        $.each(errors.errors, function(key, value) {
                            var ErrorID = '#' + key + 'UpdateError';
                            $(ErrorID).text(value);
                            console.log(value);
                            console.log(ErrorID);
                        })
                    }
                }
            });
        });

        function convert_to_tamweel(id){
            console.log(id);
            var url = "{{ url('/properties-requests/convert-propertyRequest-to-tamweelRequest') }}" ;
            console.log(url);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: url ,
                data: {'id': id },
                success: function(data){
                    swal.fire({
                        title: data.msg,
                        type: data.type
                    }).then((result) => {
                        if (result.value) {
                            console.log(data)
                            var alert_message = '';
                            alert_message += '<div id="msg" class="alert alert-'+ data.type +' ">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                data.msg +' </div>';
                            $('.modal').trigger("click");
                            console.log(alert_message)
                            if($('.data-table').length > 0) {
                                $('.data-table').DataTable().ajax.reload();
                            }
                            if($('.alr').length > 0){
                                console.log('found')
                                $('.alr').append(alert_message);
                            }

                            $('html, body').animate({ scrollTop: 0 }, 'fast');
                        }
                    });
                },
                error: function(data) {
                    var errors = data.responseJSON;
                },
                complete: function() {
                    $("body").css("padding-right", "0px !important");
                }
            });
        }

        function open_customer_chat(id){
            console.log(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{route('newChat')}}" ,
                data: {'receivers[]': id  , 'receiver_model_type':"App\\Customer"},
                success: function(data){

                },
                error: function(data) {
                    var errors = data.responseJSON;
                },
                complete: function() {
                    $("body").css("padding-right", "0px !important");
                }
            });
        }
    </script>
@endsection
