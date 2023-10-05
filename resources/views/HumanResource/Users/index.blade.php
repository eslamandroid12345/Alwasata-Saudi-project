@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'users') }}
@endsection


@section('css_style')

{{--    OLD STYLE --}}
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

    .reqType {
        width: 2%;
    }
</style>

{{--    NEW STYLE   --}}
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
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3>  {{ MyHelpers::admin_trans(auth()->user()->id,'users') }} :</h3>
        <div class="addBtn">
            <a href="{{ route('HumanResource.addUserPage')}}">
                <button>
                    <i class="fas fa-plus-circle"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'user') }}</button>
            </a>
        </div>
    </div>
</div>

<div class="tableBar">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-2">
                <div class="selectAll">

                </div>
            </div>
            <div class="col-lg-8 ">
                <div class="tableUserOption  flex-wrap ">
                    <div class="addBtn col-md-5 mt-lg-0 mt-3">

                    </div>
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
                <th>م</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'email') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'role') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'user status') }}</th>
                <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
            </tr>
            </thead>
            <tbody>

            </tbody>

        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('#myusers-table').DataTable({
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
            buttons: [
                'excelHtml5',
                'print',
                'pageLength'
            ],

            processing: true,
            serverSide: true,
            ajax: "{{ route('HumanResource.users.datatable') }}",
            columns: [

                {
                    "name": 'id',
                    "data": "id"
                },


                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            "order": [
                [6, "desc"]
            ], // Order on init. # is the column, starting at 0
            createdRow: function(row, data, index) {

            },

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
@endsection
