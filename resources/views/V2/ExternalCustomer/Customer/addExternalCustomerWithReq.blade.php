@extends('layouts.content')

@section('title')
    أضافة طلب إلى طلباتي الخاصة
@endsection
@section('css_style')
    <!--NEW 2/2/2020 for hijri datepicker-->
    <link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />

@endsection

@section('customer')


    <div>
        @if (session('success'))
            <div id="msg" class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        @if (session('msg'))
            <div id="msg" class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('msg') }}
            </div>
        @endif
    </div>


    <section class="new-content mt-0">
        <div class="container-fluid">

            <div class="row ">
                <div class="col-md-8 offset-md-2">
                    <div class="row">
                        <div class="col-lg-12   mb-md-0">
                            <div class="userFormsInfo  ">
                                <div class="headER topRow text-center">
                                    <i class="fas fa-user"></i>
                                    <h4>أضافة طلب إلى طلباتي الخاصة</h4>
                                </div>
                                <form action="{{ route('V2.BankDelegate.external.customer.store')}}" method="post" class="">
                                    @csrf
                                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                    <div class="userFormsContainer mb-3">
                                        <div class="userFormsDetails topRow">
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="name">{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</label>
                                                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}">
                                                    </div>
                                                    @if ($errors->has('name'))
                                                        <span class="help-block col-md-12">
                                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('name') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                                {{--                                            <div class="col-12 mb-3">--}}
                                                {{--                                                <div class="form-group">--}}
                                                {{--                                                    <label for="password">الرقم السري</label>--}}
                                                {{--                                                    <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" autocomplete="password" autofocus placeholder="الرقم السري">--}}
                                                {{--                                                </div>--}}
                                                {{--                                                @if ($errors->has('password'))--}}
                                                {{--                                                    <span class="help-block col-md-12">--}}
                                                {{--                                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('password') }}</strong>--}}
                                                {{--                                                </span>--}}
                                                {{--                                                @endif--}}
                                                {{--                                            </div>--}}

                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <input id="mobile" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="5xxxxxxxx">
                                                    </div>
                                                    <span class="text-danger" id="error" role="alert"> </span>
                                                    @if ($errors->has('mobile'))
                                                        <span class="help-block col-md-12">
                                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('mobile') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="notes">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'note') }}
                                                        </label>
                                                        <textarea class="form-control" id="notes" name="note" >{{ old('note') }}</textarea>
                                                    </div>
                                                    <span class="text-danger" id="error" role="alert"> </span>
                                                    @if ($errors->has('note'))
                                                        <span class="help-block col-md-12">
                                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('note') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                                {{--<div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="mobile">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}
                                                        </label>
                                                        <input id="salary" name="salary" type="number" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary') }}" >
                                                    </div>
                                                    <span class="text-danger" id="error" role="alert"> </span>
                                                    @if ($errors->has('salary'))
                                                        <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('salary') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>--}}


                                                <div class="col-12">
                                                    <button type="submit" class="Green d-block border-0 w-100 py-2 rounded text-light addUserClient">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </section>


@endsection
