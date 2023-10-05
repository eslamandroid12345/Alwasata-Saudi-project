@extends('layouts.content')
@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}
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
                                <form action="{{route('admin.saveNewProductTypeCheckTotalItem')}}" method="post" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="product_type_id" class="control-label mb-1"> نوع المنتج </label>
                                            <select class="form-control" name="product_type_id">
                                                <option selected disabled>نوع المنتج</option>
                                                @foreach($productType['data'] as $product_type)
                                                    <option value="{{ $product_type['id'] }}">{{ $product_type['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="percentage" class="control-label mb-1"> النسبة (%) </label>
                                            <input type="text" name="percentage" value="{{ old('percentage') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" name="residential_support" class="js-switch">
                                            <label for="residential_support" class="control-label mb-1"> الدعم السكني  </label>
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