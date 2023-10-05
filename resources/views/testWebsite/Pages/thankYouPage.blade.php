@extends('testWebsite.layouts.master')

@section('title') شكرا @endsection


@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')


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


                @if ($helpDesk == 'yes')

                <p>تم استقبال طلبك بنجاح ، سيتم التواصل بك قريبا . </p>

                <!--
   @elseif($key !=null)
                <meta http-equiv="refresh" content="1;url={{route($key)}}" />.
                <h4>سيتم إعادة توجيهك إلى صفحة كود التحقق خلال ثواني </h4>

                -->

                @else
                @if($id)
                <p>{{ MyHelpers::guest_trans('Thank you for your registration, one of our consultants will get in touch with you as soon as possible to provide the most suitable financing solution') }}, {{ MyHelpers::guest_trans('Your request No') }}. #<span class="text-danger">{{$id}}</span> {{ MyHelpers::guest_trans('You can inquire via this link') }} <a href="https://alwsata.com.sa/ar/my-requests" style="color:blue;text-decoration: underline;">https://alwsata.com.sa/ar/my-requests</a><br>
                    {{ MyHelpers::guest_trans('Keep in touch through our following social media networks') }}: </p>
                @else

                <p>{{ MyHelpers::guest_trans('Thank you for your registration, one of our consultants will get in touch with you as soon as possible to provide the most suitable financing solution') }}, {{ MyHelpers::guest_trans('Your request No') }}. #<span class="text-danger">{{$id}}</span> {{ MyHelpers::guest_trans('You can inquire via this link') }} https://alwsata.com.sa/ar/my-requests {{ MyHelpers::guest_trans('Keep in touch through our following social media networks') }}: </p>

                @endif

                @endif

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