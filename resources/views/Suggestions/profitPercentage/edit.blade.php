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
                                تقديم مقترح
                            </div>
                            <div class="card-body card-block">
                                <form action="{{route('all.suggestions.updateProfitPercentage')}}" method="post" class="">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $profitPercentage['data']['id'] }}">
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> البنك </label>
                                            <select class="form-control" name="bank_id">
                                                @foreach($banks['data'] as $bank)
                                                    <option value="{{ $bank['id'] }}" {{ $bank['id']  === $profitPercentage['data']['bank_id'] ? 'selected' : '' }}>{{ $bank['text'] }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('bank_id'))
                                                <span class="help-block col-md-12">
                                                 <strong style="color:red ;font-size:10pt">{{ $errors->first('bank_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> السنة ( من ) </label>
                                            <input type="number" name="from_year" class="form-control" value="{{ $profitPercentage['data']['from_year'] }}">
                                            @if ($errors->has('from_year'))
                                                <span class="help-block col-md-12">
                                                 <strong style="color:red ;font-size:10pt">{{ $errors->first('from_year') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> السنة ( إلي ) </label>
                                            <input type="number" name="to_year" class="form-control" value="{{ $profitPercentage['data']['to_year'] }}">
                                            @if ($errors->has('to_year'))
                                                <span class="help-block col-md-12">
                                                 <strong style="color:red ;font-size:10pt">{{ $errors->first('to_year') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-6 mt-4">
                                            <label for="profit_percentage" class="control-label mb-1"> النسبة </label>
                                            <input type="text" name="percentage" class="form-control" value="{{ $profitPercentage['data']['percentage'] }}">
                                            @if ($errors->has('percentage'))
                                                <span class="help-block col-md-12">
                                                 <strong style="color:red ;font-size:10pt">{{ $errors->first('percentage') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" {{ $profitPercentage['data']['residential_support'] === true ? 'checked' : ''}}
                                            name="residential_support" id="isChecked" class="js-switch">
                                            <label for="profit_percentage" class="control-label mb-1"> الدعم السكني </label>
                                        </div>
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" {{ $profitPercentage['data']['personal'] === true ? 'checked' : ''}}
                                            name="personal" class="js-switch">
                                            <label for="profit_percentage" class="control-label mb-1"> شخصي </label>
                                        </div>
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" {{ $profitPercentage['data']['guarantees'] === true ? 'checked' : ''}}
                                            name="guarantees" class="js-switch">
                                            <label for="profit_percentage" class="control-label mb-1"> الضمانات </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 form-group">
                                            <button type="button" class="btn btn-info " data-toggle="modal" data-target="#exampleModal">إرسال</button>
                                        </div>
                                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">هل أنت متأكد</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        هل أنت متأكد من إرسال المقترح ؟
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                                        <button type="submit" class="btn btn-primary">نعم أرسل</button>
                                                    </div>
                                                </div>
                                            </div>
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
