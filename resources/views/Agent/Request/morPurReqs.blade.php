@extends('layouts.content')


@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests') }}
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

    .newReq {
        background:rgba(98, 255, 0, 0.4) ! important;
    }

    .needFollow {
        background: rgba(12, 211, 255, 0.3) ! important;
    }

    .noNeed {
        background:  rgba(0, 0, 0, 0.2)  ! important;
    }

    .wating {
        background:   rgba(255, 255, 0, 0.2)  ! important;
    }

    .watingReal {
        background:   rgba(0, 255, 42, 0.2)  ! important;
    }

    .rejected {
        background:   rgba(255, 12, 0, 0.2)  ! important;
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
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests') }}:</h3>
    </div>
</div>



@if ($check == 0)
    @if ($requests[0]->count() != 0)
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

                    @foreach ($requests as $request => $reqs)
                        <!-- nested for each to the arry of list-->
                        @foreach ($reqs as $req)
                            <tr>


                                <td>{{$req->id}}</td>
                                <td>{{$req->req_date}}</td>
                                <td>{{$req->type}}</td>
                                <td>{{$req->name}}</td>

                                @if($req->statusReq== 0)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'new req') }}</td>
                                @elseif ($req->statusReq== 1)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'open req') }}</td>
                                @elseif ($req->statusReq== 2)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'archive in sales agent req') }}</td>
                                @elseif ($req->statusReq== 3)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating sales manager req') }}</td>
                                @elseif ($req->statusReq== 4)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected sales manager req') }}</td>
                                @elseif ($req->statusReq== 5)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'archive in sales manager req') }}</td>
                                @elseif ($req->statusReq== 6)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating funding manager req') }}</td>
                                @elseif ($req->statusReq== 7)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected funding manager req') }}</td>
                                @elseif ($req->statusReq== 8)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'archive in funding manager req') }}</td>
                                @elseif ($req->statusReq== 9)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating mortgage manager req') }}</td>
                                @elseif ($req->statusReq== 10)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected mortgage manager req') }}</td>
                                @elseif ($req->statusReq== 11)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'archive in mortgage manager req') }}</td>
                                @elseif ($req->statusReq== 12)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating general manager req') }}</td>
                                @elseif ($req->statusReq== 13)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected general manager req') }}</td>
                                @elseif ($req->statusReq== 14)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'archive in general manager req') }}</td>
                                @elseif ($req->statusReq== 15)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'Canceled') }}</td>
                                @elseif ($req->statusReq== 16)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'Completed') }}</td>
                                @elseif ($req->statusReq== 18)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating sales manager req') }}</td>
                                @elseif ($req->statusReq== 19)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating sales agent req') }}</td>
                                @elseif ($req->statusReq== 20)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected sales manager req') }}</td>
                                @elseif ($req->statusReq== 21)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating funding manager req') }}</td>
                                @elseif ($req->statusReq== 22)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected funding manager req') }}</td>
                                @elseif ($req->statusReq== 23)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating general manager req') }}</td>
                                @elseif ($req->statusReq== 24)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'cancel mortgage manager req') }}</td>
                                @elseif ($req->statusReq== 25)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected general manager req') }}</td>
                                @elseif ($req->statusReq== 26)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'Completed') }}</td>
                                @elseif ($req->statusReq== 27)
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'Canceled') }}</td>
                                @else
                                    <td>{{ MyHelpers::admin_trans(auth()->user()->id,'Undefined') }}</td>
                                @endif
                                <td>{{$req->source}}</td>
                                <td>{{$req->comment}}</td>
                                <td>{{$req->comment}}</td>
                                <td>
                                    <div class="tableAdminOption">
                                        <span class="item pointer" id="open" data-id="{{$req->id}}" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}">
                                            <a href="{{ route('agent.morPurRequest',$req->id)}}"> <i class="fas fa-eye"></i></a>
                                        </span>
                                    </div>
                                </td>
                            </tr>

                        @endforeach

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="middle-screen">
            <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }} </h2>
        </div>

    @endif

@else
    <div class="middle-screen">
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }} </h2>
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
                    pageLength: "عرض",
                    excelHtml5: "اكسل",
                    print: "طباعة",
                   

                }
            },
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            columns: [


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
            "order": [[ 1, "desc" ]], // Order on init. # is the column, starting at 0
            createdRow: function(row, data, index) {

                if ( data.status === "طلب جديد") {
                    $(row).addClass( 'newReq' );

                }

                if ( data.class_id_agent === "يحتاج متابعة") {
                    $(row).addClass( 'needFollow' );
                }

                if ( data.class_id_agent === "لا يرغب") {
                    $(row).addClass( 'noNeed' );
                }

                if ( data.class_id_agent === "بانتظار الأوراق") {
                    $(row).addClass( 'wating' );
                }

                if ( data.class_id_agent === "يبحث عن عقار") {
                    $(row).addClass( 'watingReal' );
                }

                if ( data.class_id_agent === "مرفوض") {
                    $(row).addClass( 'rejected' );
                }


                $('td', row).eq(6).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(6).attr('title', data.comment); // to show other text of comment

                $('td', row).eq(7).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(7).attr('title', data.quacomment);

                $('td', row).eq(0).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(1).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(2).addClass('reqType'); // 6 is index of column
                $('td', row).eq(3).addClass('reqType'); // 6 is index of column
            },
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "الكل"]
            ],
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                // 'copyHtml5',
                'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
                
            ],
            initComplete: function() {
                let api = this.api();
                $("#filter-search-req").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
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

                // $( ".dt-button" ).last().html('<i class="fas fa-search"></i>').attr('title','بحث') ;
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title','تصدير') ;
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title','طباعة') ;
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title','عرض') ;

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');


                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

            },

        });
    });
</script>
@endsection
