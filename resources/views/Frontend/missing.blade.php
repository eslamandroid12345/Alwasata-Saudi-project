@extends('Frontend.layouts.master')
@section('title') Error 404 @endsection
@section('content')
    <section class="innerSlider wow fadeInUp">
        <img src="{{ URL::to('website_style/frontend/images/img_requst.jpg') }}">
        <div class="innerCaption">
            <h3>OOPS Page not Found !</h3>
        </div>
    </section>
    <section class="content wow fadeInUp">
        <div class="container">
            <div class="row">
                <p>
                  <a href="{{ route('frontend.index') }}"> Return to home page </a></p>
            </div>
        </div>
    </section>
@endsection