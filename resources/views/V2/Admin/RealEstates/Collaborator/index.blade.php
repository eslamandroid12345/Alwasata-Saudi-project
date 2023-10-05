@extends('layouts.content')
@section('title')
    العقارات - المضافة من المتعاونيين
@endsection
@section('css_style')
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection
@section('customer')
    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>العقارات - المضافة بواسطة المتعاونيين</h3>
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
                    <th>إسم المتعاون</th>
                    <th>نوع العقار</th>
                    <th>سعر العقار</th>
                    <th>حالة العقار</th>
                    <th>الوصف</th>
                    <th>عنوان العقار</th>
                    <th>المدينة</th>
                    <th>المنطقة</th>
                    <th>الحي</th>
                    <th>تاريخ الاضافة</th>
                    <th>عرض </th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
@section('updateModel')
@include('V2.Admin.RealEstates.Collaborator.filter')
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
                lengthMenu: [
                    [10, 50, 100, 150, 200],
                    [10, 50, 100, 150, 200],
                    // [10, 50, 500, 1000, 2000]
                ],
                processing: true,
                serverSide: true,
                paging: true,
                ajax: ({
                    'url': "{{ url('admin/collaborator/real-estates-index') }}",
                    'method': 'GET',
                    'data': function (d) {
                        d.uniqueRealTypes=$('#uniqueRealTypes').val()
                        d.collaborator=$('#collaborator').val()
                        d.uniqueCities=$('#uniqueCities').val()
                    },
                }),
                columns: [
                    {
                        data: 'collaborator_name',
                        name: 'creator.name',
                    },
                    {
                        data: 'property_type',
                        name: 'type.value',
                    },
                    {
                        data: 'fixed_price',
                        name: 'fixed_price',
                    },
                    {
                        data: 'is_published',
                        name: 'is_published',
                    }, {
                        data: 'description',
                        name: 'description',
                    },
                    {
                        data: 'address',
                        name: 'address',
                    },
                    {
                        data: 'city',
                        name: 'city.value',
                    },
                    {
                        data: 'area',
                        name: 'area.value',
                    },
                    {
                        data: 'district',
                        name: 'district.value',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },
                ],
                "order": [
                    [9, "desc"]
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
            });
        });
    </script>
@endsection
