@extends('layouts.content')
@section('title')
    العقارات - طلبات العملاء
@endsection
@section('css_style')
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection
@section('customer')
    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>    العقارات - طلبات العملاء - العميل : {{ $realestate->customer->name ?? ''}}</h3>
        </div>
    </div>
    <br>
    <div class="messages-box" style="display: none;" id="list-loading">
        <div id="loading"><img src="{{ url('assest/images/loadingLogo.png') }}" alt=""> </div>
    </div>


    <div class="topRow">
        <form name="filter" id="filter" method="get" action="{{ route('V2.Admin.report1') }}">
            <div class="row align-items-center text-center text-md-left">

                <div class="col-6">
                    <label class="label">إسم العميل</label>
                    <input class="form-control" type="text" name="" value="{{ $realestate->customer->name ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label">رقم الجوال</label>
                    <input class="form-control" type="text" name="" value="{{ $realestate->customer->mobile ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label">إسم استشاري المبيعات</label>
                    <input class="form-control" type="text" name="" value="{{ $realestate->customer->request->user->name ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label">نوع العقار</label>
                    <input class="form-control" type="text" name="" value="{{ $realestate->propertyType->value  ?? '' }}" disabled>
                </div>


                <div class="col-6">
                    <label class="label">سعر العقار</label>
                    <input class="form-control" type="text" name="" value="{{ $realestate->min_price . '-' . $realestate->max_price ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label">مساحة العقار</label>
                    <input class="form-control" type="text" name="" value="{{ $realestate->distance  ?? '' }}" disabled>
                </div>


                <div class="col-6">
                    <label class="label">تاريخ طلب العقار</label>
                    <input class="form-control" type="text" name="" value="{{ $realestate->req_date ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label">المدينة</label>
                    <input class="form-control" type="text" name="" value="{{ $realestate->city->value  ?? '' }}" disabled>
                </div>


                <div class="col-6">
                    <label class="label">المنطقة</label>
                    <input class="form-control" type="text" name="" value="{{ $realestate->district->value ?? '' }}" disabled>
                </div>

                <div class="col-6">
                    <label class="label">الحي</label>
                    <input class="form-control" type="text" name="" value="{{ $realestate->area->value  ?? '' }}" disabled>
                </div>

            </div>

        </form>

    </div>
@endsection
@section('updateModel')
@endsection
@section('scripts')
@endsection
