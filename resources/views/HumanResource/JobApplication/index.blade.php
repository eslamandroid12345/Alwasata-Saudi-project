@extends('layouts.content')

@section('title')
طلبات التوظيف
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

    .reqType {
        width: 2%;
    }

    table {
        text-align: center;
    }
    
</style>
{{-- NEW STYLE   --}}
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
        <h3> طلبات التوظيف: </h3>
    </div>
</div>
<br>

<div class="tableBar">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8">
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
            <div class="col-lg-4 mt-lg-0 mt-4">
                @include('Admin.datatable_display_number')                        
                <div id="dt-btns" class="tableAdminOption">
                    
                </div>
            </div>
        </div>
    </div>

            
<!-- data-page-length='5' -->
    <div class="dashTable">
        <table id="pendingReqs-table" class="table table-bordred table-striped data-table" data-page-length='10'>
            <thead>
                <tr>

                    <th> الاسم</th>
                    <th> البريد الالكترونى</th>
                    <th> المسمى الوظيفى</th>
                    <th> الجامعه</th>
                    <th> الجنسيه</th>
                    <th> الراتب المتوقع</th>
                    <th> التخصص المرغوب</th>
                    <th> تصنيف الطلب</th>
                    <th> تاريخ الطلب</th>
                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>

                </tr>
            </thead>
            <tbody>

            </tbody>

        </table>
    </div>
</div>
@endsection
@section('updateModel')
    @include('HumanResource.JobApplication.filter_page')
@endsection
@section('scripts')
<script>  
    $(document).ready(function() {
       
            //================== this will display data in datatable =============
            var dt = $('.data-table').DataTable({
            // "pageLength": 50,
                "language": {
                    "url": "{{route('datatableLanguage')}}",
                    buttons: {
                        pageLength: "عرض",
                        excelHtml5: "اكسل",
                        print: "طباعة",
                    }
                },
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    'excelHtml5',
                    'print',
                    {
                        text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                        action: function (e, dt, node, config) {
                            $('#myModal').modal('show');
                        }
                    }
                ],
                processing: true,
                serverSide: true,
                ajax: {
                   url: "{{ route('HumanResource.job_applications_datatable') }}",
                   data:function(data){
                        data.job_title=$("#job_title").val()
                        data.nationality_id=$("#nationality_id").val()
                        data.duration_type=$("#duration_type").val()
                        data.salary_from=$("#salary_from").val()
                        data.salary_to=$("#salary_to").val()
                        data.type_id=$("#type_id").val()
                        data.specialization=$("#specialization").val()
                        data.need_traning=$("#need_traning").val()
                   }
                },
                columns: [
                    {data: 'name', name: 'name',searchable:true},
                    {data: 'email', name: 'email',searchable:true},
                    {data: 'jobtitle', name: 'jobtitle'},
                    {data: 'university', name: 'university'},
                    {data: 'nationality', name: 'nationality'},
                    {data: 'salary', name: 'salary'},
                    {data: 'specialization', name: 'specialization'},
                    {data: 'type', name: 'type'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action'},
                ],
                initComplete: function() {
                    // ========================= when click serch button =============================
                    $("#filter-search-job").on('click', function () {
                        dt.draw();
                        $('#myModal').modal('hide');
                    });
                    // ================================================================================
                    // $(".paginate_button").addClass("pagination-circle");
                    /* To Adaptive with New Design */
                    $('#example-search-input').keyup(function(){
                        dt.search($(this).val()).draw() ;
                    });
                    
                    //====================draw table when change in display number=====================
                    $('#display_number').focusout(function(){
                        dt.page.len( $(this).val()).draw();
                    });
                    //==================================================================================
                    dt.buttons().container().appendTo('#dt-btns');
                    $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
                    $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                    $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                    $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                    $('.buttons-excel').addClass('no-transition custom-btn');
                    $('.buttons-print').addClass('no-transition custom-btn');
                    // $('.buttons-collection').addClass('no-transition custom-btn');

                    $('.tableAdminOption span').tooltip(top)
                    $('button.dt-button').tooltip(top)

                    /* To Adaptive with New Design */

                },
                "order": [
                    [5, "desc"]
                ], // Order on init. # is the column, starting at 0
                createdRow: function(row, data, index) {
                    $('td', row).eq(1).addClass('commentStyle');
                    $('td', row).eq(1).attr('title', data.content);
                },
            });
    });
</script>
@endsection
