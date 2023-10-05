
{{--@if(!$messages)--}}
{{--         <div class="customer-care d-flex">--}}
{{--            <div class="customer-nav">--}}
{{--                <div class="customer-img">--}}
{{--                    <i class="fas fa-headset fa-lg"></i>--}}
{{--                </div>--}}
{{--                <div class="customer-time clearfix">--}}
{{--                    <span> {{ \Carbon\Carbon::now()->diffForHumans()  }}</span>--}}
{{--                </div>--}}
{{--                <div class="customer-message">--}}
{{--                    <p>مرحبا عميلي العزيز كيف يمكنني مساعدتك ؟</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--@else--}}
{{--        @foreach($messages as $message)--}}
{{--            @if($message['senderId'] == Auth::guard('customer')->id() && $message['from_type'] == $model_type)--}}
{{--                <div class="media w-50 mr-auto mb-5 mt-3">--}}
{{--    <div class="customer-nav">--}}
{{--        <div class="customer-img">--}}
{{--            <i class="fas fa-user-check"></i>--}}
{{--        </div>--}}
{{--        <div class="customer-time clearfix">--}}
{{--            <span> {{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}</span>--}}
{{--        </div>--}}
{{--        <div class="customer-message">--}}
{{--            @if($message['message_type'] == 'text')--}}
{{--                {{ $message['text'] }}--}}
{{--            @elseif($message['message_type'] == 'file')--}}
{{--                <a href="{{ route('openFileFirebase',$message['file'])}}" target="_blank">--}}
{{--                    <img src="{{ asset('newWebsiteStyle/images/pdf-icon.png')}}" width="150" height="150" alt="PDF file">--}}
{{--                </a>--}}
{{--                <a href="{{ route('downloadFileFirebase',$message['file'])}}" target="_blank">--}}
{{--                    <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans('Download') }}"></i>--}}
{{--                </a>--}}
{{--            @elseif($message['message_type'] == 'image')--}}
{{--                <a data-toggle="modal" data-target="#zoom_image" data-id="{{ $message['file'] }}" data-img="{{ url('storage/chat/'.$message['file']) }}" href="">--}}
{{--                    <img src="{{ url('storage/chat/'.$message['file']) }}" width="200" height="200" id="image_{{ $message['file'] }}" />--}}
{{--                </a>--}}
{{--                <a href="{{ route('downloadFileFirebase',$message['file'])}}" target="_blank">--}}
{{--                    <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans('Download') }}"></i>--}}
{{--                </a>--}}
{{--            @elseif($message['message_type'] == 'video')--}}
{{--                <video src="{{ $message['file'] }}" width="200" height="200" controls>--}}
{{--                </video>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--            @elseif($message['receiverId'] == Auth::guard('customer')->id() && $message['to_type'] == $model_type)--}}
{{--                --}}{{-- sales agent--}}
{{--                <div class="media w-50 ml-auto mb-5 mt-3">--}}
{{--                    <div class="customer-nav">--}}
{{--                        <div class="customer-img">--}}
{{--                            <i class="fas fa-headset fa-lg"></i>--}}
{{--                        </div>--}}
{{--                        <div class="customer-time clearfix">--}}
{{--                            <span> {{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}</span>--}}
{{--                        </div>--}}
{{--                        <div class="customer-message">--}}
{{--                            @if($message['message_type'] == 'text')--}}
{{--                                {{ $message['text'] }}--}}
{{--                            @elseif($message['message_type'] == 'file')--}}
{{--                                <a href="{{ route('openFileFirebase',$message['file'])}}" target="_blank">--}}
{{--                                    <img src="{{ asset('newWebsiteStyle/images/pdf-icon.png')}}" width="150" height="150" alt="PDF file">--}}
{{--                                </a>--}}
{{--                                <a href="{{ route('downloadFileFirebase',$message['file'])}}" target="_blank">--}}
{{--                                    <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans('Download') }}"></i>--}}
{{--                                </a>--}}
{{--                            @elseif($message['message_type'] == 'image')--}}
{{--                                <a data-toggle="modal" data-target="#zoom_image" data-id="{{ $message['file'] }}" data-img="{{ url('storage/chat/'.$message['file']) }}" href="">--}}
{{--                                    <img src="{{ url('storage/chat/'.$message['file']) }}" width="200" height="200" id="image_{{ $message['file'] }}" />--}}
{{--                                </a>--}}
{{--                                <a href="{{ route('downloadFileFirebase',$message['file'])}}" target="_blank">--}}
{{--                                    <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans('Download') }}"></i>--}}
{{--                                </a>--}}
{{--                            @elseif($message['message_type'] == 'video')--}}
{{--                                <video src="{{ $message['file'] }}" width="200" height="200" controls>--}}
{{--                                </video>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @else--}}
{{--            @endif--}}
{{--            <div class="clearfix"></div>--}}
{{--        @endforeach--}}
{{--@endif--}}



@if(count($messages ) == 0 )
    <div class="customer-care d-flex">
        <div class="customer-nav">
            <div class="customer-img">
                <i class="fas fa-headset fa-lg"></i>
            </div>
            <div class="customer-time clearfix">
                <span> {{ \Carbon\Carbon::now()->diffForHumans()  }}</span>
            </div>
            <div class="customer-message">
                <p>مرحبا عميلي العزيز كيف يمكنني مساعدتك ؟</p>
            </div>
        </div>
    </div>
@else
    @foreach($messages as $message)
        @if($message['senderId'] == Auth::guard('customer')->id() && $message['from_type'] == $model_type)
            <div class="customer-ask d-flex float-right mt-4 ">
                <div class="customer-ask-nav">
                    <div class="customer-ask-img">
                        <img src="{{ asset('newWebsiteStyle/images/555.png')}}">
                    </div>
                    <div class="customer-ask-time clearfix">
                        <span>{{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}</span>
                    </div>
                    <div class="customer-ask-message">

                        @if($message['message_type'] == 'text')
                            {{ $message['text'] }}
                        @elseif($message['message_type'] == 'file')
                            <a href="{{ route('openFileFirebase',$message['file'])}}" target="_blank">
                                <img src="{{ asset('newWebsiteStyle/images/pdf-icon.png')}}" width="150" height="150" alt="PDF file">
                            </a>
                            <a href="{{ route('downloadFileFirebase',$message['file'])}}">
                                <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans('Download') }}"></i>
                            </a>
                        @elseif($message['message_type'] == 'image')
                            <a data-toggle="modal" data-target="#zoom_image" data-id="{{ $message['file'] }}" data-img="{{ url('storage/chat/'.$message['file']) }}" href="">
                                <img src="{{ url('storage/chat/'.$message['file']) }}" width="200" height="200" id="image_{{ $message['file'] }}" />
                            </a>
                            <a href="{{ route('downloadFileFirebase',$message['file'])}}">
                                <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans('Download') }}"></i>
                            </a>
                        @elseif($message['message_type'] == 'video')
                            <video src="{{ $message['file'] }}" width="200" height="200" controls>
                            </video>
                        @endif

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
                        <span>{{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}</span>
                    </div>
                    <div class="customer-message">

                        @if($message['message_type'] == 'text')
                            {{ $message['text'] }}
                        @elseif($message['message_type'] == 'file')
                            <a href="{{ route('openFileFirebase',$message['file'])}}" target="_blank">
                                <img src="{{ asset('newWebsiteStyle/images/pdf-icon.png')}}" width="150" height="150" alt="PDF file">
                            </a>
                            <a href="{{ route('downloadFileFirebase',$message['file'])}}">
                                <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans('Download') }}"></i>
                            </a>
                        @elseif($message['message_type'] == 'image')
                            <a data-toggle="modal" data-target="#zoom_image" data-id="{{ $message['file'] }}" data-img="{{ url('storage/chat'.$message['file']) }}" href="">
                                <img src="{{ url('storage/chat/'.$message['file']) }}" width="200" height="200" id="image_{{ $message['file'] }}" />
                            </a>
                            <a href="{{ route('downloadFileFirebase',$message['file'])}}">
                                <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans('Download') }}"></i>
                            </a>
                        @elseif($message['message_type'] == 'video')
                            <video src="{{ $message['file'] }}" width="200" height="200" controls>
                            </video>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <div class="clearfix"></div>
    @endforeach
@endif










































