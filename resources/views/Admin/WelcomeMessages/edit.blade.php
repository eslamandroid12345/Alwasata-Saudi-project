@extends('layouts.content')

@section('title', __("replace.update", ['name' => trans_choice('choice.WelcomeMessages',1)]))

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
                                <form action="{{route('admin.updateWelcomeMessage')}}" method="post" class="">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $welcomeMessage->id }}">
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <label class="label">@lang('attributes.request_source')</label>
                                            <br>
                                            <div class="rs-select2 js-select-simple select--no-search">
                                                <select class="form-control" name="request_source_id[]" multiple required id="request_source_id">
                                                    @foreach($requestSources as $v)
                                                        <option value="{{ $v->id }}" {{ in_array($v->id,$welcomeMessage->requestSources->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                            {{ $v->value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="select-dropdown"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="label">@lang('attributes.classification_id')</label>
                                            <br>
                                            <div class="rs-select2 js-select-simple select--no-search">
                                                <select class="form-control" name="classification_id[]" multiple required id="classification_id">
                                                    @foreach($UserAgentClassificationsSelect as $v)
                                                        <option value="{{ $v['id'] }}" {{ in_array($v['id'],$welcomeMessage->classifications->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $v['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="select-dropdown"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="welcome_message" class="control-label mb-1">الرسالة الترحيبية</label>
                                            <textarea class="form-control" name="welcome_message" required>{{ $welcomeMessage->welcome_message }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-6 mt-4">
                                            <label for="time" class="control-label mb-1">المدة الزمنية (س)</label>
                                            <input class="form-control" type="number" name="time" value="{{ $welcomeMessage->time }}" required/>
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


@push('styles')
    <link href="{{ url('interface_style/search/vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ url('interface_style/search/vendor/datepicker/daterangepicker.css') }}" rel="stylesheet" media="all">
    <link href="{{ url('assest/datatable/style.css') }}" rel="stylesheet" media="all">
    <style>
        svg:not(:root) {
            overflow: hidden;
            direction: ltr;
        }
    </style>
@endpush

@push('scripts')
    <!-- Jquery JS-->
    <script src="{{ url('interface_style/search/vendor/jquery/jquery.min.js') }}"></script>
    <!-- Vendor JS-->
    <script src="{{ url('interface_style/search/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/jquery-validate/jquery.validate.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/bootstrap-wizard/bootstrap.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <!-- Main JS-->
    <script src="{{ url('interface_style/search/js/global.js') }}"></script>
@endpush
