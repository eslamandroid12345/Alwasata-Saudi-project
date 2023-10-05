@php
    $archived = $archived ?? false
@endphp

@extends('layouts.content')

@section('title', ' مرسلة إلى الوساطة')

@section('customer')
    <div class="mt-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>مرسلة إلى الوساطة </h3>
            {{-- <h3>@lang('global.archived_customers')</h3> --}}
        </div>
    </div>
    <br>
    <div class="tableBar">
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
                    <div id="dt-btns" class="tableAdminOption"></div>
                </div>
            </div>
            <div class="row align-items-center text-center mt-3">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-2">
                            <div>
                                <div class="form-check">
                                    <label class="form-check-label" for="select-all">
                                        <input type="checkbox" id="select-all" class="form-check-input" onclick="toggleAll(this);"/>&nbsp;@lang('attributes.select_all')</label>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-10">
                            <div class="tableUserOption flex-wrap justify-content-md-end">
                                <div class="">
                                    @if(auth()->user()->subdomain)
                                    <button class="w-btn rounded" id="copy-my-url">
                                        <i class="fas fa-copy"></i>&nbsp;@lang('global.copy_my_url')
                                    </button>
                                    @endif

                                    @if(!$archived)
                                        <button class="w-btn rounded bg-danger" disabled id="archiveAll">
                                            <i class="fas fa-trash-alt"></i>&nbsp;@lang('global.archive')
                                        </button>
                                    @else
                                        <button class="w-btn rounded bg-success" disabled id="restoreAll">
                                            <i class="fas fa-redo-alt"></i>&nbsp;@lang('global.restore')
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="dashTable">
            <table class="table table-bordere d table-striped data-table">
                <thead>
                <tr>
                    <th></th>
                    {{-- <th>@lang('attributes.name')</th> --}}
                    <th>تاريخ الطلب</th>
                    <th>نوع الطلب</th>
                    <th>العميل</th>
                    <th>حالة الطلب</th>
                    <th>مصدر الطلب</th>
                    <th>الملاحظة</th>

                    <th style="text-align:left">@lang('attributes.actions')</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    @include('V2.WasataRequestes.filter_modal')
@endsection

@section('scripts')
    <script src="{{ asset('js/tokenize2.min.js') }}"></script>

    <script>
        let dt;

        $(function () {
            dt = $('.data-table').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}",
                    buttons: {
                        print: "@lang('datatable.print')",
                        reload: "@lang('datatable.reload')",
                        pageLength: "@lang('datatable.pageLength')",
                    }
                },
                "lengthMenu": [
                    [50, 100, 200, 500, -1],
                    [50, 100, 200, 500, "@lang('global.all')"],
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'print',
                    'pageLength',
                    {{--{--}}
                    {{--    text: '@lang("language.Search")',--}}
                    {{--    action: (e, dt, node, config) => $('#myModal').modal('show')--}}
                    {{--},--}}
                ],
                // scrollY: '50vh',
                // scrollX: '50vh',
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "{{ route('V2.ExternalCustomer.requestes-of-wasata-indexDatatable') }}",
                    'method': 'get',
                    'data': function (data) {

                    },
                },
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                // fixedColumns: {
                //     leftColumns: 0,
                //     rightColumns: 1
                // },
                columns: [
                    {
                        targets: 0,
                        data: "id",
                        render: (id, type, row, meta) => `<input type="checkbox" id="checkbox-${id}" name="checkbox[]" onchange="disabledButton()" value="${id}"/>`,
                        orderable: !1,
                        searchable: !1,
                        sortable: !1,
                    },
                    {
                        data: 'req_date',
                        name: 'req_date'
                    },
                    {
                        data: 'req_type',
                        name: 'req_type'
                    },
                    {
                        data: 'external_customer_id',
                        name: 'external_customer_id'
                    },
                    {
                        data: 'req_status',
                        name: 'req_status'
                    },
                    {
                        data: 'finance_supervisor_id',
                        name: 'finance_supervisor_id'
                    },
                    {
                        data: 'notes',
                        name: 'notes'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: !1,
                        searchable: !1,
                        sortable: !1,
                    },
                ],
                initComplete: function () {
                    let api = this.api();
                    $("#filter-search-req").on('click', function (e) {
                        e.preventDefault();
                        api.draw();
                        $('#myModal').modal('hide');
                    });
                    $(".paginate_button").addClass("pagination-circle");
                    /* To Adaptive with New Design */
                    $('#example-search-input').keyup(_.debounce(function () {
                        dt.search($(this).val()).draw();
                    }, 500))
                    dt.buttons().container().appendTo('#dt-btns');

                    $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', "@lang('datatable.search')");
                    $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', '@lang('datatable.export')');
                    $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', '@lang('datatable.print')');
                    $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', '@lang('datatable.pageLength')');
                    $('.buttons-excel,.buttons-print,.buttons-collection').addClass('no-transition custom-btn');
                    $('.tableAdminOption span,button.dt-button').tooltip(top)
                },
                "order": [],
                createdRow: function (row, data, index) {
                    // $('td', row).eq(8).addClass('commentStyle'); // 6 is index of column
                },
            });
        });

        const getSelectedItems = () => $(':checkbox[name="checkbox[]"]:checked')

        function disabledButton() {
            const archiveAll = $("#archiveAll")
            const restoreAll = $("#restoreAll")
            const checkboxes = getSelectedItems()
            archiveAll.prop('disabled', !(checkboxes.length > 0))
            restoreAll.prop('disabled', !(checkboxes.length > 0))
        }

        function toggleAll(source) {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');

            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] !== source)
                    checkboxes[i].checked = source.checked;
            }

            disabledButton();
        }

    </script>
@endsection

@push('styles')
    <style>
        .data-table th:first-child,
        .data-table td:first-child {
            width: 5% !important;
        }
    </style>
@endpush
