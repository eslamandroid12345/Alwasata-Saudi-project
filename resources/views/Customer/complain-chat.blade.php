@extends('layouts.Customermaster')

@section('title') تواصل مع استشاري المبيعات @endsection


@section('content')

    <section class="order-sub-shop after_bg mt-5">

        <div class="container">
            <div class="head-div text-center wow fadeInUp mb-5">
                <h3>{{$complain->title}}</h3>
                <h4>{{--{{$user->name}}--}}</h4>
            </div>
        </div>

        <div class="cont-message-body message-wrapper" id="messages">


           @if(count($messages ) == 0 )

                <div class="customer-care d-flex">

                    <div class="customer-nav">
                        <div class="customer-img">
                            <i class="fas fa-headset fa-lg"></i>
                        </div>
                        <div class="customer-time clearfix">
                            <span> {{ \Carbon\Carbon::now() }}</span>
                        </div>
                        <div class="customer-message">
                            <p>مرحبا استاذي العزيز كيف يمكني مساعدتك ؟</p>
                        </div>
                    </div>
                </div>

            @else

            @foreach($messages as $message)

                  @if($message->type == 'send')

                        <div class="customer-ask d-flex float-right mt-4 ">

                            <div class="customer-ask-nav">
                                <div class="customer-ask-img">
                                    <img src="{{ asset('newWebsiteStyle/images/555.png')}}">
                                </div>
                                <div class="customer-ask-time clearfix">
                                    <span>{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="customer-ask-message">
                                    {{$message->message}}
                                </div>
                            </div>
                        </div>


                    @else
                        <div class="customer-care d-flex">

                            <div class="customer-nav">
                                <div class="customer-img">
                                    <i class="fas fa-headset fa-lg"></i>
                                </div>
                                <div class="customer-time clearfix">
                                  <span> {{ $message->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="customer-message">
                                    {{$message->message}}
                                </div>
                            </div>
                        </div>


                  @endif

                    <div class="clearfix"></div>

             @endforeach

            @endif

        </div>


        <form action="{{route('complain.chatStore')}}" method="POST">
            @csrf
            {{method_field('POST')}}
            <div class="message-form">

                <input type="hidden" name="customer_id" value="{{auth()->user()->id}}" >
                <input type="hidden" name="complain_id" value="{{$complain->id}}" >
                <input type="hidden" name="user_id" value="{{auth()->user()->user_id}}" >
                <input type="hidden" name="type" value="send" >
                <div class="message-type d-flex flex-wrap">
                    <div class="message-body">
                        <input type="text" id="message" name="message" placeholder="{{ MyHelpers::guest_trans('Type a message') }}">
                    </div>
                    <div class="img-select mx-4 au-input-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </div>
                    <div class="message-send">
                        <button class="exp" id="msg" type="submit">
                            ارسال
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </section>

@endsection


@section('scripts')
<script>
       $(document).ready(function () {
        $("html, body").animate({
            scrollTop: document.body.scrollHeight
        }, "slow");
        
            });
</script>

@endsection
