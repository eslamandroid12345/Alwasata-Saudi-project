@extends('layouts.content')
@section('title',__("global.hasbah_requests"))
@section('css_style')
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection
@section('customer')
    <div class="addUser my-3">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>    طلبات الحاسبة - العميل : {{ $guest->name ?? ''}}</h3>
        </div>
    </div>
    <br>
    <div class="messages-box" style="display: none;" id="list-loading">
        <div id="loading"><img src="{{ url('assest/images/loadingLogo.png') }}" alt=""> </div>
    </div>


    <div class="topRow">
        <form name="filter" id="filter" method="get" action="">
            <div class="row align-items-center text-center text-md-left">

                <div class="col-6">
                    <label class="label my-2"> الاسم</label>
                    <input class="form-control" type="text" name="" value="{{ $guest->name ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label my-2"> البريد الالكتروني</label>
                    <input class="form-control" type="text" name="" value="{{ $guest->email  ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label my-2">رقم الجوال </label>
                    <input class="form-control" type="text" name="" value="{{ $guest->mobile ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label my-2"> الراتب </label>
                    <input class="form-control" type="text" name="" value="{{ $guest->salary ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label my-2"> التاريخ</label>
                    <input class="form-control" type="text" name="" value="{{ $guest->created_at->format('Y-m-d') ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label my-2">العدد  </label>
                    <input class="form-control" type="text" name="" value="{{ $guest->count ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label my-2"> جهة العمل</label>
                    <input class="form-control" type="text" name="" value="{{ $guest->work  ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label my-2"> الرتبه العسكرية</label>
                    <input class="form-control" type="text" name="" value="{{ $guest->military_rank  ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label my-2">هل لديه طلب ؟ </label>
                    <input class="form-control" type="text" name="" value="{{ $guest->has_request == 1 ? 'لديه طلب سابق' : 'ليس لديه طلب سابق'}}" disabled>
                </div>

                <div class="col-6">
                    <label class="label my-2">حالة الطلب</label>
                    <input class="form-control" type="text" name="" value="{{ $guest->status == 1 ? 'أكمل الطلب' : 'لم يكمل الطلب' }}" disabled>
                </div>

            </div>

        </form>

    </div>
@endsection
@section('updateModel')
@endsection
@section('scripts')
@endsection
