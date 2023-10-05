@extends('layouts.content')
@section('nav_actions')

{{-- Grid && List --}}
<div class="table-cell d-flex align-items-center">
    <div class="table-display d-flex align-items-center">
        <a class="table-grid selected ms-3"  href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="20.428" height="20.428" viewBox="0 0 20.428 20.428">
            <g id="Icon_feather-grid" data-name="Icon feather-grid" transform="translate(1 1)">
                <path id="Path_47" data-name="Path 47" d="M11.666,4.5H4.5v7.166h7.166Z" transform="translate(6.762 -4.5)" fill="#d8d8d8" stroke="#d8d8d8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                <path id="Path_48" data-name="Path 48" d="M28.167,4.5H21v7.166h7.166Z" transform="translate(-21 -4.5)" fill="#d8d8d8" stroke="#d8d8d8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                <path id="Path_49" data-name="Path 49" d="M28.167,21H21v7.166h7.166Z" transform="translate(-21 -9.738)" fill="#d8d8d8" stroke="#d8d8d8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                <path id="Path_50" data-name="Path 50" d="M11.666,21H4.5v7.166h7.166Z" transform="translate(6.762 -9.738)" fill="#d8d8d8" stroke="#d8d8d8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
            </g>
            </svg>
        </a>
        <a class="table-list" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="24.348" height="19.783" viewBox="0 0 24.348 19.783">
            <path
                id="Icon_awesome-list-ul"
                data-name="Icon awesome-list-ul"
                d="M22.065,3.375a2.283,2.283,0,1,1-2.283,2.283A2.283,2.283,0,0,1,22.065,3.375Zm0,7.609a2.283,2.283,0,1,1-2.283,2.283A2.283,2.283,0,0,1,22.065,10.984Zm0,7.609a2.283,2.283,0,1,1-2.283,2.283,2.283,2.283,0,0,1,2.283-2.283Zm-21.3.761H15.978a.761.761,0,0,1,.761.761v1.522a.761.761,0,0,1-.761.761H.761A.761.761,0,0,1,0,21.636V20.114A.761.761,0,0,1,.761,19.353Zm0-15.217H15.978a.761.761,0,0,1,.761.761V6.418a.761.761,0,0,1-.761.761H.761A.761.761,0,0,1,0,6.418V4.9A.761.761,0,0,1,.761,4.136Zm0,7.609H15.978a.761.761,0,0,1,.761.761v1.522a.761.761,0,0,1-.761.761H.761A.761.761,0,0,1,0,14.027V12.505A.761.761,0,0,1,.761,11.745Z"
                transform="translate(0 -3.375)"
                fill="#d8d8d8"
            ></path>
            </svg>
        </a>
    </div>
</div>

{{-- Print - Show -Search --}}
<div class="table-cell d-flex align-items-center mt-3 mt-md-0" id="new-dt-btns"></div>

@endsection

@section('title')
    {{$title }}
@endsection

@section('css_style')

<style>
    .mov, .green{
    background-color: #fff;
    }
    .hidden{
        display: none;
    }
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
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'pur-pre') }}:</h3>
    </div>
</div>


@if ($requests >0)
    <div class="row hidden" id="grid-cont">
    </div>

    <div class="col-12">
        <div class="portlet">
            <div class="portlet__body">
                <div class="tablee-responsive">
                    <div class="dashTable">
                        <table class="table table-custom table-striped table-custom-3 table-resizable data-table">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="m-checkbox mb-1">
                                            <input type="checkbox" id="allreq" onclick="chbx_toggle1(this);" />
                                            <span class="checkmark border-white"></span>
                                        </label>
                                    </th>
                                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>
                                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'notes_status_quality') }}</th>
                                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="middle-screen">
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'pur-prep') }}</h2>
    </div>
@endif

@endsection

@section('scripts')
<script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>

<script>
    $(document).ready(function() {
        var dt = $('.data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
            buttons: {
                excelHtml5: "اكسل",
                print: "طباعة",
                pageLength: "عرض",

            }
        },
        scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                // rightColumns: 1
                rightColumns: 0
            },
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "الكل"]
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
            scrollY:'50vh',
            processing: true,
            serverSide: true,
            ajax: "{{ url('agent/prepaymentreqs-datatable') }}",
            columns: [

                {
                    "targets": 0,
                    "data": "id",
                    "render": function(data, type, row, meta) {
                        return '<input type="checkbox" id="chbx" name="chbx[]" onchange="disabledButton()" value="' + data + '"/>';
                    }
                },

                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'req_date',
                    name: 'req_date'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'source',
                    name: 'source'
                },
                {
                    data: 'comment',
                    name: 'comment'
                },
                {
                    data: 'quacomment',
                    name: 'quacomment'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            initComplete: function() {
                let api = this.api();
                $('#grid-cont').html('');
                $("#filter-search-req").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
                    $('#grid-cont').html('');
                    $('#myModal').modal('hide');
                });

                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                })

                $('#nav-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                    $('#grid-cont').html('');
                })

                dt.buttons().container().appendTo( '#new-dt-btns' );




                // dt.buttons().container()
                //     .appendTo( '#dt-btns' );

                // $( ".dt-button" ).last().html('<i class="fas fa-search"></i>').attr('title','بحث') ;
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title','تصدير') ;
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title','طباعة') ;
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title','عرض') ;

                $( ".dt-button" ).addClass(' btn-icon');

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');


                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

            },
            createdRow: function(row, data, index) {
                $('td', row).eq(6).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(6).attr('title', data.comment); // to show other text of comment

                $('td', row).eq(7).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(7).attr('title', data.quacomment);

                $('td', row).eq(0).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(1).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(2).addClass('reqType'); // 6 is index of column
                $('td', row).eq(3).addClass('reqType'); // 6 is index of column

                $('td', row).eq(2).attr('title', data.created_at);
                $('td', row).eq(2).attr('data-title', data.created_at);
                $('td', row).eq(2).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(2).attr('data-bs-placement', 'top');

                $('td', row).eq(4).attr('title', data.name);
                $('td', row).eq(4).attr('data-title', data.name);
                $('td', row).eq(4).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(4).attr('data-bs-placement', 'top');

                $('td', row).eq(5).attr('title', data.status);
                $('td', row).eq(5).attr('data-title', data.status);
                $('td', row).eq(5).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(5).attr('data-bs-placement', 'top');

                $('td', row).eq(6).attr('title', data.source);
                $('td', row).eq(6).attr('data-title', data.source);
                $('td', row).eq(6).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(6).attr('data-bs-placement', 'top');

                $('td', row).eq(7).attr('title', data.class_id_agent);
                $('td', row).eq(7).attr('data-title', data.class_id_agent);
                $('td', row).eq(7).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(7).attr('data-bs-placement', 'top');

                $('td', row).eq(8).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(8).attr('title', data.comment); // to show other text of comment
                $('td', row).eq(8).attr('data-title', data.comment);
                $('td', row).eq(8).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(8).attr('data-bs-placement', 'top');

                $('td', row).eq(9).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(9).attr('title', data.quacomment); // to show other text of comment
                $('td', row).eq(9).attr('data-title', data.quacomment);
                $('td', row).eq(9).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(9).attr('data-bs-placement', 'top');

                addCardGrid(data);

            },
        });
    });


    function disabledButton() {
        if ($(':checkbox[name="chbx[]"]:checked').length > 0) {
            document.getElementById("restoreAll").disabled = false;
            document.getElementById("restoreAll").style = "";
        } else {
            document.getElementById("restoreAll").disabled = true;
            document.getElementById("restoreAll").style = "cursor: not-allowed";
        }
    }


    function chbx_toggle1(source) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
        }
        disabledButton();
    }

    ///////////////////////////////////
    function addCardGrid(data) {
            var dde = ``;
            for (let index = 0; index < data.action_grid.length; index++) {
                // const element = data.action_grid[index];
                dde += `
                <li>
                                    <a class="dropdown-item" href="`+data.action_grid[index]['url']+`">
                                        <i class="`+data.action_grid[index]['icon']+`"></i>
                                    <span class="font-medium">`+data.action_grid[index]['title']+`</span>
                                    </a>
                                </li>
                `;

            }
            if(data.statusReq == 'جديد')
            {
                $start = `<i class="fas fa-star"></i>`;
            }else{
                $start = `<i class="fas fa-star-o"></i>`;
            }

            $('#grid-cont').append(`
            <div class="col-lg-3 col-sm-6">
                        <div class="widget__item-order widget-`+data.card_class+`">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <div class="d-flex align-items-center">
                            <h6 class="font-medium">`+data.name+`</h6>
                            </div>
                            <div class="d-flex align-items-center">
                            <div class="btn-star ms-3 add-to-special-orders">
                                `+$start+`
                            </div>
                            <div class="dropdown">
                                <button class="btn bg-white p-1" data-bs-toggle="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="3.548" height="16.219" viewBox="0 0 3.548 16.219">
                                    <path
                                    id="menu"
                                    d="M1.774,3.548A1.851,1.851,0,0,1,.507,3.016,1.765,1.765,0,0,1,0,1.774,2,2,0,0,1,.507.507,1.781,1.781,0,0,1,1.774,0,1.882,1.882,0,0,1,3.016.507a1.8,1.8,0,0,1,.532,1.267A1.819,1.819,0,0,1,1.774,3.548Zm7.577-.532a1.791,1.791,0,0,0,.532-1.242A1.9,1.9,0,0,0,9.351.507,1.765,1.765,0,0,0,8.109,0,1.946,1.946,0,0,0,6.842.507a1.781,1.781,0,0,0-.507,1.267,1.882,1.882,0,0,0,.507,1.242,1.744,1.744,0,0,0,2.509,0Zm6.336,0a1.791,1.791,0,0,0,.532-1.242A1.9,1.9,0,0,0,15.687.507,1.765,1.765,0,0,0,14.445,0a1.946,1.946,0,0,0-1.267.507,1.781,1.781,0,0,0-.507,1.267,1.882,1.882,0,0,0,.507,1.242,1.744,1.744,0,0,0,2.509,0Z"
                                    transform="translate(0 16.219) rotate(-90)"
                                    fill="#acacac"
                                    ></path>
                                </svg>
                                </button>
                                <ul class="dropdown-menu">
                                `+dde+`
                                </ul>
                            </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6>`+data.req_date+`</h6>
                            <div class="label label-solid-`+data.card_class+`">`+data.status+`</div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="font-medium">`+data.source+`</h6>
                            <h5>`+data.type+`</h5>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <h5>تاريخ النزول</h5>
                            <h5>`+data.agent_date+`</h5>
                        </div>
                        <hr />
                        <h6 class="widget__item-text">`+data.comment+`</h6>
                        </div>
                    </div>
            `);
        }

        $(document).on('click', '.table-grid', function(){
            $('#grid-cont').removeClass('hidden');
            $('.DTFC_ScrollWrapper').addClass('hidden');
        })
        $(document).on('click', '.table-list', function(){
            $('#grid-cont').addClass('hidden');
            $('.DTFC_ScrollWrapper').removeClass('hidden');
        })

</script>
@endsection
