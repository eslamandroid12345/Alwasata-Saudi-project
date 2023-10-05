@extends('layouts.content')

@section('title', __('reports.report3'))

@section('customer')
    {{-- For Search Parameters   --}}
    @include('Charts.movedRequestWithPostiveChart-parameters')

    <div class="main-content">
        <div class="section_content section_content--p30">
            <div class="container-fluid">
                <div class="dashTable" style="padding: 20px">
                    <div class="topRow">
                        <div class="row align-items-center text-center text-md-left">
                            <div class="col-lg-8 ">
                                <h4>@lang('reports.report3'):</h4>
                            </div>
                            <div class="col-lg-4 text-md-right mt-lg-0 mt-3">
                                <div id="dt-btns2" class="tableAdminOption"></div>
                            </div>
                        </div>
                    </div>

                    <table id="" class="table table-bordred table-striped data-table2">
                        <thead>
                        <tr style="text-align: center;">
                            <th>استشاري المبيعات</th>
                            <th>اطلب عملاء</th>
                            <th>اطلب عملاء<br>سلة التحويل</th>
                            <th>مدير النظام</th>
                            <th>Admin<br>سلة التحويل</th>
                            <th>سلة الأرشيف</th>
                            <th>استشاري مؤرشف</th>
                            <th>الطلبات المعلقة</th>
{{--                            <th>غير محدد</th>--}}
                        </tr>
                        </thead>
                        <tbody style="text-align: center;">
                        @foreach($data_for_chart as $data)
                            <tr>
                                <td>{{$data['name']}}</td>
                                <td>{{$data['moved_AskReq']}}</td>
                                <td>{{$data['moved_transfer_basket']}}</td>
                                <td>{{$data['moved_Admin']}}</td>
                                <td>{{$data['moved_NeedActionTable']}}</td>
                                <td>{{$data['moved_ArchiveBacket']}}</td>
                                <td>{{$data['moved_ArchiveAgent']}}</td>
                                <td>{{$data['moved_Pending']}}</td>
{{--                                <td>{{$data['moved_Undefined']}}</td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot align="center">
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ url('interface_style/search/vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ url('interface_style/search/vendor/datepicker/daterangepicker.css') }}" rel="stylesheet" media="all">
    <link href="{{ url('assest/datatable/style.css') }}" rel="stylesheet" media="all">
    <style>
        svg:not(:root) {
            overflow: hidden;
            direction: ltr;
        }
    </style>
@endpush

@section('scripts')
    <script>

        /*
        var dt = $('.data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    pageLength: "عرض",

                }
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            scrollX: true,
            scrollY: true,
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pageLength'
            ],
            initComplete: function() {
                $(".paginate_button").addClass("pagination-circle");
                /!* To Adaptive with New Design *!/
                $('#example-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                })

                dt.buttons().container().appendTo( '#dt-btns');
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title','تصدير') ;
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title','عرض') ;

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)
            },

        });
        */

        var dt2 = $('.data-table2').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    pageLength: "عرض",

                }
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            scrollX: true,
            scrollY: true,
            order: [],
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pageLength'
            ],
            initComplete: function () {
                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function () {
                    dt2.search($(this).val()).draw();
                })

                dt2.buttons().container().appendTo('#dt-btns2');
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)
            },
            footerCallback: function (row, data, start, end, display) {
                var api = this.api(), data;
                // converting to interger to find total
                var intVal = function (i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                            i : 0;
                };


                var moved_AskReq = api
                    .column(1)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var moved_Admin = api
                    .column(2)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var moved_NeedActionTable = api
                    .column(3)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var moved_ArchiveBacket = api
                    .column(4)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var moved_ArchiveAgent = api
                    .column(5)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var moved_Pending = api
                    .column(6)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var moved_Undefined = api
                    .column(7)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);


                // Update footer by showing the total with the reference of the column index
                $(api.column(0).footer()).html('المجموع');

                $(api.column(1).footer()).html(moved_AskReq);
                $(api.column(2).footer()).html(moved_Admin);
                $(api.column(3).footer()).html(moved_NeedActionTable);
                $(api.column(4).footer()).html(moved_ArchiveBacket);
                $(api.column(5).footer()).html(moved_ArchiveAgent);
                $(api.column(6).footer()).html(moved_Pending);
                $(api.column(7).footer()).html(moved_Undefined);
            },
        });
    </script>

    <!-- Jquery JS-->
    <script src="{{ url('interface_style/search/vendor/jquery/jquery.min.js') }}"></script>
    <!-- Vendor JS-->
    <script src="{{ url('interface_style/search/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/jquery-validate/jquery.validate.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/bootstrap-wizard/bootstrap.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/datepicker/moment.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/datepicker/daterangepicker.js') }}"></script>

    <!-- Main JS-->
    <script src="{{ url('interface_style/search/js/global.js') }}"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        var adviser_ids = ("{{implode(',',$adviser_ids)}}").split(',');

        //console.log(adviser_ids);
        $('#status_user').change(function () {
        reFullAdviser_id()
    })
        function reFullAdviser_id() {
            $this = $('#manager_id');
            $.get(
                '{{route('requestChartRApi')}}', {
                    managerId: $this.val(),
                    status_user:$('#status_user').val()
                },
                function (response) {
                    $data = '<option value="0">الكل</option>';
                    response.users.forEach(($user, $index) => {
                        $data += '<option value="' + $user.id + '"' +
                            (adviser_ids.includes('' + $user.id) ? 'selected' : '') +
                            '>' + $user.name + '</option>';

                    });
                    $('#adviser_id').html($data);
                });
        }

        reFullAdviser_id();
        $('#manager_id').on('change', function () {

            reFullAdviser_id();
        });

    </script>

@endsection
