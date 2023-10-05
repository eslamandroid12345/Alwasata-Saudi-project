@extends('layouts.content')


@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Bank') }}
@endsection


@section('css_style')


@endsection

@section('customer')
    <!-- MAIN CONTENT-->
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
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Bank') }}
                            </div>
                            <div class="card-body card-block">
                                <form action="{{route('admin.addNewBank')}}" method="post" class="">
                                    @csrf
                                    <div class="form-group">
                                        <label for="bank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name_ar') }}</label>
                                        <input type="text" class="form-control" value="{{ old('name_ar') }}" name="name_ar" id="">
                                        @if ($errors->has('name_ar'))
                                            <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('name_ar') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="bank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name_en') }}</label>
                                        <input type="text" class="form-control" value="{{ old('name_en') }}" name="name_en" id="">
                                        @if ($errors->has('name_en'))
                                            <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('name_en') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="bank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'code') }}</label>
                                        <input type="text" class="form-control" value="{{ old('code') }}" name="code" id="">
                                        @if ($errors->has('code'))
                                            <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('code') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="bank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'sort_order') }}</label>
                                        <input type="number" class="form-control" value="{{ old('sort_order') }}" name="sort_order" id="">
                                        @if ($errors->has('sort_order'))
                                            <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('sort_order') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-4">
                                            <input type="checkbox" value="1" name="property_completed" class="js-switch">
                                            <label for="bank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property_completed') }}</label>
                                        </div>
                                        <div class="form-group col-4">
                                            <input type="checkbox" value="1" name="property_uncompleted" class="js-switch">
                                            <label for="bank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property_uncompleted') }}</label>
                                        </div>

                                        <div class="form-group col-4">
                                            <input type="checkbox" value="1" name="joint" class="js-switch">
                                            <label for="bank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint') }}</label>
                                        </div>
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" name="quest_check" class="js-switch">
                                            <label for="bank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'quest_check') }}</label>
                                        </div>
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" name="bear_tax" class="js-switch">
                                            <label for="bank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'bear_tax') }}</label>
                                        </div>
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" name="guarantees" class="js-switch">
                                            <label for="bank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'guarantees') }}</label>
                                        </div>
                                        <div class="form-group col-4 mt-4">
                                            <input type="checkbox" value="1" name="shl" class="js-switch">
                                            <label for="bank" class="control-label mb-1">آلية سهل</label>
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
