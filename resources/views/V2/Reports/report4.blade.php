@extends('layouts.content')

@section('title', __('reports.report4'))

@section('customer')
    {{-- For Search Parameters   --}}
    @include('Charts.movedRequestWithPostiveChart-parameters',['showReport4' => !0])
    <div class="main-content">
        <div class="section_content section_content--p30">
            <div class="container-fluid">
                <div class="dashTable" style="padding: 20px">
                    <div class="topRow">
                        <div class="row align-items-center text-center text-md-left">
                            <div class="col-lg-8 ">
                                <h4>@lang('reports.report4'):</h4>
                            </div>
                            <div class="col-lg-4 text-md-right mt-lg-0 mt-3">
                                <div id="dt-btns2" class="tableAdminOption"></div>
                            </div>
                        </div>
                    </div>

                    <table id="" class="table table-bordred table-striped data-table-list">
                        <thead>
                        <tr style="text-align: center;">
                            <th>@lang('attributes.agent_id')</th>
                            <th>
                                الاستشاري
                                <br>
                                ايجابي
                            </th>
                            <th>
                                الجودة
                                <br>
                                ايجابي
                            </th>
                            <th>
                                الجودة
                                <br>
                                سلبي
                            </th>
                            <th>
                                الاستشاري
                                <br>
                                سلبي
                            </th>
                            <th>
                                الجودة
                                <br>
                                ايجابي
                            </th>
                            <th>
                                الجودة
                                <br>
                                سلبي
                            </th>
                        </tr>
                        </thead>
                        <tbody style="text-align: center;">
                        @foreach($userData as $data)
                            <tr>
                                <td>{{$data['name']}}</td>
                                <td>{{$data['positiveCount']}}</td>
                                <td>{{$data['positive_positiveCount']}}</td>
                                <td>{{$data['negative_positiveCount']}}</td>
                                <td>{{$data['negativeCount']}}</td>
                                <td>{{$data['positive_negativeCount']}}</td>
                                <td>{{$data['negative_negativeCount']}}</td>
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
        var datatableList = $('.data-table-list').DataTable({
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
                    datatableList.search($(this).val()).draw();
                })

                datatableList.buttons().container().appendTo('#dt-btns2');
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)
            },
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();
                // Update footer by showing the total with the reference of the column index
                for (let i = 1; i < api.columns()[0].length; ++i) {
                    $(api.column(i).footer()).html(api.column(i).data().reduce((a, b) => parseInt(a) + parseInt(b), 0));
                }
                $(api.column(0).footer()).html('المجموع');
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
