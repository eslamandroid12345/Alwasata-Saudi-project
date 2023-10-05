@extends('layouts.content')
@section('title')
    الصفحة الرئيسية
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

        tr:hover td {
            background: #d1e0e0
        }
    </style>

    {{--    NEW STYLE   --}}
    <style>
        .tFex{
            position: relative !important;
            width: 100% !important;
        }
        .dataTables_filter { display: none; }
        span.redBg{
            background: #E67681;
        }
        .pointer{
            cursor: pointer;
        }
        .dataTables_info{
            margin-left: 15px;
            font-size: smaller;
        }
        .dataTables_paginate {
            color: #333;
            font-size: smaller;
        }
        .dataTables_paginate , .dataTables_info{
            margin-bottom: 10px;
            margin-top: 10px;
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
            <h3>مرحبا {{auth()->user()->name}}:</h3>
        </div>
    </div>
    <br>

{{--

    <div class="middle-screen">
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No New Requests') }}</h2>
    </div>
--}}

@endsection

@section('scripts')
    <script src="{{ asset('js/tokenize2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>
@endsection



