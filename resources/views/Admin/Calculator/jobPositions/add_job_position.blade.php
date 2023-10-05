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
                                <form action="{{route('admin.saveNewJobPosition')}}" method="post" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name_ar') }}</label>
                                            <input type="text" class="form-control" value="{{ old('name_ar') }}" name="name_ar">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name_en') }}</label>
                                            <input type="text" class="form-control" value="{{ old('name_en') }}" name="name_en">
                                        </div>

                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'code') }}</label>
                                            <input type="text" class="form-control" value="{{ old('code') }}" name="code">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary_deduction') }}</label>
                                            <input type="text" class="form-control" value="{{ old('salary_deduction') }}" name="salary_deduction" id="">
                                        </div>

                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'retirement_age') }}</label>
                                            <input type="text" class="form-control" value="{{ old('retirement_age') }}" name="retirement_age" id="">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'retirement_calc_number') }}</label>
                                            <input type="text" class="form-control" value="{{ old('retirement_calc_number') }}" name="retirement_calc_number" id="">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'sort_order') }}</label>
                                            <input type="text" class="form-control" value="{{ old('sort_order') }}" name="sort_order">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" name="retirement" class="js-switch">
                                            <label for="retirement" class="control-label mb-1"> متقاعد  </label>
                                        </div>
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" name="active" class="js-switch">
                                            <label for="active" class="control-label mb-1"> فعال  </label>
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