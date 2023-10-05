@extends('layouts.content')


@section('css_style')
<!--NEW 2/2/2020 for hijri datepicker-->
<link href="{{ url("/") }}/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />

@endsection

@section('customer')
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
                    <div class="alert alert-error">
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
                        <div class="card-header">
                            تغيير الفرق بين النتيجتين
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('admin.settings.form.update')}}" method="post" class="">
                                @csrf
                                <div class="row">

                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                </div>

                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label style="text-align:center; float:center"class="control-label mb-1"> الفرق بين النتيجتين</label>
                                            <input type="number" min="0" id="{{$fields->option_name}}" class="form-control" name="{{$fields->option_name}}" value="{{$fields->option_value}}">
                                        </div>
                                    </div>
                                </div>

                                <div >
                                    <div class="form-group" style="padding-top: 25px">
                                        <button type="submit" style="height: 45px" class="btn btn-info">حفظ الاعدادات</button>
                                    </div>
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