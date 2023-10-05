@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'all_real_estate') }}
@endsection
@section('css_style')

    <link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">

    <style>
        .middle-screen {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        table {
            width: 100%;
            text-align: center;
        }

        td {
            width: 15%;
        }

        .reqNum {
            width: 0.5%;
        }

        .reqDate {
            text-align: center;
        }

        .loadingButton {
            background-color: #0088cc;
            color: azure;
            cursor: not-allowed;
        }

        .reqType {
            width: 2%;
        }

        tr:hover td {
            background: #d1e0e0
        }

        .newReq {
            background: rgba(98, 255, 0, 0.4) ! important;
        }

        .needFollow {
            background: rgba(12, 211, 255, 0.3) ! important;
        }

        .noNeed {
            background: rgba(0, 0, 0, 0.2) ! important;
        }

        .wating {
            background: rgba(255, 255, 0, 0.2) ! important;
        }

        .watingReal {
            background: rgba(0, 255, 42, 0.2) ! important;
        }

        .rejected {
            background: rgba(255, 12, 0, 0.2) ! important;
        }
    </style>

    {{-- NEW STYLE   --}}
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">

@endsection

@section('customer')

    @if(session()->has('msg'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('msg') }}
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

    <div class="addUser my-4 {{auth()->user()->role == '7' ? 'hidden' : ''}}">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>  {{ MyHelpers::admin_trans(auth()->user()->id,'properties') }} :</h3>
            <div class="addBtn">
                <a href="{{route('property.create')}}">
                    <button class="au-btn au-btn-icon au-btn--blue au-btn--small">
                        <i class="zmdi zmdi-plus"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Add Property') }} </button></a>
            </div>
        </div>
    </div>
    @if ($properties->count() > 0)
        <div class="tableBar">
            <div class="topRow">
                <div class="row align-items-center text-center text-md-left">
                    <div class="col-lg-2">
                        <div class="selectAll">
                            <div class="form-check">
                                <input type="checkbox" id="allreq" class="form-check-input" onclick="chbx_toggle1(this);" />
                                <label class="form-check-label" for="allreq">تحديد الكل </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 ">
                        <div class="tableUserOption  flex-wrap ">
                            <div class="addBtn col-md-5 mt-lg-0 mt-3">
                                <button  id="" onclick="BulkDelete()">
                                    <i class="fas fa-trash-alt"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Archive Property') }}
                                </button>
                            </div>
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
                    <div class="col-lg-2 mt-lg-0 mt-3">
                        <div  id="dt-btns" class="tableAdminOption">

                        </div>
                    </div>
                </div>
            </div>
            <div class="dashTable">
                <table id="myusers-table" class="table table-bordred table-striped data-table">
                    <thead>
                    <tr>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property num') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property source') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property price') }}</th>
                        <th>المنطقة</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property type') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property zone') }}</th>
                        <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>

                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        </div>
    @else
        <div class="middle-screen">
            <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Properties') }}</h2>
        </div>
        @endif
        </div>

@endsection
@section('updateModel')
    @include('Proper.Property.confirmDelMsg')
    @include('Proper.Property.confirmArchMsg')
@endsection
@section('scripts')
    <script src="{{ asset('js/tokenize2.min.js') }}"></script>

    <script>
        function getReqests1() {
            var array = []
            var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                    var val = parseInt(checkboxes[i].value);
                    array.push(val);
                }
            }
            //console.log(array);
            archiveAllReqs(array);
            //  alert(array);
        }

        function archiveAllReqs(array) {
            var modalConfirm = function(callback) {
                $("#mi-modal3").modal('show');
                $("#modal-btn-si3").on("click", function() {
                    callback(true);
                    $("#mi-modal3").modal('hide');
                });
                $("#modal-btn-no3").on("click", function() {
                    callback(false);
                    $("#mi-modal3").modal('hide');
                });
            };
            modalConfirm(function(confirm) {
                if (confirm) {
                    $.post("{{ route('property.archPropertyArray')}}", {
                        array: array,
                        _token: "{{csrf_token()}}",
                    }, function(data) {
                        var url = '{{ route("property.archPropertyArray") }}';
                        if (data != 0) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')}}");
                            window.location.href = url; //using a named route
                        } else
                            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Try Again')}}");
                    });
                } else {
                    //reject
                }
            });
        };
        function disabledButton() {

            if ($(':checkbox[name="chbx[]"]:checked').length > 0) {
                document.getElementById("archAll").disabled = false;
                document.getElementById("archAll").style = "";
            } else {
                document.getElementById("archAll").disabled = true;
                document.getElementById("archAll").style = "cursor: not-allowed";
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
    <script>
        $(document).ready(function() {
            var table = $('.data-table').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}",
                    buttons: {
                        excelHtml5: "اكسل",
                        print: "طباعة",
                        pageLength: "عرض",
                    }
                },
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 1
                },
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'print',
                    'pageLength'
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ url('property/list') }}",
                columns: [
                    {
                        "targets": 0,
                        "data": "id",
                    },
                    { data: 'source', name: 'source' },
                    { data: 'fixed_price', name: 'fixed_price' },
                    { data: 'areaName', name: 'areaName' },
                    { data: 'type', name: 'type' },
                    { data: 'zone', name: 'zone' },
                    { data: 'action', name: 'action' },

                ],
                "order": [
                    [1, "desc"]
                ] // Order on init. # is the column, starting at 0
                ,
                createdRow: function(row, data, index) {


                    $('td', row).eq(3).addClass('commentStyle');
                    $('td', row).eq(3).attr('title', data.email);


                    $('td', row).eq(1).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(2).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(4).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(5).addClass('reqNum'); // 6 is index of column
                },
                "initComplete": function(settings, json) {
                    $(".paginate_button").addClass("pagination-circle");
                    /* To Adaptive with New Design */
                    $('#example-search-input').keyup(function(){
                        table.search($(this).val()).draw() ;
                    })

                    table.buttons().container()
                        .appendTo( '#dt-btns' );

                    $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr( 'title', 'تصدير' );
                    $('.buttons-print').html('<i class="fas fa-print"></i>').attr( 'title', 'طباعة' ) ;
                    $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr( 'title', 'عرض' );

                    $('.buttons-excel').addClass('no-transition custom-btn');
                    $('.buttons-print').addClass('no-transition custom-btn');
                    $('.buttons-collection').addClass('no-transition custom-btn');


                    $('.tableAdminOption span').tooltip(top)
                    $('button.dt-button').tooltip(top)

                    /* To Adaptive with New Design */
                }

            });

        });
    </script>
    <script>
        $(document).on("click", ".deleteBtn", function () {
            $("#Confirm").modal("show");
            $("#Confirm .deleteForm").attr("action", $(this).attr("href"));
            return false;
        })
        $('#Confirm').on('show.bs.modal', function (event) {
            // console.log($('.deleteBtn').attr("href"));
            var btn_trans = "{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}" ;
            var loader = $('.btn-send');
            loader.html(btn_trans);
        });
        function deleteData(id) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'هل انت متأكد',
                text: "لن تكون قادر على التراجع فى هذا الأمر ؟",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                buttons: ["إلغاء","نعم , احذف !"],
            }).then(function(inputValue) {
                if (inputValue != null) {
                    $.ajax({
                        url: "{{ url('property/destroy/') }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': csrf_token
                        },
                        success: function(data) {
                            $('.data-table').DataTable().ajax.reload();
                            swal({
                                title: 'تم!',
                                text: 'تم الحذف بنجاح ',
                                type: 'success',
                                timer: '750'
                            })
                        },
                        error: function() {
                            swal({
                                title: 'خطأ',
                                text: data.message,
                                type: 'error',
                                timer: '750'
                            })
                        }
                    });
                } else {

                }

            });
        }
        function  ShowData(id) {

            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'هل انت متأكد من إظهار العقار',
                text: "سوف يتمكن العملاء أو الزائرين من رؤية العقار ؟",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                buttons: ["إلغاء","نعم , إظهار !"],
            }).then(function(inputValue) {
                if (inputValue != null) {
                    $.ajax({
                        url: "{{ route('property.status') }}",
                        type: "POST",
                        data: {
                            '_method': 'POST',
                            '_token': csrf_token,
                            'status':true , 'id': id
                        },
                        success: function(data) {
                            $('.data-table').DataTable().ajax.reload();
                            swal({
                                title: 'تم!',
                                text: 'تم تفعيل ظهور العقار',
                                type: 'success',
                                timer: '750'
                            })
                        },
                        error: function() {
                            swal({
                                title: 'خطأ',
                                text: data.message,
                                type: 'error',
                                timer: '750'
                            })
                        }
                    });
                } else {

                }

            });
        }
        function HideData(id) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'هل انت متأكد من إخفاء العقار',
                text: "لم يتمكن العملاء أو الزائرين من رؤية العقار ؟",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                buttons: ["إلغاء","نعم , إخفاء !"],
            }).then(function(inputValue) {
                if (inputValue != null) {
                    $.ajax({
                        url: "{{ route('property.status') }}",
                        type: "POST",
                        data: {
                            '_method': 'POST',
                            '_token': csrf_token,
                            'status':false , 'id': id
                        },
                        success: function(data) {
                            $('.data-table').DataTable().ajax.reload();
                            swal({
                                title: 'تم!',
                                text: 'تم إخفاء العقار',
                                type: 'success',
                                timer: '750'
                            })
                        },
                        error: function() {
                            swal({
                                title: 'خطأ',
                                text: data.message,
                                type: 'error',
                                timer: '750'
                            })
                        }
                    });
                } else {

                }

            });
        }

        function BulkDelete() {
            var id = [];
            $('.customers_checkbox:checked').each(function () {
                id.push($(this).val());
            });
            if(id.length > 0) {
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                swal({
                    title: 'هل انت متأكد',
                    text: "لن تكون قادر على التراجع فى هذا الأمر ؟",
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonColor: '#3085d6',
                    buttons: ["إلغاء","نعم , احذف !"],
                }).then(function(inputValue) {
                    if(id.length > 0) {
                        if (inputValue != null) {
                            $.ajax({
                                url: "{{ url('property/archpropertyarr/') }}",
                                type: "POST",
                                data: {
                                    '_method': 'DELETE',
                                    '_token': csrf_token,
                                    'ids'   : id
                                },
                                success: function (data) {
                                    $('.data-table').DataTable().ajax.reload();
                                    swal({
                                        title: 'تم!',
                                        text: 'تم حذف العناصر',
                                        type: 'success',
                                        timer: '750'
                                    })
                                },
                                error: function () {
                                    swal({
                                        title: 'خطأ',
                                        text: data.message,
                                        type: 'error',
                                        timer: '750'
                                    })
                                }
                            });
                        } else {

                        }
                    }else{
                        swal({
                            title: 'خطأ...',
                            text: 'لم يتم تحديد أى عنصر',
                            type: 'error',
                            timer: '750'
                        })
                    }

                });
            }

        }
    </script>
@endsection
