@extends('layouts.content')
@php
    $classifications=\App\Models\Classification::whereIn('id',[33,62])->get();
@endphp
@section('title', trans_choice('choice.FreezeRequests', 2))
@section('css_style')
<style>
    .select2-request + .select2-container .select2-selection--single{
        width: 300px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        top:8px !important;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-top: 5px;
    }
</style>
@endsection
@section('customer')

    <div class="my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>{{trans_choice('choice.FreezeRequests', 2)}}:</h3>

            <div class="row">
                <div class="col-lg-12">
                    <select id='classification_before_to_freeze' class="form-control select2-request">
                        <option value="">تصنيف الطلب قبل التجميد</option>
                        <option value="">الكل</option>
                        @foreach($classifications as $classification)
                            <option value="{{$classification['id']}}">{{ $classification['value'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="tableBar">
        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-lg-8 ">
                    <div class="tableUserOption  flex-wrap ">
                        <div class="input-group col-md-7 mt-lg-0 mt-3">
                            <input class="form-control py-2" type="search" placeholder="ابحث هنا"
                                   id="example-search-input">
                            <span class="input-group-append">
                              <button class="btn btn-outline-info" type="button">
                                  <i class="fa fa-search"></i>
                              </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-md-right mt-lg-0 mt-3">
                    <div id="dt-btns" class="tableAdminOption">
                        {{--  Here We Will Add Buttons of Datatable  --}}
                    </div>
                </div>
            </div>
            <div class="row align-items-center text-center mt-5">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="selectAll">
                                <div class="form-check">
                                    <input type="checkbox" id="allreq" class="form-check-input" onclick="chbx_toggle1(this);" />
                                    <label class="form-check-label" for="allreq">تحديد الكل </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="tableUserOption   flex-wrap justify-content-md-end">
                                <div class="addBtn  mt-lg-0 mt-3 orderBtns spansBtn">
{{--                                    <button class="mr-2 Red" style="cursor: not-allowed" disabled id="archAll" onclick="getReqests1()">
                                        <i class="fas fa-trash-alt"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Archive Request') }}
                                    </button>
                                    <button class="mr-2 Cloud" style="cursor: not-allowed" disabled id="moveAll" onclick="getReqests2()">
                                        <i class="fas fa-random"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Move Reqs') }}
                                    </button>
                                    <button class="mr-2 Pink" style="cursor: not-allowed" disabled id="moveNeesActionAll" onclick="getReqests3()">
                                        <i class="fas fa-directions"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Move Reqs To Need Action') }}
                                    </button>
                                    <button class="mr-2 Red2-light" style="cursor: not-allowed" disabled id="moveQualityAll" onclick="getReqests4()">
                                        <i class="fas fa-paper-plane"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Move Reqs To Quality') }}
                                    </button>
                                    --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashTable">
            <table class="table table-bordered table-striped data-table">
                <thead>
                <tr>
                    <th> </th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'before last classification') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

@endsection
@section('updateModel')
    @include('Admin.Request.filterReqs')
@endsection

@section('scripts')
    <script src="{{ asset('js/tokenize2.min.js') }}"></script>

    <script>
        var xses = [
            'sa',
            'sm',
            'fm',
            'mm',
            'gm'
        ];

        function getClassifcationX($x) {
            return $("#classifcation_" + $x).data('tokenize2').toArray();
        }

        function getReqTypes() {
            return $("#request_type").data('tokenize2').toArray();
        }

        $(function () {
            var dt = $('.data-table').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}",
                    buttons: {
                        print: "طباعة",
                        // reload: "تحديث",
                        pageLength: "عرض",
                    }
                },
                "lengthMenu": [
                    [50, 100, 200],
                    [50, 100, 200]
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'print',
                    'pageLength',
                    {
                        text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                        action: (e, dt, node, config) => $('#myModal').modal('show')
                    },
                ],
                // scrollY: '50vh',
                // scrollX: '50vh',
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "{{ route('V2.Admin.FreezeRequest.datatable') }}",
                    'method': 'get',
                    'data': function (data) {
                        let agents_ids = $('#agents_ids').data('tokenize2').toArray();
                        let reqTypes = $("#request_type").data('tokenize2').toArray();
                        let customer_salary = $('#customer-salary').val();
                        let customer_phone = $('#customer-phone').val();
                        let customer_birth = $('#customer-birth').val();
                        let req_date_from = $('#request-date-from').val();
                        let req_date_to = $('#request-date-to').val();
                        let complete_date_from = $('#complete-date-from').val();
                        let complete_date_to = $('#complete-date-to').val();
                        let req_status = ($("#request_status").data('tokenize2').toArray());
                        let pay_status = ($("#pay_status").data('tokenize2').toArray());
                        let source = $('#source').data('tokenize2').toArray();
                        let collaborator = $('#collaborator').data('tokenize2').toArray();
                        let work_source = $('#work_source').data('tokenize2').toArray();
                        let salary_source = $('#salary_source').data('tokenize2').toArray();
                        let founding_sources = $('#founding_sources').data('tokenize2').toArray();
                        let notes_status = $('#notes_status').data('tokenize2').toArray();
                        let app_downloaded = $('#app_downloaded').data('tokenize2').toArray();
                        let quality_recived = $('#quality_recived').data('tokenize2').toArray();
                        data.updated_at_from = $('#updated_at_from').val();
                        data.updated_at_to = $('#updated_at_to').val();
                        data.classification_before_to_freeze=$('#classification_before_to_freeze').val()



                        if (req_date_from != '') {
                            data['req_date_from'] = req_date_from;
                        }
                        if (req_date_to != '') {
                            data['req_date_to'] = req_date_to;
                        }

                        if (complete_date_from != '') {
                            data['complete_date_from'] = complete_date_from;
                        }
                        if (complete_date_to != '') {
                            data['complete_date_to'] = complete_date_to;
                        }

                        if (customer_birth != '') data['customer_birth'] = customer_birth;

                        if (customer_salary != '') data['customer_salary'] = customer_salary;
                        if (customer_phone != '') data['customer_phone'] = customer_phone;

                        if (source != '') data['source'] = source;
                        if (collaborator != '') data['collaborator'] = collaborator;
                        if (work_source != '') data['work_source'] = work_source;
                        if (salary_source != '') data['salary_source'] = salary_source;
                        if (founding_sources != '') data['founding_sources'] = founding_sources;

                        if (app_downloaded != '') data['app_downloaded'] = app_downloaded;

                        if (agents_ids != '') data['agents_ids'] = agents_ids;

                        if (req_status != '') {

                            var contain = false;

                            contain = req_status.includes("3"); // because wating for sales manager is equal to 5 (archived in sales maanager)
                            if (contain)
                                req_status.push("5", "18"); //status of arachived request in sales manager,wating sales manager req mor-pur req


                            contain = false;
                            contain = req_status.includes("4"); // rejected sales manager req
                            if (contain)
                                req_status.push("20"); //status of rejected sales manager req mor-pur req


                            contain = false;
                            contain = req_status.includes("7"); // rejected funding manager req
                            if (contain)
                                req_status.push("22"); //status of rejected funding manager req mor-pur req


                            contain = false;
                            contain = req_status.includes("6"); // wating funding manager req
                            if (contain)
                                req_status.push("8", "21"); //archive in funding manager req,wating funding manager req mor-pur req


                            contain = false;
                            contain = req_status.includes("9"); // wating mortgage manager req
                            if (contain)
                                req_status.push("11"); //archive in mortgage manager req


                            contain = false;
                            contain = req_status.includes("13"); // rejected general manager req
                            if (contain)
                                req_status.push("25"); //status of rejected general manager req mor-pur req


                            contain = false;
                            contain = req_status.includes("12"); // wating general manager req
                            if (contain)
                                req_status.push("14", "23"); //archive in general manager req,,wating general manager req mor-pur req


                            contain = false;
                            contain = req_status.includes("16"); // completed
                            if (contain)
                                req_status.push("26"); //completed mor-pur req


                            contain = false;
                            contain = req_status.includes("15"); // Canceled
                            if (contain)
                                req_status.push("27"); //Canceled mor-pur req


                            contain = false;
                            contain = req_status.includes("29"); // Rejected and archived
                            if (contain) {
                                data['checkExisted'] = "29";
                                req_status.push("2"); //archived in sales agent
                                req_status.splice(req_status.indexOf('29'), 1);
                            } else
                                data['checkExisted'] = null;


                            data['req_status'] = req_status;

                        }

                        if (pay_status != '') data['pay_status'] = pay_status;

                        if (reqTypes != '') data['reqTypes'] = reqTypes;

                        if (notes_status != '') {

                            var contain = false;
                            var empty = false;
                            contain = notes_status.includes("1"); // returns true
                            empty = notes_status.includes("0"); // returns true

                            if (contain && empty) // choose all optiones
                                notes_status = 0;
                            else if (contain && !empty) // choose contain only
                                notes_status = 1;
                            else if (!contain && empty) // choose empty only
                                notes_status = 2;
                            else
                                notes_status = null;
                            data['notes_status'] = notes_status;
                        }

                        if (quality_recived != '') {

                            var yesValue = false;
                            var noValue = false;

                            yesValue = quality_recived.includes("yes");
                            noValue = quality_recived.includes("no");

                            if (yesValue && noValue) // choose all optiones
                                quality_recived = 0;
                            else if (yesValue && !noValue) // choose contain only
                                quality_recived = 1;
                            else if (!yesValue && noValue) // choose empty only
                                quality_recived = 2;
                            else
                                quality_recived = null;

                            data['quality_recived'] = quality_recived;

                        }

                        xses.forEach(function (item) {
                            if (getClassifcationX(item) != '') {
                                data['class_id_' + item] = getClassifcationX(item)
                            }
                        })
                    },
                },
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 1
                },
                columns: [
                    {
                        targets: 0,
                        data: "id",
                        render: (data, type, row, meta) =>'<input type="checkbox" id="chbx" name="chbx[]" onchange="disabledButton()" value="' + data + '"/>'
                    },
                    {
                        data: "id",
                        name: "id",
                    },{
                        data: "classification_before_to_freeze",
                        name: "classification_before_to_freeze",
                    },
                    {
                        data: 'req_date',
                        name: 'req_date'
                    },
                    {
                        data: 'source',
                        name: 'source'
                    },
                    {
                        data: 'customer_id',
                        name: 'customer.name'
                    },
                    {
                        data: 'customer.mobile',
                        name: 'customer.mobile'
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

                    $('#classification_before_to_freeze').change(function(){
                        dt.draw();
                    });

                    $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
                    $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                    $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                    $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');
                    $('.buttons-excel').addClass('no-transition custom-btn');
                    $('.buttons-print').addClass('no-transition custom-btn');
                    $('.buttons-collection').addClass('no-transition custom-btn');
                    $('.tableAdminOption span').tooltip(top)
                    $('button.dt-button').tooltip(top)
                },
                "order": [[1, "desc"]],
                createdRow: function (row, data, index) {
                    // $('td', row).eq(8).addClass('commentStyle'); // 6 is index of column
                    // $('td', row).eq(8).attr('title', data.comment); // to show other text of comment
                    // $('td', row).eq(3).addClass('commentStyle'); // 6 is index of column
                    // $('td', row).eq(3).attr('title', data.cust_name); // to show other text of comment
                    // $('td', row).eq(0).addClass('reqDate'); // 6 is index of column
                    // $('td', row).eq(10).addClass('reqDate'); // 6 is index of column
                    // $('td', row).eq(1).addClass('reqNum'); // 6 is index of column
                    // $('td', row).eq(2).addClass('reqType'); // 6 is index of column
                    // $('td', row).eq(6).addClass('reqType'); // 6 is index of column
                },
            });
            $('#source').on('tokenize:tokens:add', function (e, value, text) {
                if (value == 2) {
                    document.getElementById("collaboratorDiv").style.display = "block";
                }
            });
            $('#source').on('tokenize:tokens:remove', function (e, value) {
                if (value == 2) {
                    document.getElementById("collaboratorDiv").style.display = "none";
                    document.getElementById("collaborator").value = "";
                }
            });

            $('#request_type').on('tokenize:tokens:add', function (e, value, text) {
                if (value == "شراء-دفعة") {
                    document.getElementById("paystatusDiv").style.display = "block";
                }
            });
            $('#request_type').on('tokenize:tokens:remove', function (e, value) {
                if (value == "شراء-دفعة") {
                    document.getElementById("paystatusDiv").style.display = "none";
                    document.getElementById("pay_status").value = "";
                }
            });

            $('.tokenizeable').tokenize2();
            $(".tokenizeable").on("tokenize:select", function () {
                $(this).trigger('tokenize:search', "");
            });
        });

        function disabledButton() {

            if ($(':checkbox[name="chbx[]"]:checked').length > 0) {
                document.getElementById("archAll").disabled = false;
                document.getElementById("archAll").style = "";

                document.getElementById("moveAll").disabled = false;
                document.getElementById("moveAll").style = "";

                document.getElementById("moveNeesActionAll").disabled = false;
                document.getElementById("moveNeesActionAll").style = "";

                document.getElementById("moveQualityAll").disabled = false;
                document.getElementById("moveQualityAll").style = "";
            } else {
                document.getElementById("archAll").disabled = true;
                document.getElementById("archAll").style = "cursor: not-allowed";

                document.getElementById("moveAll").disabled = true;
                document.getElementById("moveAll").style = "cursor: not-allowed";

                document.getElementById("moveNeesActionAll").disabled = true;
                document.getElementById("moveNeesActionAll").style = "cursor: not-allowed";

                document.getElementById("moveQualityAll").disabled = true;
                document.getElementById("moveQualityAll").style = "cursor: not-allowed";
            }

        }
        function chbx_toggle1(source) {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] != source)
                    checkboxes[i].checked = source.checked;
            }

            disabledButton();
        }
    </script>
@endsection
