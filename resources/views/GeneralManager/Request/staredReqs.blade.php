@extends('layouts.content')


@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Stared Requests') }}
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
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'Stared Requests') }}  :</h3>
    </div>
</div>
<br>

    @if (!empty($requests[0]))
    <div class="tableBar">
        <div class="topRow">
            <div class="row   text-center text-lg-left align-items-center">
                <div class="col-lg-2">
                    <div class="selectAll">
                        <div class="form-check">
                            <input type="checkbox" id="allreq" class="form-check-input" onclick="chbx_toggle1(this);" />
                            <label class="form-check-label" for="allreq">تحديد الكل </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-10  ">
                    <div class="tableUserOption  flex-wrap justify-content-lg-end justify-content-center">
                        <div class="addBtn  mt-lg-0 mt-3 ml-4">
                            <button class="DarkBlue" onclick="getReqests()"><i class="fas fa-reply-all"></i>
                                {{--                                {{ MyHelpers::admin_trans(auth()->user()->id,'Restore Request') }}--}}
                                اظهار أرقام الطلبات المحددة

                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

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
                            {{--  Here We Will Add Buttons of Datatable  --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashTable">
            <table class="table table-bordred table-striped data-table" >
                <thead>
                <tr>
                    <th> </th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>
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
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Star Requests') }}</h2>
    </div>

    @endif

@endsection


@section('scripts')

<script>
$(document).ready( function () {
      var dt = $('.data-table').DataTable({
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
            // 'copyHtml5',
            'excelHtml5',
            // 'csvHtml5',
            // 'pdfHtml5' ,
            'print',
            'pageLength'
        ],
        processing: true,
        serverSide: true,
        ajax: "{{ url('generalmanager/staredreqs-datatable') }}",
        columns: [

            {
                    "targets": 0,
                    "data": "id",
                    "render": function(data, type, row, meta) {
                        return '<input type="checkbox" id="chbx" name="chbx[]" value="' + data + '"/>';
                    }
                },
            { data: 'id', name: 'id' },
            { data: 'req_date', name: 'req_date' },
            { data: 'type', name: 'type' },
            { data: 'name', name: 'name' },
            { data: 'status', name: 'status' },
            { data: 'source', name: 'source' },
            { data: 'comment', name: 'comment' },
            { data: 'action', name: 'action' }
        ] ,
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

          },
    });
} );
</script>
@endsection
