@extends('testWebsite.layouts.master')

@section('title') تسجيل دخول @endsection


@section('pageMenu')
    @include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
    <div class="myOrders">
        <div class="container" style="max-width: 900px">

            <div class="head-div text-center wow fadeInUp">
                <h1>إسترجاع كلمة المرور </h1>
            </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                        <form method="POST" action="{{ route('customer.password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">
                            @if(request()->has('email'))
                            <input type="hidden" name="email" value="{{urldecode(request('email'))}}">
                            @else
                            <div class="form-group ">
                                <label for="email" class="col-md-12 col-form-label ">البريد الإلكترونى</label>
                                <input type="hidden" name="customer_id" value="{{$customerId}}">
                                <div class="col-md-12">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$customer->email}}">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="col-lg-12">
                                    <b>
                                        <p class="text-danger" style="padding-top: 10px">يرجى إدخال البريد الإلكتروني أو التأكد من صحته لاستخدامه في استرجاع كلمة المرور في المرات القادمة *</p>
                                    </b>
                                    <hr>
                                </div>
                                <input type="hidden" name="mobile" value="{{request('mobile')}}">
                            </div>
                            @endif

                            <div class="form-group ">
                                <label for="password" class="col-md-12 col-form-label ">كلمة المرور الجديدة</label>

                                <div class="col-md-12">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="password-confirm" class="col-md-12 col-form-label ">إعادة كتابة كلمة المرور الجديدة </label>

                                <div class="col-md-12">
                                    <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12 offset-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        حفظ
                                    </button>
                                </div>
                            </div>
                        </form>

            <!-- end card-body -->
        </div>
        <!-- end card -->

        <!-- end row -->

    </div>
    <!-- end col -->
    <!-- end row -->
@endsection

