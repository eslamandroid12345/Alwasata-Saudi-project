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
                                <form action="{{route('admin.updateSupportInstallmentItem')}}" method="post" class="">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $supportInstallment['data']['id'] }}">
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="support_installment" class="control-label mb-1"> جهة التمويل </label>
                                            <select class="form-control" name="bank_id">
                                                @foreach($banks['data'] as $bank)
                                                    <option value="{{ $bank['id'] }}" {{ $bank['id']  === $supportInstallment['data']['bank_id'] ? 'selected' : '' }}>{{ $bank['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="support_installment" class="control-label mb-1"> نسبة استقطاع صافي الراتب % </label>
                                            <input type="text" name="salary_deduction" class="form-control" value="{{ $supportInstallment['data']['salary_deduction'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="support_installment" class="control-label mb-1">نسبة استقطاع صافي الراتب الجديدة %  </label>
                                            <input type="text" name="new_salary_deduction" class="form-control" value="{{ $supportInstallment['data']['new_salary_deduction'] }}">
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="support_installment" class="control-label mb-1">نسبة سقف القسط من الراتب %</label>
                                            <input type="text" name="less_percentage" class="form-control" value="{{ $supportInstallment['data']['less_percentage'] }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" {{ $supportInstallment['data']['support_installment'] === true ? 'checked' : ''}}
                                            name="support_installment" id="isChecked" class="js-switch">
                                            <label for="support_installment" class="control-label mb-1"> اضافة قسط الدعم </label>
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