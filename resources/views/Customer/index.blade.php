@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->guard('customer')->user()->id,'Customer') }}
@endsection

@section('css_style')

@endsection

@section('customer')


    <div>
        @if (session('message'))
            <div id="msg" class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('message') }}
            </div>
        @endif
        @if (session('msg'))
            <div id="msg" class="alert alert-danger">
                <button type="button" class="close pull-left" data-dismiss="alert">&times;</button>
                {{ session('msg') }}
            </div>
        @endif

    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        Hi {{auth()->guard('customer')->user()->name}}!
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')


@endsection
