@extends('layouts.content')
@section('title')
    العقارات - طلبات العملاء
@endsection
@section('css_style')
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection
@section('customer')
    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3> العقارات - طلبات العملاء</h3>
            {{-- <select name="uniqueRealTypes" id="uniqueRealTypes" class="form-control w-25">
                <option value="">نوع العقار</option>
                @foreach ($uniqueRealTypes as $realType)
                    <option value="{{ $realType->id }}">{{ $realType->value }}</option>
                @endforeach
            </select>

            <select name="uniqueAgents" id="uniqueAgents" class="form-control w-25">
                <option value="">استشاري المبيعات</option>
                @foreach ($uniqueAgents as $agent)
                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                @endforeach
            </select> --}}
        </div>
    </div>
    <br>
    <div class="messages-box" style="display: none;" id="list-loading">
        <div id="loading"><img src="{{ url('assest/images/loadingLogo.png') }}" alt=""> </div>
    </div>
    <div class="tableBar">
        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-lg-8 ">
                    <div class="tableUserOption  flex-wrap ">
                        <div class="input-group col-md-6 mt-lg-0 mt-3">
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
                    <div id="tableAdminOption" class="tableAdminOption">
                        <div id="dt-btns" class="tableAdminOption">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-lg-2">
                    <div class="selectAll">
                        <div class="form-check">
                            <input type="checkbox" id="allreq" class="form-check-input" onclick="chbx_toggle1(this);"/>
                            <label class="form-check-label" for="allreq">تحديد الكل </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashTable">
            <table class="table table-bordred table-striped data-table" id="myreqs-table">
                <thead>
                <tr>
                    <th>إسم العميل</th>
                    <th>رقم الجوال</th>
                    <th>إسم استشاري المبيعات</th>
                    <th>نوع العقار</th>
                    <th>سعر العقار</th>
                    <th>مساحة العقار</th>
                    <th>تاريخ طلب العقار</th>
                    <th>المدينة</th>
                    <th>المنطقة</th>
                    <th>الحي</th>
                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>

                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
@section('updateModel')
@include('V2.Admin.RealEstates.Customer.filter')
@endsection
@section('scripts')
    <script>
        let datatable
        $(document).ready(function () {
            datatable = $('.data-table').DataTable({
                language: {
                    "url": "{{route('datatableLanguage')}}",
                    buttons: {
                        excelHtml5: "اكسل",
                        print: "طباعة",
                        pageLength: "عرض",
                    }
                },
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
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
                ajax: ({
                    'url': "{{ url('admin/customer/real-estates-index') }}",
                    'method': 'GET',
                    'data': function (d) {
                        d.uniqueRealTypes=$('#uniqueRealTypes').val()
                        d.uniqueAgents=$('#uniqueAgents').val()
                        d.uniqueCities=$('#uniqueCities').val()
                    },
                }),
                columns: [
                    {
                        data: 'customer.name',
                        name: 'name',
                    },
                    {
                        data: 'customer.mobile',
                        name: 'mobile',
                    },
                    {
                        data: 'agent_name',
                        name: 'agent_name',
                    },
                    {
                        data: 'value',
                        name: 'value',
                    },
                    {
                        data: 'price',
                        name: 'price',
                    },
                    {
                        data: 'distance',
                        name: 'distance',
                    },
                    {
                        data: 'req_date',
                        name: 'req_date',
                    },
                    {
                        data: 'city.value',
                        name: 'city.value',
                    },
                    {
                        data: 'area.value',
                        name: 'area.value',
                    },
                    {
                        data: 'district.value',
                        name: 'district.value',
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],

                initComplete: function() {
                    let api = this.api();
                    $("#filter-search-req").on('click', function (e) {
                        e.preventDefault();
                        api.draw();
                        $('#myModal').modal('hide');
                    });


                    $(".paginate_button").addClass("pagination-circle");
                    /* To Adaptive with New Design */
                    $('#example-search-input').keyup(function(){
                        datatable.search($(this).val()).draw() ;
                    })

                    datatable.buttons().container().appendTo( '#dt-btns' );

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
            "order": [
                [6, "desc"]
            ],
            });
        });

        // $(document).on('change','#uniqueRealTypes,#uniqueAgents',function(){
        //     datatable.draw();
        // })

    </script>
@endsection
