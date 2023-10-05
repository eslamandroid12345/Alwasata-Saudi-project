@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }}
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

    .textarea {
        width: 220px;
        height: 33px;
        margin: 0 auto;
        display: block;
        resize: none;
        border: solid 1px #e0ebeb;

    }




    table {
        width: 100%;
    }

    td {
        width: 15%;
    }

    th {
        text-align: center;
    }

    .reqNum {
        width: 1%;
    }

    .reqDate {
        text-align: center;
    }

    .reqType {
        width: 2%;
    }

    .commentStyle {
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    tr:hover td {
        background: #d1e0e0
    }
</style>


{{--    NEW STYLE   --}}
<style>
    .tFex{
        position: relative !important;
        width: 100% !important;
    }
    .dataTables_filter { display: none; }
    span.redBg{
        background: #E67681;
    }
    .pointer{
        cursor: pointer;
    }
    .dataTables_info{
        margin-left: 15px;
        font-size: smaller;
    }
    .dataTables_paginate {
        color: #333;
        font-size: smaller;
    }
    .dataTables_paginate , .dataTables_info{
        margin-bottom: 10px;
        margin-top: 10px;
    }


</style>
@endsection

@section('customer')

<div id="msg" class="alert alert-success" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="msg2" class="alert alert-warning" style="display:none;">
    <button type="button" class="alert-warning" data-dismiss="alert">&times;</button>
</div>


@if(!empty($message))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ $message }}
</div>
@endif

@if ( session()->has('message') )
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message') }}
</div>
@endif

@if ( session()->has('message2') )
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif




<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }}:</h3>
    </div>
</div>
<br>

{{--    @if (!empty($requests[0]))--}}

    <div class="tableBar">
        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">

                <div class="col-lg-8 ">
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
                <div class="col-lg-4 text-md-right mt-lg-0 mt-3">

                    <div id="tableAdminOption" class="tableAdminOption">
                    @include('Admin.datatable_display_number')
                        <div id="dt-btns" class="tableAdminOption">
                            {{--  Here We Will Add Buttons of Datatable  --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashTable">
            <table class="table table-bordred table-striped data-table">
                <thead>
                <tr>

                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'in agent') }}</th>
                    @if(auth()->user()->role == 9)
                        <th style="text-align:center">الجودة</th>
                    @endif
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }} <br /> {{ MyHelpers::admin_trans(auth()->user()->id,'in quality') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }} <br /> {{ MyHelpers::admin_trans(auth()->user()->id,'in agent') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'agent comment') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification agent') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'quality comment') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification quality') }}</th>
                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>


                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
{{--    @else--}}
{{--    <div class="middle-screen">--}}
{{--        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No New Requests') }}</h2>--}}
{{--    </div>--}}
{{--    @endif--}}


@endsection

@section('updateModel')
@include('QualityManager.Request.filterReqs')
@include('QualityManager.Request.confirmArchMsg')
@endsection

@section('scripts')




<script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>

<script src="{{ asset('js/tokenize2.min.js') }}"></script>
<script>
$(document).ready(function() {
        @if ( session()->has('message3') )
        swal({
            title: 'خطأ!',
            text:  '{{MyHelpers::admin_trans(auth()->user()->id, "You already add this request")}}',
            type: 'error',
        });
        @endif

        @if ( session()->has('message4') )
        swal({
            title: 'تم!',
            text: '{{MyHelpers::admin_trans(auth()->user()->id, "Request added sucessfully")}}',
            type: 'success',
        });
        @endif
    });
</script>
<script>
    $('.tokenizeable').tokenize2();
    $(".tokenizeable").on("tokenize:select", function() {
        $(this).trigger('tokenize:search', "");
    });


    function getClassifcationX($x) {
        return $("#classifcation_" + $x).data('tokenize2').toArray();
    }




    $(document).ready(function() {
        var dt = $('.data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    pageLength: "عرض",
                    print: "طباعة",
                   

                }
            },
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                // 'copyHtml5',
                //'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
                
                {
                    text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                    action: function(e, dt, node, config) {
                        $('#myModal').modal('show');
                    }
                }
            ],
            scrollY:'50vh',
            processing: true,
            serverSide: true,
            ajax: {
                'url': "{{ url('qualityManager/recivedreq-datatable') }}",
                'data': function(data) {

                    let reqTypes = $("#request_type").data('tokenize2').toArray();
                    let customer_phone = $('#customer-phone').val();
                    let req_date_from = $('#request-date-from').val();
                    let req_date_to = $('#request-date-to').val();
                    let req_status = ($("#request_status").data('tokenize2').toArray());

                    let users = (($("#users").length > 0)? $("#users").data('tokenize2').toArray() : []);

                    let task_status = ($("#task_status").data('tokenize2').toArray());
                    let source = $('#source').data('tokenize2').toArray();
                    let collaborator = $('#collaborator').data('tokenize2').toArray();
                    let notes_status_agent = $('#notes_status_agent').data('tokenize2').toArray();
                    let notes_status_quality = $('#notes_status_quality').data('tokenize2').toArray();

                    if (req_date_from != '') {
                        data['req_date_from'] = req_date_from;
                    }
                    if (req_date_to != '') {
                        data['req_date_to'] = req_date_to;
                    }


                    if (customer_phone != '') data['customer_phone'] = customer_phone;

                    if (source != '') data['source'] = source;
                    if (collaborator != '') data['collaborator'] = collaborator;


                    if (req_status != '') data['req_status'] = req_status;

                    if (task_status != '') data['task_status'] = task_status;


                    if (reqTypes != '') data['reqTypes'] = reqTypes;
                    if (users != '') data['users'] = users;


                    if (notes_status_agent != '') {

                        var contain = false;
                        var empty = false;
                        contain = notes_status_agent.includes("1"); // returns true
                        empty = notes_status_agent.includes("0"); // returns true

                        if (contain && empty) // choose all optiones
                            notes_status_agent = 0;
                        else if (contain && !empty) // choose contain only
                            notes_status_agent = 1;
                        else if (!contain && empty) // choose empty only
                            notes_status_agent = 2;
                        else
                            notes_status_agent = null;
                        data['notes_status_agent'] = notes_status_agent;
                    }


                    if (notes_status_quality != '') {

                        var contain = false;
                        var empty = false;
                        contain = notes_status_quality.includes("1"); // returns true
                        empty = notes_status_quality.includes("0"); // returns true

                        if (contain && empty) // choose all optiones
                            notes_status_quality = 0;
                        else if (contain && !empty) // choose contain only
                            notes_status_quality = 1;
                        else if (!contain && empty) // choose empty only
                            notes_status_quality = 2;
                        else
                            notes_status_quality = null;
                        data['notes_status_quality'] = notes_status_quality;
                    }


                    if (getClassifcationX(item='sa') != '') {
                            data['class_id_' + item] = getClassifcationX(item);
                        }

                        if (getClassifcationX(item='qu') != '') {
                            data['class_id_' + item] = getClassifcationX(item);
                        }

                    // console.log(req_status);

                },
            },
            columns: [

                {
                    "targets": 0,
                    "data": "created_at",
                    "render": function(data, type, row, meta) {
                        return data.split(" ").join("<br/>");
                    }

                },
                {
                    "targets": 0,
                    "name": "requests.created_at",
                    "data": "req_created_at",
                    "render": function(data, type, row, meta) {
                        return data.split(" ").join("<br/>");
                    }

                },
                @if(auth()->user()->role == 9)
                {
                    data: 'quality', //name
                    name: 'quality'
                },
                @endif
                {
                    data: 'agentName', //name
                    name: 'users.name'
                },
                {
                    data: 'name', //name
                    name: 'customers.name'
                },
                {
                    data: 'mobile',
                    name: 'customers.mobile'
                },
                {
                    data: 'status',
                    name: 'quality_reqs.status'
                },
                {
                    data: 'statusReq',
                    name: 'requests.statusReq'
                },
                {
                    data: 'comment',
                    name: 'requests.comment'
                },
                {
                    data: 'class_id_agent',
                    name: 'requests.class_id_agent'
                },
                {
                    data: 'quacomment',
                    name: 'requests.quacomment'
                },
                {
                    data: 'class_id_quality',
                    name: 'requests.class_id_quality'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],

            "order": [
                [0, "desc"]
            ],
            createdRow: function(row, data, index) {

                $('td', row).eq(7).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(7).attr('title', data.comment); // to show other text of comment


                $('td', row).eq(0).addClass('reqDate'); // 6 is index of column
                $('td', row).eq(1).addClass('reqDate'); // 6 is index of column
            },
            initComplete: function() {
                let api = this.api();
                $("#filter-search-req").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
                    //checktable(api);
                    $('#myModal').modal('hide');
                });

                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                });

                   
                //====================draw table when change in display number=====================
                $('#display_number').focusout(function(){
                    dt.page.len( $(this).val()).draw();
                });
                //==================================================================================



                dt.buttons().container()
                    .appendTo( '#dt-btns' );

                $( ".dt-button" ).last().html('<i class="fas fa-search"></i>').attr('title','بحث') ;
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title','تصدير') ;
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title','طباعة') ;
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title','عرض') ;

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

                /* To Adaptive with New Design */

            },

        });
    });


    //


    $(function() {
        $('#source').on('tokenize:tokens:add', function(e, value, text) {



            if (value == 2) {


                document.getElementById("collaboratorDiv").style.display = "block";


            }
        });

        $('#source').on('tokenize:tokens:remove', function(e, value) {

            if (value == 2) {


                document.getElementById("collaboratorDiv").style.display = "none";
                document.getElementById("collaborator").value = "";

            }
        });

    });


    $(function() {
        $('#request_type').on('tokenize:tokens:add', function(e, value, text) {



            if (value == "شراء-دفعة") {


                document.getElementById("paystatusDiv").style.display = "block";


            }
        });

        $('#request_type').on('tokenize:tokens:remove', function(e, value) {

            if (value == "شراء-دفعة") {


                document.getElementById("paystatusDiv").style.display = "none";
                document.getElementById("pay_status").value = "";

            }
        });

    });

    function savecomm(reqID) {

        var id = reqID; //to pass req id

        var reqComm = $("#reqComment" + reqID).val();


        $.ajax({
            url: "{{ URL('qualityManager/updatecomm') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                reqComm: reqComm,
            },
            success: function(data) {
                if (data.status == 0)
                    $('#msg2').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else {
                    $('#msg').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");
                    $("#reqComment" + reqID).attr('title', data.newComm);
                }

            },

        });
    }

    function saveclass(data, reqID) {

        var id = reqID; //to pass req id

        var reqClass = data.value;


        $.ajax({
            url: "{{ URL('qualityManager/updateclass') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                reqClass: reqClass,
            },
            success: function(data) {
                if (data.status == 0)
                    $('#msg2').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else {
                    $('#msg').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");
                }

            },

        });
    }

    const popupCenter = ({
        url,
        title,
        w,
        h
    }) => {
        // Fixes dual-screen position                             Most browsers      Firefox
        const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
        const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

        const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        const systemZoom = width / window.screen.availWidth;
        const left = (width - w) / 2 / systemZoom + dualScreenLeft
        const top = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow = window.open(url, title,
            `
      scrollbars=yes,
      width=${w / systemZoom},
      height=${h / systemZoom},
      top=${top},
      left=${left}
      `
        )

        if (window.focus) newWindow.focus();
    }

    $(document).on('click', '#questions', function(e) {


        var id = $(this).attr('data-id');

        var url = '{{route("quality.manager.questions" , 'id')}}';

        url = url.replace("id", id)


        popupCenter({
            url: url,
            title: 'أسئلة التقييم',
            w: 900,
            h: 500
        });

    });


    function transalteData(id) {
     var csrf_token = $('meta[name="csrf-token"]').attr('content');
     swal({
         title: 'هل انت متأكد',
         type: 'warning',
         showCancelButton: true,
         cancelButtonColor: '#d33',
         confirmButtonColor: '#3085d6',
         buttons: ["إلغاء","نعم"],
     }).then(function(inputValue) {
         if (inputValue != null) {
             $.ajax({
                 url: "{{ url('qualityManager/translate-to-basket/') }}" + '/' + id,
                 type: "POST",
                 data: {
                     '_method': 'POST',
                     '_token': csrf_token
                 },
                 success: function(data) {

                     swal({
                         title: 'تم!',
                         text: data.message,
                         type: 'success',
                         timer: '750'
                     })
                 },
                 error: function() {
                     swal({
                         title: 'خطأ',
                         text: data.message,
                         type: 'error',
                         timer: '750'
                     })
                 }
             });
         } else {

         }

     });
 }

    $(document).on('click', '#tasks', function(e) {


        var id = $(this).attr('data-id');

        var url = '{{route("quality.manager.alltask" , 'id')}}';

        url = url.replace("id", id)


        popupCenter({
            url: url,
            title: 'التذاكر',
            w: 900,
            h: 500
        });

    });
</script>
@endsection
