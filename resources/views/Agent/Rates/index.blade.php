@extends('layouts.content')

@section('title')
    تقييمات الخدمة
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

    .active-filter{
        background-color: #0f5b94;
        color: #fff;
    }
</style>
<!--NEW 2/2/2020 for hijri datepicker-->
<link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />

{{--    NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">

@endsection

@section('customer')


<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3>  تقييمات الخدمة  :</h3>
    </div>

    <nav class="mt-3">
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
          <button class="nav-link buttons-tabs-filter mx-1 active-filter" value="0"  data-bs-toggle="tab"  type="button" role="tab" > التقييمات الحاليه</button>
          <button class="nav-link buttons-tabs-filter mx-1" value="1"  data-bs-toggle="tab"  type="button" role="tab" >تقييمات تمت معالجتها</button>
        </div>
    </nav>
</div>


@if ($customers > 0)

    @if(Session::has('message7'))
        <div class="alert alert-danger">
            {{Session::get('message7')}}    
        </div>
    @endif

    @if(Session::has('message2'))
        <div class="alert alert-danger">
            {{Session::get('message2')}}    
        </div>
    @endif

    <div  class="tableBar">
        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">

                <div class="col-lg-9">
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
                <div class="col-lg-3 mt-lg-0 mt-3">
                    <div  id="dt-btns" class="tableAdminOption">

                    </div>
                </div>
            </div>
        </div>

        <div class="dashTable">
            <table id="mycustomer-table" class="table table-bordred table-striped data-table">
                <thead>
                <tr>

                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'id') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                    <th>التقييم بالنجوم</th>
                    <th>التعليق</th>
                    <th>تاريخ التقييم</th>
                    <th>تمت المعالجة</th>
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
        <h2 style=" text-align: center;font-size: 20pt;">لا يوجد تقييمات </h2>
    </div>

@endif
@endsection

@section('updateModel')
    @include('Admin.Rates.filter')
@endsection

@section('scripts')


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
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
        dom: 'Bfrtip',
        buttons: [
            // 'copyHtml5',
            'excelHtml5',
            // 'csvHtml5',
            // 'pdfHtml5' ,
            'print',
            'pageLength',
            {
                text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                action: function (e, dt, node, config) {
                    $('#myModal').modal('show');
                }
            }
        ],
        processing: true,
        serverSide: true,
        order: [[6, 'desc']],
        ajax: {
                'url': "{{ url('agent/rates-datatable') }}",
                'method': 'Get',
                'data': function (data) {
                    data.agent_id = $('#agent_id').val();
                    data.rate_date = $('#date-of-rate').val();
                    data.stars = $('#stars').val();
                    data.is_processed = $('.active-filter').val();
                },
            },
        columns: [
            {data: 'id',name: 'id' },
            {data: 'name',name: 'name'},
            {data: 'mobile',name: 'mobile'},
            {data: 'stars',name: 'stars' },
            {data: 'comment',name: 'comment'},
            {data: 'date_of_rate',name: 'date_of_rate' },
            {data: 'is_processed',name: 'is_processed'},
            {data: 'action',name: 'action'}
        ],
        initComplete: function() {
            let api = this.api();
            $("#filter-search-req").on('click', function (e) {
                e.preventDefault();
                api.draw();
                $('#myModal').modal('hide');
            });


            // Khaled
            $(document).on('click','.buttons-tabs-filter',function(){
                $('.buttons-tabs-filter').each(function(){
                    $(this).removeClass('active-filter')
                })

                $(this).addClass('active-filter')
                dt.draw();
            })

            $(document).on('click','.make-it-processed',function(){
                console.log($(this).attr('data-id'))
                var customer_id=$(this).attr('data-id')
                //alert(customer_id);
                $.ajax({
                    url:"{{ route('agent.updateIsProcessedRate') }}",
                    type:"get",
                    data:{
                        customer_id : customer_id
                    },
                    success:function(data){
                        dt.draw();
                    },
                    error:function(){

                    }
                })
            })


            $(".paginate_button").addClass("pagination-circle");
            /* To Adaptive with New Design */
            $('#example-search-input').keyup(function(){
                dt.search($(this).val()).draw() ;
            })

            dt.buttons().container()
                .appendTo( '#dt-btns' );

            $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
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
