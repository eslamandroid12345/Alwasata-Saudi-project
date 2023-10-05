@extends('layouts.content')
@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }}
@endsection
@section('css_style')
@endsection
@section('customer')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }}
                            </div>
                            <div class="card-body card-block">
                                @if(\Session::has('errors_api') )
                                    <div class="alert alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        {!! \Session::get('errors_api')  !!}
                                    </div>
                                @else
                                @endif
                                <form action="{{route('admin.updateProductTypeItem')}}" method="post" class="">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $productType['data']['id'] }}">
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> الإسم العربي </label>
                                            <input type="text" name="name_ar" class="form-control" value="{{ $productType['data']['name_ar'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> الإسم الإنجليزي </label>
                                            <input type="text" name="name_en" class="form-control" value="{{ $productType['data']['name_en'] }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="code" class="control-label mb-1">الكود</label>
                                            <input type="text" class="form-control" name="code" value="{{ $productType['data']['code'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="first_batch_percentage" class="control-label mb-1">نسبة الدفعة الأولي</label>
                                            <input type="text" class="form-control" name="first_batch_percentage" value="{{ $productType['data']['first_batch_percentage'] }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" {{ $productType['data']['property_status'] === true ? 'checked' : ''}}
                                            name="property_status" id="isChecked" class="js-switch">
                                            <label for="property_status" class="control-label mb-1"> حالة العقار </label>
                                        </div>
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" {{ $productType['data']['active'] === true ? 'checked' : ''}}
                                            name="active" id="isChecked" class="js-switch">
                                            <label for="active" class="control-label mb-1"> فعال </label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-4 form-group">
                                            <button type="submit" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</button>
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
        $(document).ready(function() {
            $('#stutus , #classifcations').select2();
        });
    </script>
    <script>
        let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
    </script>
    <script>
        $(document).ready(function(){
            $('#isChecked').change(function(){
                if($(this).prop('checked'))
                {
                    $('#txt').show();
                }else{
                    $('#txt').hide();
                }
            })
        });
    </script>
@endsection