@extends('layouts.content')


@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}
@endsection
@section('customer')
    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}
                            </div>
                            <div class="card-body card-block">
                                @if(\Session::has('errors_api') )
                                    <div class="alert alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        {!! \Session::get('errors_api')  !!}
                                    </div>
                                @else
                                @endif
                                <form action="{{route('admin.saveNewProductTypeItem')}}" method="post" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label for="name_ar" class="control-label mb-1">الإسم العربي</label>
                                            <input type="text" class="form-control" name="name_ar" value="{{ old('name_ar') }}">
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="name_en" class="control-label mb-1">الإسم الإنجليزي</label>
                                            <input type="text" class="form-control" name="name_en" value="{{ old('name_en') }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label for="code" class="control-label mb-1">الكود</label>
                                            <input type="text" class="form-control" name="code" value="{{ old('code') }}">
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="first_batch_percentage" class="control-label mb-1">نسبة الدفعة الأولي</label>
                                            <input type="text" class="form-control" name="first_batch_percentage" value="{{ old('first_batch_percentage') }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" name="property_status" class="js-switch">
                                            <label for="property_status" class="control-label mb-1">حالة العقار</label>
                                        </div>
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" name="active" class="js-switch">
                                            <label for="active" class="control-label mb-1">فعال</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-4 form-group">
                                            <button type="submit" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</button>
                                        </div>
                                        <div class="col-4"></div>
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
    <script>
        let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
    </script>
@endsection