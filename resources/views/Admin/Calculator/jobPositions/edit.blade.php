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
                                <form action="{{route('admin.updateJobPosition')}}" method="post" class="">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $jobPosition['data']['id'] }}">
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">الإسم العربي</label>
                                            <input type="text" class="form-control" value="{{ $jobPosition['data']['name_ar'] }}" name="name_ar">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">الإسم الإنجليزي</label>
                                            <input type="text" class="form-control" value="{{ $jobPosition['data']['name_en'] }}" name="name_en">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">الكود</label>
                                            <input type="text" class="form-control" value="{{ $jobPosition['data']['code'] }}" name="code">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">نسبة استقطاع صافي الراتب</label>
                                            <input type="text" class="form-control" value="{{ $jobPosition['data']['salary_deduction'] }}" name="salary_deduction">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">سن التقاعد</label>
                                            <input type="text" class="form-control" value="{{ $jobPosition['data']['retirement_age'] }}" name="retirement_age">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">حساب الراتب التقاعدي</label>
                                            <input type="text" class="form-control" value="{{ $jobPosition['data']['retirement_calc_number'] }}" name="retirement_calc_number">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="job_position" class="control-label mb-1">ترتيب العرض</label>
                                            <input type="text" class="form-control" value="{{ $jobPosition['data']['sort_order'] }}" name="sort_order">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-3 mt-4">
                                            <input type="checkbox" value="1" {{ $jobPosition['data']['retirement'] === true ? 'checked' : ''}}
                                            name="retirement" id="isChecked" class="js-switch">
                                            <label for="retirement" class="control-label mb-1"> متقاعد </label>
                                        </div>

                                        <div class="form-group col-3 mt-4">
                                            <input type="checkbox" value="1" {{ $jobPosition['data']['active'] === true ? 'checked' : ''}}
                                            name="active" class="js-switch">
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
@endsection