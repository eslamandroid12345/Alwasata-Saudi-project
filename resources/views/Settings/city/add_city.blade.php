@extends('layouts.content')


@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'City') }}
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
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'City') }}
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('admin.savecity')}}" method="post" class="">
                                @csrf



                                <div class="form-group">
                                    <label for="city" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'city') }}</label>

                                    <input type="text" class="form-control" value="{{ old('city') }}" name="city" id="">

                                    @if ($errors->has('city'))
                                    <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('city') }}</strong>
                                    </span>
                                    @endif
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
@endsection