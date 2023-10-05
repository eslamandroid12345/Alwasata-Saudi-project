@extends('layouts.content')

@section('title', __('reports.report2'))

@section('customer')
    <div class="container container-fluid mt-4">
        <div class="row">
            <div class="col-12 topRow">
                <h4>@lang('reports.report2')</h4>
                <form>
                    <div class="row">
                        <div class="col-4">
                            <label class="label">@lang('attributes.date_from')</label>
                            <input class="form-control" type="date" name="date_from" value="{{ request('date_from',old('date_from',now()->subDays(7)->format('Y-m-d'))) }}">
                        </div>
                        <div class="col-4">
                            <label class="label">@lang('attributes.date_to')</label>
                            <input class="form-control" type="date" name="date_to" value="{{ request('date_to',old('date_from',now()->format('Y-m-d'))) }}">
                        </div>
                        <div class="col-4">
                            <label class="label"> الحالة  </label>
                <select class="form-control" name="status_user" id="status_user" style="height: 38px">
                                <option value="2" {{request('status_user') == 2 ? 'selected' : ''}}>الكل</option>
                    <option value="0" {{request('status_user') == 0 ? 'selected' : ''}}>إستشاري مؤرشف</option>
                    <option value="1" {{request('status_user') == 1 ? 'selected' : ''}}>إستشاري نشط</option>
                            </select>
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
                                <select class="form-control" multiple name="agent_id[]" id="adviser_id">
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{in_array($agent->id,request('agent_id',[])) ? "selected" :"" }}>{{ $agent->name }}</option>
                                    @endforeach
                                </select>
                                <div class="select-dropdown"></div>
                            </div>

                        </div>
                        <div class="col-12 mt-3 d-flex justify-content-between align-items-center">
                            <div class="addBtn">
                                <button class="text-center mr-3 green item" type="submit" name="submit">
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
{{--                        <th class="first">@lang('attributes.No')</th>--}}
                        <th class="name">@lang('attributes.agent_id')</th>
                        <th>@lang('attributes.frozen')</th>
                        <th>@lang('attributes.notFrozen')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $row)
                        <tr>
{{--                            <td class="first">{{$row['id']}}</td>--}}
                            <td class="name">{{$row['name']}}</td>
                            <td>{{$row['frozen']}}</td>
                            <td>
                                {{$row['notFrozen']}}
                                @if ($row['notFrozen'] > 0)
                                <button class="btn btn-primary load-reqs" style="border-radius: 50%; padding: 1px 5px; background: #0f5b94;"  data-toggle="modal" data-target=".bd-example-modal-lg" data-ids="{{implode(',', $row['notFrozen_rows'])}}">
                                    <i class="fas fa-eye"></i>
                                </button>

                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
{{--                        <td>{{$rows->count()}}</td>--}}
                        <td>@lang('attributes.total')</td>
                        <td>{{$allFrozenCount}}</td>
                        <td>{{$allNotFrozenCount}}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 80%;">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">عرض الطلبات</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body dashTable " id="load-ajax">

          </div>

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
            width: 50px !important;
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
    <script>
        $(document).on('click','.load-reqs', function(){
            $('#load-ajax').html('');
            $.get('{{route("V2.Admin.requests_table")}}?ids='+$(this).data('ids'))
            .done(function(res){
                $('#load-ajax').html(res['view']);
            })
            .fail(function(res){});
        });
    </script>
@endpush
