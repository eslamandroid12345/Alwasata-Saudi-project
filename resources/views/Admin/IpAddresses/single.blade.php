@extends('layouts.content')
@php
    $id = $id ?? "";
    $pageTitle = "طلبات " . $id;
@endphp

@section('title',$pageTitle )

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

        .modal-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: -2 !important;
            background-color: #000;
        }

        .tooltips {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black;
        }

        .tooltips .tooltipstext {
            visibility: hidden;
            width: 120px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            position: absolute;
            z-index: 1;
            bottom: 150%;
            left: 50%;
            margin-left: -60px;
        }

        .tooltips .tooltipstext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: black transparent transparent transparent;
        }

        .tooltips:hover .tooltipstext {
            visibility: visible;
        }
    </style>
    {{-- NEW STYLE   --}}
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection
@section('customer')
    <div>
        @if (session('msg'))
            <div id="msg" class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('msg') }}
            </div>
        @endif
    </div>
    @if(session()->has('message2'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message2') }}
        </div>
    @endif
    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>


    <div class="addUser my-4 row">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap col-lg-10">
            <h3>{!! $pageTitle !!}</h3>
        </div>
        <div class="col-lg-2">
            <button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-block btn-dark"><i class="fa fa-arrows-alt-h mr-2 "></i>دمج الطلبات </button>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="exampleModal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">هل نت متأكد من دمج الطلبات معا</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.ips.merge')}}" method="post">
                <div class="modal-body">
                    <h6 class="text-danger">لا يمكن الرجوع فى هذه الخطوة</h6>
                    <p>فى حالة دمج الطلبات سيتم إضافة جولات الطلبات الأخرى للطلب التى سيتم إختياره </p>
                    <hr>

                        @csrf
                        {{method_field('POST')}}
                        <input type="hidden" name="ip" value="{{$id}}">
                        <div class="form-group">
                            <label for="request_id">من فضلك إختر الإستشارى </label>
                            <select name="request_id" id="request_id" class="form-control" style="height:45px">
                                @foreach($requests as $request)
                                    <option value="{{$request->id}}">{{$request->user->name ?? 'لا يوجد إستشارى'}}</option>
                                @endforeach
                            </select>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="modal-btn-no3" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" id="modal-btn-si3" class="btn btn-success">نعم دمج </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <br>





    <div class="tableBar">
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
                        <div class="addBtn col-md-5 mt-lg-0 mt-3">

                        </div>

                    </div>
                </div>
                <div class="col-lg-3 mt-lg-0 mt-3">
                    <div id="dt-btns" class="tableAdminOption">

                    </div>
                </div>
            </div>
        </div>

        <div class="dashTable">
            <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
                <thead>
                <tr>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification agent') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'note') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'notes_status_quality') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'assign req date') }} <br>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'in agent') }}</th>
                    <th> هل تم استلامه من <br> قبل الجودة</th>
                    <th>عرض  </th>
                </tr>
                </thead>
                <tbody>
                @foreach($requests as $request)
                    <tr>
                        <td>{{$request->id}}</td>
                        <td>{{ \Carbon\Carbon::parse($request->created_at)->format('Y-m-d g:ia')}}</td>
                        <td>{{$request->type}}</td>
                        <td>{{$request->user->name ?? 'لا يوجد إستشاري'}}</td>
                        <td>{{$request->customer->name}}</td>
                        <td>{{$request->customer->mobile}}</td>
                        <td>{{$request->statusCode}}</td>
                        <td>{{$request->requestSource->value}}</td>
                        <td>
                           @php($classifcations_sa = \App\classifcation::where('id', $request->class_id_agent)->first())

                            @if ($classifcations_sa != null)
                            {{$classifcations_sa->value}}
                            @else
                            {{ $request->class_id_agent}}
                            @endif
                        </td>
                        <td>{{$request->comment}}</td>
                        <td>{{$request->quacomment}}</td>
                        <td>{{$request->agent_date}}</td>
                        <td>{{$request->is_quality_recived}}</td>
                        <td>
                            @if ($request->type == 'رهن-شراء')
                           <span class="item pointer" id="open" data-id="' . $row->id . '" data-toggle="tooltip" data-placement="top" title="{{MyHelpers::admin_trans(auth()->user()->id, 'Open')}}">
                            <a href="{{route('admin.morPurRequest', $request->id)}}"><i class="fas fa-eye"></i></a></span>';
                            @else
                                <span class="item pointer" id="open" data-id="' . $row->id . '" data-toggle="tooltip" data-placement="top" title="{{MyHelpers::admin_trans(auth()->user()->id, 'Open')}}">
                                    <a class="btn btn-primary btn-sm text-white" href="{{route('admin.fundingRequest', $request->id)}}"><i class="fas fa-eye mr-2"></i></a>
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
@section('css_style')
    {{--Sweet Alert--}}
    <script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
    <link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
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
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                dom: 'Bfrtip',
                buttons: [
                    // 'copyHtml5',
                    'excelHtml5',
                    // 'csvHtml5',
                    // 'pdfHtml5' ,
                    'print',
                    'pageLength'
                ],
                initComplete: function() {
                    /* To Adaptive with New Design */
                    $('#example-search-input').keyup(function() {
                        dt.search($(this).val()).draw();
                    })
                    dt.buttons().container()
                        .appendTo('#dt-btns');
                    $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
                    $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                    $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                    $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                    $('.buttons-excel').addClass('no-transition custom-btn');
                    $('.buttons-print').addClass('no-transition custom-btn');
                    $('.buttons-collection').addClass('no-transition custom-btn');

                    $('.tableAdminOption span').tooltip(top)
                    $('button.dt-button').tooltip(top)

                    /* To Adaptive with New Design */

                },
            });
        });
    </script>
@endsection
