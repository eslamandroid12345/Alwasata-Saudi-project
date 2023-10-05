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
                                <form action="{{route('admin.saveNewSupportInstallmentItem')}}" method="post" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="extra_funding_years" class="control-label mb-1"> جهة التمويل </label>
                                            <select class="form-control" name="bank_id">
                                                <option selected disabled>جهة التمويل</option>
                                                @foreach($banks['data'] as $bank)
                                                    <option value="{{ $bank['id'] }}">{{ $bank['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> نسبة استقطاع صافي الراتب %</label>
                                            <input type="text" name="salary_deduction" class="form-control" value="{{ old('salary_deduction') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> نسبة استقطاع صافي الراتب الجديدة % </label>
                                            <input type="text" name="new_salary_deduction" class="form-control" value="{{ old('new_salary_deduction') }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> نسبة سقف القسط من الراتب % </label>
                                            <input type="text" name="less_percentage" class="form-control" value="{{ old('less_percentage') }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" name="support_installment" class="js-switch">
                                            <label for="support_installment" class="control-label mb-1"> اضافة قسط الدعم </label>
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