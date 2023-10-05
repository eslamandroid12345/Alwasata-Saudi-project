@extends('layouts.content')
@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'extra_funding_years_calculator_settings') }}
@endsection
@section('css_style')
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection
@section('customer')
    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>المقترحات</h3>
        </div>
    </div>
    <br>
    <div class="card p-5 ">
        <div class="row">
            <div class="col-lg-6" style="float: right">
                <div class="card" style="position: relative">
                    <div class="card-body">
                        <span class="badge badge-success" style="position:absolute;top: 5px;left: 5px">{{$years}} بالإنتظار </span>

                        <h5>اقتراحات تمديد السنوات</h5>
                        <p>من فضلك قم بالتقييم بالموافقة او الرفض على المقترح</p>
                        <a href="{{route('all.suggestions.years.index')}}" class="btn btn-success mt-1 mr-1">دخول</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" style="float: right">
                <div class="card" style="position: relative">

                    <div class="card-body">
                        <span class="badge badge-danger" style="position:absolute;top: 5px;left: 5px">{{$banks}} بالإنتظار </span>
                        <h5>اقتراحات نسب البنوك</h5>
                        <p>من فضلك قم بالتقييم بالموافقة او الرفض على المقترح</p>
                        <a href="{{route('all.suggestions.percentages.index')}}" class="btn btn-danger mt-1 mr-1">دخول</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
