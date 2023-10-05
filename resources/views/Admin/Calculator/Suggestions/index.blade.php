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
                        <h5>اقتراحات تمديد السنوات</h5>
                        <p>من فضلك قم بالتقييم بالموافقة او الرفض على المقترح</p>
                        <a href="{{route('admin.suggestions.years.index','new')}}" class="btn btn-sm btn-success mt-1 mr-1">
                            الإقتراحات الجديدة
                            <span class="badge badge-light">{{$years}}</span>
                        </a>
                        <a href="{{route('admin.suggestions.years.index','archives')}}" class="btn btn-sm btn-dark mt-1 mr-1">
                            الإقترحات المرفوضة
                            <span class="badge badge-light">{{$archiveYears}}</span>
                        </a>
                        <a href="{{route('admin.suggestions.years.index','approved')}}" class="btn btn-sm btn-primary mt-1 mr-1">
                            الإقتراحات المعتمدة
                        <span class="badge badge-light">{{$approvedYears}}</span>
                        </a>

                    </div>
                </div>
            </div>
            <div class="col-lg-6" style="float: right">
                <div class="card" style="position: relative">

                    <div class="card-body">
                        <h5>اقتراحات نسب البنوك</h5>
                        <p>من فضلك قم بالتقييم بالموافقة او الرفض على المقترح</p>
                        <a href="{{route('admin.suggestions.percentages.index','new')}}" class="btn btn-sm btn-success mt-1 mr-1">
                            الإقتراحات الجديدة
                            <span class="badge badge-light">{{$banks}}</span>
                        </a>
                        <a href="{{route('admin.suggestions.percentages.index','archives')}}" class="btn btn-sm btn-dark mt-1 mr-1">
                            الإقترحات المرفوضة
                            <span class="badge badge-light">{{$archiveBanks}}</span>
                        </a>
                        <a href="{{route('admin.suggestions.percentages.index','approved')}}" class="btn btn-sm btn-primary mt-1 mr-1">
                            الإقتراحات المعتمدة
                            <span class="badge badge-light">{{$approvedBanks}}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
