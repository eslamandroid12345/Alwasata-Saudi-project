@extends('Frontend.layouts.master')

@section('title') {{ $page->ar_title }} @endsection

@section('content')
    <section class="innerSlider wow fadeInUp">
        @if($page->cover_image)
            <img src="{{asset('website_style/frontend/images/'.$page->cover_image)}}" alt="{{ $page->en_title}}">
        @else
            <img src="{{asset('website_style/frontend/images/img_ask_question.jpg')}}" alt="{{ $page->en_title}}">
        @endif
        <div class="innerCaption">
        
            <h3>{{$page->ar_title}}</h3>
        </div>
    </section>
    <section class="content wow fadeInUp">
        <div class="container">
            <div class="row">
                @if(! is_null($page->form_type))
                    @include($page->template_path, ['page'=>$page])
                @endif
            </div>
        </div>
    </section>
@endsection