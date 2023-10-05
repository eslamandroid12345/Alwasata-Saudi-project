@extends('layouts.content')

@section('title')
تفاصيل التطبيق
@endsection


@section('css_style')

<style>
    .select2-results {
        max-height: 350px;
    }

    .bigdrop {
        width: 600px !important;
    }
</style>
@endsection

@section('customer')

@if(session()->has('message'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message') }}
</div>
@endif

@if(session()->has('message2'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif

<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if (session('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{!! session('success') !!}</li>
                        </ul>
                    </div>
                    @elseif(session('error'))
                    <div class="alert alert-danger">
                        <ul>
                            <li>{!! session('error') !!}</li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

            <div class="row">

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header"> اعداد التفاصيل الخاصه بالتطبيق</div>
                        <div class="card-body card-block">

                            <form action="{{route('app_details.update','test')}}" method="post" id="form-data"  enctype="multipart/form-data" class="">
                                @csrf
                                @method('PATCH')

                                @foreach($app_details as $detail)
                                <div class="row form-group">
                                    <div class="col-lg-4">
                                        <label for="managers" class="control-label mb-1">اسم الايقون</label>
                                        <input type="text" class="form-control" name="icon_name[]" value="{{$detail->icon_name}}" disabled>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="managers" class="control-label mb-1">عنوان الايقون</label>
                                        <input type="text" class="form-control" name="icon_title[]" value="{{$detail->icon_title}}" required>
                                        @error('icon_title')
                                            <small class="form-text text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="managers" class="control-label mb-1">وصف الايقون</label>
                                        <textarea class="form-control" name="icon_desc[]" required>{{$detail->icon_desc}}</textarea> 
                                        @error('icon_desc')
                                            <small class="form-text text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                @endforeach

                                <br>
                                <div class="form-actions form-group">
                                    <button type="submit" class="btn btn-info btn-block">تعديل</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


@endsection

@section('scripts')

@endsection
