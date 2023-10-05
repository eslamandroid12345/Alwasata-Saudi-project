@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'quality questions') }}
@endsection


@section('css_style')

<link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
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
    }

    td {
        width: 15%;
    }

    .reqNum {
        width: 1%;
    }

    .reqType {
        width: 2%;
    }

    .reqDate {
        text-align: center;
    }

    .commentStyle {
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    tr:hover td {
        background: #d1e0e0
    }
</style>
@endsection

@section('customer')



@if(!empty($message))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ $message }}
</div>
@endif

@if ( session()->has('message') )
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message') }}
</div>
@endif

@if ( session()->has('message2') )
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>


<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3> {{ MyHelpers::admin_trans(auth()->user()->id,'quality questions') }}:</h3>

    </div>
</div>


@if ($questions->count() >0)



<div class="tableBar">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">

            <div class="col-lg-7 ">
                <div class="tableUserOption  flex-wrap ">
                    <div class="addBtn col-md-5 mt-lg-0 mt-3">
                        <a href="{{ url('admin/settings/addquestions') }}">
                            <button class="mr-2 Cloud">
                                <i class="fas fa-plus"></i>
                                اضافة سؤال
                            </button>
                        </a>
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
            <div class="col-lg-4 text-md-right mt-lg-0 mt-3">

                <div id="dt-btns" class="tableAdminOption">
                    {{-- Here We Will Add Buttons of Datatable  --}}

                </div>

            </div>
        </div>
    </div>

    <div class="dashTable">
        <table class="table table-bordred table-striped data-table" id="starreqs-table">
            <thead>
                <tr>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'question') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'question status') }}</th>
                    <th style="text-align:center;">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>

                </tr>
            </thead>
            <tbody>
                @foreach($questions as $item)
                <tr>
                    <td>{{ $item->question }}</td>
                    <td>
                        @if($item->status == 0)
                        <span class="badge badge-primary">مفعل</span>
                        @else
                        <span class="badge badge-danger">غير مفعل</span>
                        @endif
                    </td>
                    <td class="tableAdminOption">

                        <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تعديل">
                            <a href="{{ url('admin/settings/editquestions' ,$item->id ) }}"><i class="fas fa-edit"></i></a></span>

                        @if($item->status == 0)
                        <span class="item pointer Red" data-toggle="tooltip" data-placement="top" title="تفعيل">
                            <a href="{{ url('admin/settings/statusquestions' ,['id' => $item->id, 'status'=> 1]) }}"><i class="fas fa-times"></i></a></span>
                        @else
                        <span class="item pointer Green" data-toggle="tooltip" data-placement="top" title="إلغاء التفعيل">
                            <a  href="{{ url('admin/settings/statusquestions' ,['id' => $item->id, 'status'=>0 ]) }}"><i class="fas fa-check"></i></a></span>

                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="middle-screen">
    <h2 style=" text-align: center;font-size: 20pt;">
        {{ MyHelpers::admin_trans(auth()->user()->id,'No questions') }}
        <br>
        <button type="button" class="btn btn-primary" style="margin-bottom: 20px;">
            <a href="{{ url('admin/settings/addquestions') }}" style="    color: #fff;">اضافة سؤال</a>
        </button>
    </h2>
</div>



@endif



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
                'pageLength',

            ],
            processing: true,
            columns: [

                {
                    data: 'question',
                    name: 'question'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                }

            ],
            initComplete: function() {



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


    //
</script>
@endsection
