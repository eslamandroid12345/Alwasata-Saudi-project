@extends('layouts.content')

@section('title', __('global.unableToConnectStatistics'))

@section('customer')
    <div class="container container-fluid mt-4">
        <div class="row">
            <div class="col-12 topRow">
                <h4>@lang('global.unableToConnectStatistics')</h4>
                <form>
                    <div class="row">
                        <div class="col-6">
                            <label class="label">@lang('attributes.date_from')</label>
                            <input class="form-control" type="date" name="date_from" value="{{ request('date_from',old('date_from')) }}">
                        </div>
                        <div class="col-6">
                            <label class="label">@lang('attributes.date_to')</label>
                            <input class="form-control" type="date" name="date_to" value="{{ request('date_to',old('date_from')) }}">
                        </div>
                        <div class="col-12">
                            <label class="label">@lang('attributes.manager_id')</label>
                            <div class="rs-select2 js-select-simple select--no-search">
                                <select class="form-control" multiple name="manager_id[]">
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->id }}" {{in_array($manager->id,request('manager_id',[])) ? "selected" :"" }}>{{ $manager->name }}</option>
                                    @endforeach
                                </select>
                                <div class="select-dropdown"></div>
                            </div>

                        </div>
                        <div class="col-12">
                            <label class="label">@lang('attributes.agent_id')</label>
                            <div class="rs-select2 js-select-simple select--no-search">
                                <select class="form-control" multiple name="agent_id[]">
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{in_array($agent->id,request('agent_id',[])) ? "selected" :"" }}>{{ $agent->name }}</option>
                                    @endforeach
                                </select>
                                <div class="select-dropdown"></div>
                            </div>

                        </div>
                        <div class="col-12 mt-3 d-flex justify-content-between align-items-center">
                            <div class="addBtn">
                                <button class="text-center mr-3 green item" type="submit">
                                    <i class="fas fa-search"></i>
                                    @lang('global.search')
                                </button>
                            </div>
                            <div id="dt-buttons" class="tableAdminOption"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 dashTable p-2">
                <table class="table table-sm table-striped data-table">
                    <thead>
                    <tr>
                        <th class="first">@lang('attributes.No')</th>
                        <th class="name">@lang('attributes.agent_id')</th>
                        <th>@lang('attributes.change_classification')</th>
                        <th>@lang('attributes.app_message')</th>
                        <th>@lang('global.fromRegister')</th>
                        <th>@lang('global.unableToConnect')</th>
                        <th>@lang('global.pendingResponse')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $row)
                        <tr>
                            <td class="first">{{$row['id']}}</td>
                            <td class="name">{{$row['name']}}</td>
                            <td>{{$row['changesCount']}}</td>
                            <td>{{$row['messagesCount']}}</td>
                            <td>{{$row['registerCount']}}</td>
                            <td>{{$row['unableToConnectCount']}}</td>
                            <td>{{$row['pendingCount']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>{{$rows->count()}}</td>
                        <td>@lang('attributes.total')</td>
                        <td>{{$allChangesCount}}</td>
                        <td>{{$allMessagesCount}}</td>
                        <td>{{$allRegisterCount}}</td>
                        <td>{{$allUnableToConnectCount}}</td>
                        <td>{{$allPendingCount}}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .dashTable {
            overflow-x: hidden;
        }

        .table-report {
            margin-bottom: 0;
        }

        .table-responsive {
            max-height: 500px;
            overflow: auto;
        }

        /*.table-report .total{*/
        /*    width: 45% !important;*/
        /*}*/
        .table-report .first {
            width: 5% !important;
        }

        .table-report .name {
            width: 40%
        }

        .table-report :not(.name):not(.first) {
            width: 20%
        }
    </style>
@endpush

@push('scripts')

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
        var dt = $('.data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    print: "{{__("global.print")}}",
                    // pageLength: "عرض",
                }
            },
            paging: !1,
            scrollY: '50vh',
            scrollX: !0,
            dom: 't',
            buttons: [
                'excelHtml5',
                'print',
                // 'pageLength'
            ],
            order: [],
            initComplete: function () {
                // $(".paginate_button").addClass("pagination-circle");
                // $('#example-search-input').keyup(function(){
                //     dt.search($(this).val()).draw() ;
                // })
                dt.buttons().container().appendTo('#dt-buttons');
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                // $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');
                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)
            },

        });
    </script>
@endpush
