@extends('Frontend.layouts.master')
@section('title'){{ MyHelpers::guest_trans('Thank you')}} @endsection
@section('content')
    <section class="innerSlider wow fadeInUp">
        <img src="{{ URL::to('website_style/frontend/images/img_requst.jpg') }}">
        <div class="innerCaption">
            <h3>{{ MyHelpers::guest_trans('Thank you')}}</h3>
        </div>
    </section>
    <section class="content wow fadeInUp">
        <div class="container">
            <div class="row" style="text-align: center;">
                @if($id) 
                    <p>{{ MyHelpers::guest_trans('Thank you for your registration, one of our consultants will get in touch with you as soon as possible to provide the most suitable financing solution') }}, {{ MyHelpers::guest_trans('Your request No') }}. #<span class="text-danger">{{$id}}</span> {{ MyHelpers::guest_trans('You can inquire via this link') }}  <a href="https://alwsata.com.sa/ar/my-requests" style="color:blue;text-decoration: underline;">https://alwsata.com.sa/ar/my-requests</a><br>
                        {{ MyHelpers::guest_trans('Keep in touch through our following social media networks') }}: </p>  
                @else 

                <p>{{ MyHelpers::guest_trans('Thank you for your registration, one of our consultants will get in touch with you as soon as possible to provide the most suitable financing solution') }}, {{ MyHelpers::guest_trans('Your request No') }}. #<span class="text-danger">{{$id}}</span> {{ MyHelpers::guest_trans('You can inquire via this link') }} https://alwsata.com.sa/ar/my-requests {{ MyHelpers::guest_trans('Keep in touch through our following social media networks') }}: </p>  
             
                @endif
                <br>
                <br>
                <p>
                <div class="socialLinks">
                    <ul style="display: flex;justify-content: center;">
                        <li><a target="_blank" href="https://www.snapchat.com/add/alwsata"><i class="fa fa-snapchat"></i></a></li>
                        <li><a target="_blank" href="https://twitter.com/alwsatasa"><i class="fa fa-twitter"></i></a></li>
                        <li><a target="_blank" href="https://www.instagram.com/alwsatasa/"><i class="fa fa-instagram"></i></a></li>
                        <li><a target="_blank" href="https://www.linkedin.com/in/alwsatasa/"><i class="fa fa-linkedin"></i></a></li>
                        <li><a target="_blank" href="https://www.facebook.com/alwsatasa/"><i class="fa fa-facebook"></i></a></li>
                        <li><a target="_blank" href="https://www.youtube.com/channel/UC22GoF4CdghF5nv3g018IZA"><i class="fa fa-youtube"></i></a></li>
                    </ul>
                </div>
                </p>
            </div>
        </div>
    </section>
@endsection
