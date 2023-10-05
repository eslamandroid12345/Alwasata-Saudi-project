@extends('testWebsite.layouts.master')

@section('title', 'تطبيقنا')


@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
<div class="myOrders">
    <div class="container">
        <div class="row justify-content-center">
            @if (session('message'))
                <div class="col-12 col-md-8" style="text-align: center;">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                    </div>
                </div>
            @endif
     @include('testWebsite.Pages.appsection')
    </div>
</div>
@endsection
