@extends('layouts.content')

@section('title')
 الدعم الفني
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

    .commentStyle {
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .reqNum {
        width: 1%;
    }

    th {
        text-align: center;
    }

    td {
        text-align: center;
    }

    .reqType {
        width: 2%;
    }
</style>
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">


@endsection

@section('customer')



@if(session()->has('message'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message') }}
</div>
@endif

@if(session()->has('message2'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>


<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center  flex-wrap">
        <h3>طلبات الدعم الفني :</h3>
        <div class="form-check form-check-inline mx-4">
            <input class="form-check-input" type="radio" name="type_of_helpdesk" id="inlineRadio1" value="">
            <label class="form-check-label" for="inlineRadio1">الكل</label>
        </div>

        {{-- <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="type_of_helpdesk" id="inlineRadio2" value="0">
            <label class="form-check-label" for="inlineRadio2">طلبات الزوار </label>
        </div> --}}

        <div class="form-check form-check-inline mx-4">
            <input class="form-check-input" type="radio" name="type_of_helpdesk" id="inlineRadio2" value="1">
            <label class="form-check-label" for="inlineRadio2"> العملاء  </label>
        </div>

        <div class="form-check form-check-inline mx-4">
            <input class="form-check-input" type="radio" name="type_of_helpdesk" id="inlineRadio3" value="2">
            <label class="form-check-label" for="inlineRadio3"> الموظفين</label>
        </div>

    </div>
</div>

<br>



@if ($helpDeskReqs > 0)
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
                    <div id="dt-btns" class="tableAdminOption">
                        {{-- Here We Will Add Buttons of Datatable  --}}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="dashTable">
        <table class="table table-bordred table-striped data-table" id="helpDesk-table">
            <thead>
                <tr>
                    <th style="text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                    <th style="text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</th>
                    <th style="text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                    <th style="text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'description') }}</th>
                    <th style="text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'the replay') }}</th>
                    <th style="text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                    <th style="text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'has_request') }}</th>
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
    <h2 style=" text-align: center;font-size: 20pt;">لايوجد طلبات دعم فني</h2>
</div>
@endif


@endsection


@section('updateModel')

@endsection


@section('scripts')


<script>
    $(document).ready(function() {
        var dt = $('#helpDesk-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    print: "طباعة",
                    pageLength: "عرض",

                }
            },
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "الكل"]
            ],
            dom: 'Bfrtip',
            scrollY: '50vh',
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
            ajax: {
                url:"{{ url('admin/helpDesk-datatable') }}",
                data:function(d){
                    d.type_of_helpdesk=$('input[name="type_of_helpdesk"]:checked').val()
                }
            },
            columns: [

                {
                    "targets": 0,
                    "data": "created_at", // first history related to the request
                    "name": "help_desks.created_at",
                    "render": function(data, type, row, meta) {
                        return data.split(" ").join("<br/>");
                    }


                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'mobile',
                    name: 'mobile'
                },
                {
                    data: 'descrebtion',
                    name: 'descrebtion'
                },
                {
                    data: 'replay',
                    name: 'replay'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'has_request',
                    name: 'has_request'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            initComplete: function() {

                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function() {
                    dt.search($(this).val()).draw();
                })

                $('input:radio').on('click', function(e) {
                    dt.draw();
                });


                dt.buttons().container()
                    .appendTo('#dt-btns');

                $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

                /* To Adaptive with New Design */
            },
            // Order on init. # is the column, starting at 0
            createdRow: function(row, data, index) {


                $('td', row).eq(3).addClass('commentStyle');
                $('td', row).eq(3).attr('title', data.descrebtion);
                $('td', row).eq(4).addClass('commentStyle');
                $('td', row).eq(4).attr('title', data.replay);

            },
        });
    });

    //-----------------------------------



    //////////////////////////////////////////////////////#
</script>
@endsection
