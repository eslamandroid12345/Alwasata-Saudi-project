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
                                <form action="{{route('admin.updateProductTypeCheckTotalItem')}}" method="post" class="">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $productTypeCheckTotal['data']['id'] }}">
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="product_type_id" class="control-label mb-1"> نوع المنتج </label>
                                            <select class="form-control" name="product_type_id">
                                                @foreach($productTypes['data'] as $productType)
                                                    <option value="{{ $productType['id'] }}" {{ $productType['id']  === $productTypeCheckTotal['data']['product_type_id'] ? 'selected' : '' }}>{{ $productType['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="percentage" class="control-label mb-1"> النسبة (%) </label>
                                            <input type="text" name="percentage" value="{{$productTypeCheckTotal['data']['percentage'] }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" {{ $productTypeCheckTotal['data']['residential_support'] === true ? 'checked' : ''}}
                                            name="residential_support" id="isChecked" class="js-switch">
                                            <label for="residential_support" class="control-label mb-1"> الدعم السكني </label>
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