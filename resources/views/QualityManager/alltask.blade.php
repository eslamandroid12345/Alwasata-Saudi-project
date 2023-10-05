@extends('layouts.content')


@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'tasks') }}
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
    }

    td {
        width: 15%;
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
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection

@section('customer')



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



<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'tasks') }}:</h3>
    </div>
</div>



    @if ($tasks > 0)


    <div class="tableBar">
        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-lg-6 ">
                    <div class="tableUserOption  flex-wrap ">
                        <div class="input-group mt-lg-0 mt-3">
                            <input class="form-control py-2" type="search" placeholder="ابحث هنا" id="example-search-input">
                            <span class="input-group-append">
                              <button class="btn btn-outline-info" type="button">
                                  <i class="fa fa-search"></i>
                              </button>
                            </span>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3">
                    @if (@$request->status == 0 || @$request->status == 1 || @$request->status == 2)

                        <div class="tableUserOption  flex-wrap justify-content-lg-end justify-content-center">
                            <div class="addBtn  mt-lg-0 mt-3 ml-4">
                                <a href="{{ route('quality.manager.task',$id) }}" >
                                <button class="DarkBlue" type="button" role="button"><i class="fas fa-plus"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'task') }}
                                </button>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-lg-3 mt-lg-0 mt-3">
                    <div  id="dt-btns" class="tableAdminOption">

                    </div>
                </div>
            </div>
        </div>

        <div class="dashTable">
            <table  class="table table-borderless table-striped table-earning data-table">
                <thead>
                <tr>

                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'task date') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'status task') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Content') }} {{ MyHelpers::admin_trans(auth()->user()->id,'the task') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'agent replay') }}</th>
                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>


                </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
        </div>
    </div>
    @else
    <div class="middle-screen">
        <h2 style=" text-align: center;font-size: 20pt;">
            {{ MyHelpers::admin_trans(auth()->user()->id,'No tasks') }}
            <br /> <br />
            @if (@$request->status == 0 || @$request->status == 1 || @$request->status == 2 || @$request->status == 5)
                <!-- Add Task -->
                <div class="tableUserOption  flex-wrap ">
                    <div class="addBtn col-md-12">
                        <a href="{{ route('quality.manager.task',$id) }}" >
                        <button type="button" class="green">
                            <i class="fas fa-plus"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'task') }}
                        </button>
                        </a>
                    </div>
                </div>

            @endif

        </h2>
    </div>

    @endif

@endsection

@section('updateModel')
@include('QualityManager.Request.filterReqs')
@include('QualityManager.Request.confirmArchMsg')
@endsection

@section('scripts')



<script src="{{ asset('js/tokenize2.min.js') }}"></script>

<script>
    $('.tokenizeable').tokenize2();
    $(".tokenizeable").on("tokenize:select", function() {
        $(this).trigger('tokenize:search', "");
    });

    function getCustomerIDS() {
        return $("#customer_ids").data('tokenize2').toArray();
    }


    function getClassifcationX($x) {
        return $("#classifcation_" + $x).data('tokenize2').toArray();
    }

    function getReqTypes() {
        return $("#request_type").data('tokenize2').toArray();
    }

    $(document).ready(function() {
        var dt = $('.data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    print: "طباعة",
                    pageLength: "عرض",

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
                // 'copyHtml5',
                //'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
                'pageLength',
                {
                    text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                    action: function(e, dt, node, config) {
                        $('#myModal').modal('show');
                    }
                }
            ],
            processing: true,
            serverSide: true,
            ajax: {
                'url': "{{ url('qualityManager/task_datatable') }}",
                'data': function(data) {

                    let reqTypes = $("#request_type").data('tokenize2').toArray();
                    let customer_phone = $('#customer-phone').val();
                    let req_date_from = $('#request-date-from').val();
                    let req_date_to = $('#request-date-to').val();
                    let req_status = ($("#request_status").data('tokenize2').toArray());
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


                    if (req_status != '')  data['req_status'] = req_status;

                    if (task_status != '')  data['task_status'] = task_status;

                    //console.log(task_status);

                    if (reqTypes != '') data['reqTypes'] = reqTypes;


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

                    data['id'] = {{$id}};


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
                    data: 'status',
                    name: 'tasks.status'
                },
                {
                    data: 'user_name',
                    name: 'users.name'
                },
                {
                    data: 'name',
                    name: 'customers.name'
                },
                {
                    data: 'mobile',
                    name: 'customers.mobile'
                },
                {
                    data: 'content',
                    name: 'content'
                },
                {
                    data: 'user_note',
                    name: 'user_note'
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

                $('td', row).eq(5).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(5).attr('title', data.content); // to show other text of comment

                $('td', row).eq(6).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(6).attr('title', data.user_note); // to show other text of comment

                $('td', row).eq(0).addClass('reqDate'); // 6 is index of column
            },
            initComplete: function() {
                let api = this.api();
                $("#filter-search-req").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
                    $('#myModal').modal('hide');

                    $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                })


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

                });
            },
        });
    });


    //
</script>
@endsection
