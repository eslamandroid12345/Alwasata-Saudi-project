@extends('testWebsite.layouts.master')

@section('title') شكرا @endsection


@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
<style>
    .btn-list{
        list-style: none;
    }
    .btn-list li a {
        background-color: #0f5b94;
    }
    .btn-list li {
        display: inline-block;
    }
</style>

<!--MODAL-->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">تم تسجيلك بنجاح <i class="fa fa-check" aria-hidden="true" style="color:green"></i> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>بإمكانك تسجيل الدخول من خلال :</h5>
                <h7 style="text-align: right ;"> رقم جوالك أو اسم المستخدم : <span id="custuser" style="font-weight:bold"></span> </h7>
                <br>
                <h7 style="text-align: right ;"> كلمة المرور : <span id="custpass" style="font-weight:bold"></span></h7>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<!--MODAL-->

<div class="myOrders">
    <div class="container">
        <div class="head-div text-center">
            <h1>{{ MyHelpers::guest_trans('Thank you')}}</h1>

            <div class="order-my requstform">


                {{-- <p>تم استقبال طلبك بنجاح ، سيتم التواصل بك قريبا . </p> --}}
                <div class="col-lg-12 mx-auto">

                    <div class="step_title shadow-add  py-4 bg-white px-3 pt-3 col-lg-12">
                        @if (session()->has('message'))
                        <h4 class="mt-3 mb-4  text-center">{{session()->get('message')}}</h4>

                        @else
                        <h4 class="mt-3 mb-4  text-center">شكراً لك, تم تسجيل طلبك بنجاح و سيتم التواصل معك في اقرب وقت.</h4>

                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="btn-list">
                        <li>
                            <a class="btn btn-primary" href="{{route('careers')}}">
                            عودة الي صفحة التقديم علي وظيفة
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- <meta http-equiv="refresh" content="1;url={{url('/')}}" />. --}}


                <br>
                <div class="footer-desc">

                    <div class="icons-footer  ">
                        <span>
                            <a target="_blank" href="https://www.facebook.com/alwsatasa/">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </span>

                        <span>
                            <a target="_blank" href="https://www.snapchat.com/add/alwsata">
                                <i class="fab fa-snapchat"></i>
                            </a>
                        </span>

                        <span>
                            <a target="_blank" href="https://twitter.com/alwsatasa">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </span>
                        <span>
                            <a target="_blank" href="https://www.instagram.com/alwsatasa/">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </span>

                        <span>
                            <a target="_blank" href="https://www.youtube.com/channel/UC22GoF4CdghF5nv3g018IZA">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </span>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')

<script type="text/javascript">

</script>
@endsection
