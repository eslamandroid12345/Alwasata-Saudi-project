@extends('Customer.fundingReq.customerReqLayout')
@section('title') تواصل مع استشاري المبيعات @endsection
@section('style')
    <style>
        .downloadIcon {
            color: white;
            font-size: x-large;
            padding: 3%;
            cursor: pointer;
        }
        @-o-keyframes fadeIt {
            0% {
                color: #FFFFFF;
            }
            50% {
                color: #842515;
            }
            100% {
                color: #FFFFFF;
            }
        }
        @keyframes fadeIt {
            0% {
                color: #FFFFFF;
            }
            50% {
                color: #842515;
            }
            100% {
                color: #FFFFFF;
            }
        }
        .animateIcon {
            background-image: none !important;
            -o-animation: fadeIt 5s ease-in-out;
            animation: fadeIt 5s ease-in-out;
        }
        .afnan {
            position: relative;
            display: none;
        }
        .afnan::before {
            all: initial;
            display: inline-block;
            border-radius: 5px;
            padding: 20px;
            background-color: #1a1a1a;
            content: attr(data-tooltip);
            color: #f9f9f9;
            position: absolute;
            bottom: 120%;
            width: 100px;
            left: 50%;
            transform: translate(-50%, 0);
            margin-bottom: 15px;
            text-align: center;
            font-size: large;
        }
        .afnan::after {
            all: initial;
            display: inline-block;
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 10px solid #1a1a1a;
            position: absolute;
            bottom: 120%;
            content: '';
            left: 50%;
            transform: translate(-50%, 0);
            margin-bottom: 5px;
        }
    </style>
@endsection
@section('content')

    <section class="order-sub-shop after_bg mt-5">
{{--        <div class="form-group" id="process" style="display: none;">--}}
{{--            <div class="progress">--}}
{{--                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div style="text-align: left; padding: 2% ; font-size:x-large">
            <a href="{{ url()->previous() }}">
                رجوع
                <i class="fa fa-arrow-circle-left"> </i>
            </a>
        </div>
        <input type="hidden" value="{{Session::get('redirect')}}" id="SessionValue">
        <div class="container">
            <div class="head-div text-center wow fadeInUp mb-5">
                <h1>استشاري المبيعات</h1>
                <h4>{{$user->name}}</h4>
            </div>
        </div>
        <div class="cont-message-body message-wrapper" id="messages" data-href="{{route ('getMessageCustomer', ['agent' => App\Http\Controllers\CustomerController::salesAgent()])}}">
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
                                    <span>{{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }} </span>
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
                                    <span> {{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}</span>
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
                    @endif
                    <div class="clearfix"></div>
                @endforeach
            @endif
        </div>
        <div id="process" style="display: none; width: 100%; height: 100%; top: 100px; left: 0px; position: fixed; z-index: 10000; text-align: center;">
            <img src="{{ asset('assest/images/Loading_2.gif') }}" />
        </div>
        <div class="message-form">
            <input type="hidden" name="receivers[]" value="{{ json_encode($receivers,TRUE)}}" id="receivers">
            <input type="hidden" name="receiver_model_type" value="{{ $receiver_model_type}}" id="receiver_model_type">
            <input type="hidden" name="message_type" value="text" id="message_type">
            <input type="file" name="file_path" id="file_path" style="display: none">
            <div class="message-type d-flex flex-wrap">
                <div class="message-body">
                    <textarea style=" width: 100%;" id="message" name="message" placeholder="{{ MyHelpers::guest_trans('Type a message') }}"></textarea>
                </div>
                <div class="img-select mx-4 au-input-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span data-tooltip="بإمكانك إرفاق ملفاتك من هنا" id="afnan" class="afnan"> </span>
                    <label id="uploadIcon" for="file-upload" class="fas fa-paperclip" style="color: #f2f2f2;"></label>
                </div>
                <div class="dropdown-menu dropdown-menu">
                    <button class="dropdown-item upload_multimedia" data-type="image" type="button" name="image_action"><i class="zmdi zmdi-image"></i> صورة
                    </button>
                    <button class="dropdown-item upload_multimedia" data-type="file" type="button" name="file_action"><i class="zmdi zmdi-file"></i> ملف
                    </button>
                </div>
                <div class="message-send">
                    <button class="exp" id="msg">
                        ارسال
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('updateModel')
    @include('image.zoom')
@endsection
@section('scripts')
<script>
    $("textarea").keydown(function(e){
        // Enter was pressed without shift key
        if (e.key == 'Enter' && !e.shiftKey)
        {
            // prevent default behavior
            $('#msg').click();
            e.preventDefault();
        }
    });
    function FetchData() {
        $('#messages').load($('#messages').attr('data-href'));
    }
    setInterval(FetchData, 3000);
    var my_id = "{{ Auth::id() }}";
    $(document).ready(function() {
        $("html, body").animate({
            scrollTop: document.body.scrollHeight
        }, "slow");
        var SessionValue = document.getElementById("SessionValue").value;
        if (SessionValue == 1) {
            $("#uploadIcon").addClass('animateIcon');
            $("#afnan").fadeIn(1500);
            $("#afnan").fadeOut(4000);
        }
        $(document).on('click', '.upload_multimedia', function() {
            data_type = $(this).attr('data-type');
            if (data_type == 'file')
                $('#file_path').attr('accept', 'application/pdf, application/vnd.ms-excel');
            else
                $('#file_path').attr('accept', data_type + '\/*');
            $('#file_path').click();
            $('#message_type').val(data_type);
        });
        $(document).on('change', '#file_path', function() {
            $('#msg').click();
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#msg').click(function(e) {
            var message = $('#message').val();
            var message_type = "text";
            if ($('#file_path')[0].files.length == 1 && message == '') {
                message = $('#file_path')[0].files[0];
                message_type = $('#message_type').val();
            }
            var receivers = JSON.parse($('#receivers').val());
            var receiver_model_type = $('#receiver_model_type').val();
            var fD = new FormData();
            if (message != '' && receivers != '') {
                fD.append('message', message);
                fD.append('message_type', message_type);
                fD.append('receivers', receivers);
                fD.append('_token', "{{csrf_token()}}");
                fD.append('receiver_model_type', receiver_model_type);
                $.ajax({
                    type: "POST",
                    processData: false,
                    contentType: false,
                    url: "{{route('sendMessageFirebase')}}", // need to create this post route
                    data: fD,
                    beforeSend:function()
                    {
                        $("#msg").prop('disabled', true);
                        $('#process').css('display','block');
                    },
                    success: function(data) {
                        $("#msg").prop('disabled', false);
                        $('#process').css('display','none');
                        let base_url = window.location.origin;
                        let message_data = data.data;
                        var content = '  <div class="customer-ask d-flex float-right mt-4 ">';
                        content += '<div class="customer-ask-nav "> <div class="customer-ask-img"> <img src="{{ asset("newWebsiteStyle/images/555.png")}}"> </div>';
                        content += " <div class='customer-ask-time clearfix'> '{{ MyHelpers::guest_trans('Just Now') }}' </div>";
                        content += '<div class="customer-ask-message">';
                        if (message_data.message_type == 'text') {
                            content += message_data.text;
                        } else if (message_data.message_type == 'file') {
                            var assetBaseUrl = "{{ asset('') }}";
                            var openurl = base_url + '/' + 'openfilefirebase' + '/' + message_data.file;
                            var imgurl = assetBaseUrl + 'newWebsiteStyle/images/pdf-icon.png';
                            content += '<a href="' + openurl + '"  target="_blank">';
                            content += '<img src="' + imgurl + '" width="150" height="150"  alt="PDF file"/>';
                            content += '</a>';
                            var downurl = base_url + '/' + 'downloadfilefirebase' + '/' + message_data.file;
                            content += '<a href="' + downurl + '">';
                            content += ' <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans("Download") }}"></i></a>';
                        } else if (message_data.message_type == 'image') {
                            // console.log(message_data);
                            var url = base_url + '/' + 'storage/chat' + '/' + message_data.file;
                            content += '<a data-toggle="modal" data-target="#zoom_image" data-id="' + message_data.file + '" data-img="{{ url(' + url + ') }}" href="">';
                            content += '<img src="' + url + '" width="200" height="200" id="image_' + message_data.file + '" />';
                            content += '</a>';
                            var downurl = base_url + '/' + 'downloadfilefirebase' + '/' + message_data.file;
                            content += '<a href="' + downurl + '">';
                            content += ' <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans("Download") }}"></i></a>';
                        } else if (message_data.message_type == 'video') {
                            content += '    <video controls src="' + message_data.file + '" width="200" height="200" ></video> ';
                        }
                        content += '</div></div></div>';
                        content += '<div class="clearfix"></div>';
                        // content += '<div class="send-mess-list"><div class="send-mess">' + message + '</div></div></div></div>';
                        $('#messages').append(content);
                        $('#message').val(''); // while pressed enter text box will be empty
                        $('#file_path').val("");
                    },
                    error: function(jqXHR, status, err) {
                        $("#msg").prop('disabled', false);
                        console.log(err);
                    },
                    complete: function() {
                        $("#msg").prop('disabled', false);
                        $('#process').css('display','none');
                        $('#message').val(''); // while pressed enter text box will be empty
                        scrollToBottomFunc();
                    }
                })
            }
        });
    });
    function scrollToBottomFunc() {
        $("html, body").animate({
            scrollTop: document.body.scrollHeight
        }, "slow");
    }
    function hideMessage(id) {
        $.ajax({
            type: "GET",
            url: '/delete-message/' + id, // need to create this post route
            data: {},
            // cache: false,
            success: function(data) {
                console.log(data);
                $('#msg' + id).remove();
            },
            error: function(jqXHR, status, err) {
                alert(err);
            },
            complete: function() {
                scrollToBottomFunc();
            }
        })
    }
    $('#zoom_image').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        let id = button.data('id'); // Extract info from data-* attributes
        /* SET DATA TO MODAL */
        //console.log(id);
        var imgID = 'image_' + id;
        // Get the modal
        var modal = document.getElementById("zoom_image");
        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById(imgID);
        var modalImg = document.getElementById("zoomImage");
        img.onclick = function() {
        }
        modal.style.display = "block";
        modalImg.src = img.src;
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
    });
    /////////////////////////////////////////////////////////////////
</script>
@endsection
