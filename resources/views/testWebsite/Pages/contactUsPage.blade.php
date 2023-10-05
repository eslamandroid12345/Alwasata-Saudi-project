@extends('testWebsite.layouts.master')

@section('title') تواصل معنا @endsection


@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
<section class="calc">
    <div class="container">
        <div class="head-div text-center">
            <h1>تواصل معنا</h1>
        </div>
        <div class="calc-form funding_request_wrapper">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="calc-cont">

                        <form id="help_desk">
                            <p class="message-box alert"></p>
                            {{ csrf_field() }}

                            <div class="row first-calc">

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                    <label for="name">{{ MyHelpers::guest_trans('Full name') }}</label>
                                    <input type="text" name="name" class="form-control" id="inputnameError" placeholder="{{ MyHelpers::guest_trans('Full name') }}">
                                    <span class="text-danger" style="color:red;" id="nameError" role="alert"> </span>
                                </div>


                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                    <label for="mobile">{{ MyHelpers::guest_trans('mobile') }}</label>
                                    <input id="inputmobileError" name="mobile" class="form-control" type="number" autocomplete="mobile" placeholder="5xxxxxxxxx">
                                    <span class="text-danger" style="color:red;" id="mobileError" role="alert"> </span>
                                </div>


                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                    <label for="email">{{ MyHelpers::guest_trans('Email') }}</label>
                                    <input type="email" name="email" class="form-control" id="inputemailError" placeholder="example@example.com">
                                    <span class="text-danger" style="color:red;" id="emailError" role="alert"> </span>
                                </div>

                            </div>




                            <div class="row sec-calc mx-0">

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


                                    <label for="descrebtion">رسالتك</label>

                                    <textarea class="form-control" name="descrebtion" rows="3" ></textarea>

                                    <span class="text-danger" style="color:red;" id="descrebtionError" role="alert"> </span>

                                </div>

                            </div>
                        


                        </form>

                        <div class="send-btn">
                            <button ><i class="fas fa-paper-plane ml-2"></i> أرسل</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


@section('scripts')

<script type="text/javascript">
    

</script>
@endsection