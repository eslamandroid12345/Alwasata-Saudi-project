@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Messages Page') }}
@endsection

@section('css_style')
    <link rel="stylesheet" href="{{asset('assest/css/chatStyle.css')}}">
    <style>
        .downloadIcon {
            color: black;
            font-size: x-large;
            padding: 3%;
            cursor: pointer;
        }

        .reqlink {
            color: blue;

        }

        .reqlink:hover {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }

        .bg-blue{
            background-color: #0f5b94;
        }
    </style>

@endsection

@section('customer')

    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>


    <!-- For demo purpose-->
    <header class="text-center">
        <h1 class="display-4 text-white">{{ MyHelpers::admin_trans(auth()->user()->id,'Chat') }}</h1>
        <br>
    </header>


    <div class="row rounded-lg overflow-hidden  ">


        <!-- Chat Box-->
        <div class="col-12 col-md-8 offset-md-2 px-0">
            <!--Back arrow-->
            <div class="tableAdminOption">
                <span data-toggle="tooltip" data-placement="top" title="" data-original-title="رجوع">
                    <a href="{{route('chat')}}">
                        <i class="fas fa-arrow-alt-circle-right"></i> </a>
                </span>
            </div>
            <!--Back arrow-->
            <br>

            @if(count($users)==1)
                <div class="lastSeenUser text-left px-3 text-white bg-info py-1">
                    @foreach($users as $user)
                        <span class="nick">
                        <strong>{{$user->name}}</strong>
                        @if($receiver_model_type == 'App\User')
                                <p>{{ in_array($user->id,$arrUsers) ? 'متصل' : $user->login_time}}</p>
                            @else
                                <p>{{ in_array($user->id,$onlineCustomers) ? 'متصل' : $user->login_time}}</p>
                                @php $reqInfo= App\customer::customerRequest($user->id) @endphp
                                @if ($reqInfo != null)
                                    @if ($reqInfo->type == null || $reqInfo->type == 'شراء' || $reqInfo->type == 'رهن')
                                        <a href="{{route('agent.fundingRequestFromMsg',$reqInfo->id)}}" class="reqlink">فتح الطلب</a>
                                    @else
                                        <a href="{{route('agent.morPurRequest',$reqInfo->id)}}" class="reqlink">فتح الطلب</a>
                                    @endif
                                @else
                                    <p>الطلب مُعلق</p>
                                @endif
                            @endif
                        </span>
                    @endforeach
                </div>
            @else
                <div class="lastSeenUser text-left px-3 text-white bg-info py-1">
                    @foreach($users as $user)
                        <b class="badge badge-light">{{$user->name}}</b>
                    @endforeach
                </div>
            @endif


            <div class="px-4 py-5 chat-box bg-white ">
                @php $recive = count($messages ) != 0 ? $messages[0]->from : null @endphp
                <div class="message-wrapper" >
                    <div class="px-4 py-5 msg_history messages"  id="messages_box">
                    @if(count($messages ) != 0 )
                        @foreach($messages as $message)
                            @if($message->from == auth()->user()->id && $message->from_type == $model_type)
                                @if($message->from_is_show == 1)
                                    <!-- Sender Message-->
                                        <div class="media w-50 mb-3 message">
                                            @if($message->from != auth()->user()->id)
                                                @if ($message->from_type != 'App\customer')
                                                    <img src="{{ @$message->sender->avatar }}" alt="{{ @$message->sender->name }}" alt="user" width="50" class="rounded-circle" />
                                                @else
                                                    <img src="{{ asset('interface_style/images/customer-icon.png')}}" alt="عميل" width="50" class="rounded-circle" />
                                                @endif
                                            @endif
                                            <div class="media-body ml-3">
                                                <div class="bg-light rounded py-2 px-3 mb-2">

                                                    @if($message->message_type == 'text')
                                                        <p class="">{{ $message->message }}</p>
                                                    @elseif($message->message_type == 'file')
                                                        <a href="{{ route('openFile',$message->id)}}" target="_blank">
                                                            <img src="{{ asset('newWebsiteStyle/images/pdf-icon.png')}}" width="150" height="150" alt="PDF file">
                                                        </a>

                                                        <br>

                                                        <a href="{{ route('downFile',$message->id)}}">
                                                            <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans('Download') }}"></i>
                                                        </a>

                                                        @if ($message->from_type == 'App\customer' && auth()->user()->role == 0)
                                                            <span id="{{$message->id}}" data-type="{{$message->from}}" class="addToReq">
                                                            <i class="fa fa-plus downloadIcon" aria-hidden="true" title="إضافته إلى مرفقات العميل"></i>
                                                        </span>
                                                        @endif

                                                    @elseif($message->message_type == 'image')
                                                        <a href="{{ route('openFile',$message->id)}}" target="_blank">
                                                            <img src="{{ url('storage/'.$message->message) }}" width="200" height="200" id="image_{{ $message->id }}" />
                                                        </a>

                                                        <br>

                                                        <a href="{{ route('downFile',$message->id)}}">
                                                            <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans('Download') }}"></i>
                                                        </a>


                                                        @if ($message->from_type == 'App\customer' && auth()->user()->role == 0)
                                                            <span id="{{$message->id}}" data-type="{{$message->from}}" class="addToReq">
                                                            <i class="fa fa-plus downloadIcon" aria-hidden="true" title="إضافته إلى مرفقات العميل"></i>
                                                        </span>
                                                        @endif

                                                    @elseif($message->message_type == 'video')
                                                        <video src="{{ $message->message }}" width="200" height="200" controls>
                                                        </video>
                                                    @endif

                                                    <input type="hidden" name="message_id" id="message_id" value="{{$message->id}}">
                                                </div>
                                                <p class="small text-muted">{{ $message->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                @endif
                            @elseif($message->to == auth()->user()->id && $message->to_type == $model_type)
                                @if($message->to_is_show == 1)
                                    <!-- Reciever Message-->
                                        <div class="media w-50 ml-auto mb-3 message">
                                            <div class="media-body">
                                                <div class="bg-primary rounded py-2 px-3 mb-2">
                                                    @if($message->message_type == 'text')
                                                        <p class="text-small mb-0 text-white">{{ $message->message }}</p>
                                                    @elseif($message->message_type == 'file')
                                                        <a href="{{ route('openFile',$message->id)}}" target="_blank">
                                                            <img src="{{ asset('newWebsiteStyle/images/pdf-icon.png')}}" width="150" height="150" alt="PDF file">
                                                        </a>

                                                        <br>

                                                        <a href="{{ route('downFile',$message->id)}}">
                                                            <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans('Download') }}"></i>
                                                        </a>


                                                        @if ($message->from_type == 'App\customer' && auth()->user()->role == 0)
                                                            <span id="{{$message->id}}" data-type="{{$message->from}}" class="addToReq">
                                                            <i class="fa fa-plus downloadIcon" aria-hidden="true" title="إضافته إلى مرفقات العميل"></i>
                                                        </span>
                                                        @endif

                                                    @elseif($message->message_type == 'image')
                                                        <a href="{{ route('openFile',$message->id)}}" target="_blank">
                                                            <img src="{{ url('storage/'.$message->message) }}" width="200" height="200" id="image_{{ $message->id }}" />
                                                        </a>

                                                        <br>

                                                        <a href="{{ route('downFile',$message->id)}}">
                                                            <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans('Download') }}"></i>
                                                        </a>


                                                        @if ($message->from_type == 'App\customer' && auth()->user()->role == 0)
                                                            <span id="{{$message->id}}" data-type="{{$message->from}}" class="addToReq">
                                                            <i class="fa fa-plus downloadIcon" aria-hidden="true" title="إضافته إلى مرفقات العميل"></i>
                                                        </span>
                                                        @endif


                                                    @elseif($message->message_type == 'video')
                                                        <video src="{{ $message->message }}" width="200" height="200" controls></video>
                                                    @endif

                                                    <input type="hidden" name="message_id" id="message_id" value="{{$message->id}}">
                                                </div>
                                                <p class="small text-muted">{{ $message->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif

                    </div>
                </div>

            </div>
            <!-- Typing area -->
            <div class="bg-light">
                <input type="hidden" name="receivers[]" value="{{ json_encode($receivers,TRUE)}}" id="receivers">
                <input type="hidden" name="receiver_model_type" value="{{ $receiver_model_type}}" id="receiver_model_type">

                <input type="hidden" name="message_type" value="text" id="message_type">
                <input type="file" name="file_path" id="file_path" style="display: none">
                <div class="input-group input-text">
                    <input name="message"  type="text" id="message"  class="form-control rounded-0 border-0 py-4 bg-light"  placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Type a message') }}">
                    <div class="input-group-append">
                        <button id="msg" class="btn btn-link msg_send_btn au-input-icon" onclick="javascript:sendMsg()"> <i class="fa fa-paper-plane"></i></button>
                        <button class="btn btn-link upload_multimedia" data-type="image" type="button" name="image_action"> <i class="fa fa-image"></i></button>
                        <button class="btn btn-link upload_multimedia" data-type="file" type="button" name="file_action"> <i class="fa fa-file"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection

@section('updateModel')
@include('image.zoom')
@include('Chatting.addToReqModel')
@endsection


@section('scripts')

<script>
    $( document ).ready(function() {
        console.log('ready');
        $('.ContTabelPage').removeClass('ContTabelPage').addClass( 'container py-5 px-4' );
        $('.homeCont').addClass( "noBG");

    });
$("#message").keyup(function(event) {
    if (event.keyCode === 13) {
        $("#msg").click();
    }
});
    function FetchData() {
        $('#messages').html();
       //scrollToBottomFunc();
    }
    setInterval(FetchData, 3000);
    function sendMsg(){
        console.log('msg send');
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
            fD.append('receiver_model_type', receiver_model_type);
            console.table('form table ', fD);
            $.ajax({
                type: "POST",
                processData: false,
                contentType: false,
                url: "{{route('sendMessage')}}", // need to create this post route
                data: fD,
                success: function(data) {
                    let base_url = window.location.origin;
                    let message_data = data.data;
                    var content = '';
                    content += '<div class="media w-50 mb-3 message">';
                    content += '<div class="media-body ml-3">';
                    content += '<div class="bg-light rounded py-2 px-3 mb-2"><p>';
                    if (message_data.message_type == 'text') {
                        content += message_data.message;
                    }
                    else if (message_data.message_type == 'file') {
                        var assetBaseUrl = "{{ asset('') }}";
                        var openurl = base_url + '/' + 'openfile' + '/' + message_data.id;
                        var imgurl = assetBaseUrl + 'newWebsiteStyle/images/pdf-icon.png';
                        content += '<a href="' + openurl + '"  target="_blank">';
                        content += '<img src="' + imgurl + '" width="150" height="150"  alt="PDF file"/>';
                        content += '</a>  <br>';
                        var downurl = base_url + '/' + 'downloadfile' + '/' + message_data.id;
                        content += '<a href="' + downurl + '">';
                        content += ' <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans("Download") }}"></i></a>';
                    }
                    else if (message_data.message_type == 'image') {
                        var assetBaseUrl = "{{ asset('') }}";
                        var openurl = base_url + '/' + 'openfile' + '/' + message_data.id;
                        var url = base_url + '/' + 'storage' + '/' + message_data.message;
                        content += '<a href="' + openurl + '"  target="_blank">';
                        content += '<img src="' + url + '" width="200" height="200" id="image_' + message_data.id + '" />';
                        content += '</a>  <br>';
                        var downurl = base_url + '/' + 'downloadfile' + '/' + message_data.id;
                        content += '<a href="' + downurl + '">';
                        content += ' <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans("Download") }}"></i></a>';
                    }
                    else if (message_data.message_type == 'video') {
                        content += '    <video controls src="' + message_data.message + '" width="200" height="200" ></video> ';
                    }
                    content += '</div></div></p>';
                    content += '<input type="hidden" name="message_id" id="message_id" value="'+message_data.id+'">';
                    content += '</div>';
                    content += "<p class='small text-muted'> '{{ MyHelpers::admin_trans(auth()->user()->id,'Just Now') }}' </p>";
                    // content += '<div class="send-mess-list"><div class="send-mess">' + message + '</div></div></div></div>';
                    $('#messages_box').append(content);
                    $('#message').val(''); // while pressed enter text box will be empty
                    $('#file_path').val("");
                },
                error: function(jqXHR, status, err) {
                    console.log(err);
                },
                complete: function() {
                    scrollToBottomFunc();
                }
            })
        }
    }
    var my_id = "{{ Auth::id() }}";
    $(document).ready(function() {

        scrollToBottomFunc();

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


        $('#msg').click(function() {

            console.log('msg send');
            var message = $('#message').val();
            var message_type = "text";

            if ($('#file_path')[0].files.length == 1 && message == '') {
                message = $('#file_path')[0].files[0];
                message_type = $('#message_type').val();
                //console.log('msg send file');

            }

            var receivers = JSON.parse($('#receivers').val());
            var receiver_model_type = $('#receiver_model_type').val();
            var fD = new FormData();

            // check if enter key is pressed and message is not null also receiver is selected
            if (message != '' && receivers != '') {
                //console.log('msg send file');

                fD.append('message', message);
                fD.append('message_type', message_type);
                fD.append('receivers', receivers);
                fD.append('receiver_model_type', receiver_model_type);
                console.table('form table ', fD);
                $.ajax({
                    type: "POST",
                    processData: false,
                    contentType: false,

                    url: "{{route('sendMessage')}}", // need to create this post route
                    data: fD
                        //  {
                        //     'receivers': receivers,
                        //     'receiver_model_type': receiver_model_type,
                        //     'message': message,
                        //     'message_type': message_type,
                        // }
                        ,
                    // cache: false,
                    success: function(data) {
                        let base_url = window.location.origin;
                        let message_data = data.data;
                        var content = '';
                        content += '<div class="media w-50 mb-3 message">';
                        content += '<div class="media-body ml-3">';
                        content += '<div class="bg-light rounded py-2 px-3 mb-2"><p>';
                        if (message_data.message_type == 'text') {
                            content += message_data.message;

                        }
                        else if (message_data.message_type == 'file') {
                            var assetBaseUrl = "{{ asset('') }}";
                            var openurl = base_url + '/' + 'openfile' + '/' + message_data.id;
                            var imgurl = assetBaseUrl + 'newWebsiteStyle/images/pdf-icon.png';
                            content += '<a href="' + openurl + '"  target="_blank">';
                            content += '<img src="' + imgurl + '" width="150" height="150"  alt="PDF file"/>';
                            content += '</a>  <br>';

                            var downurl = base_url + '/' + 'downloadfile' + '/' + message_data.id;
                            content += '<a href="' + downurl + '">';
                            content += ' <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans("Download") }}"></i></a>';

                        }
                        else if (message_data.message_type == 'image') {
                            var assetBaseUrl = "{{ asset('') }}";
                            var openurl = base_url + '/' + 'openfile' + '/' + message_data.id;
                            var url = base_url + '/' + 'storage' + '/' + message_data.message;
                            content += '<a href="' + openurl + '"  target="_blank">';
                            content += '<img src="' + url + '" width="200" height="200" id="image_' + message_data.id + '" />';
                            content += '</a>  <br>';

                            var downurl = base_url + '/' + 'downloadfile' + '/' + message_data.id;
                            content += '<a href="' + downurl + '">';
                            content += ' <i class="fa fa-download downloadIcon" aria-hidden="true" title="{{ MyHelpers::guest_trans("Download") }}"></i></a>';

                        }
                        else if (message_data.message_type == 'video') {
                            content += '    <video controls src="' + message_data.message + '" width="200" height="200" ></video> ';
                        }


                        content += '</div></div></p>';
                        content += '<input type="hidden" name="message_id" id="message_id" value="'+message_data.id+'">';
                        content += '</div>';
                        content += "<p class='small text-muted'> '{{ MyHelpers::admin_trans(auth()->user()->id,'Just Now') }}' </p>";

                        // content += '<div class="send-mess-list"><div class="send-mess">' + message + '</div></div></div></div>';
                        $('#messages').append(content);
                        $('#message').val(''); // while pressed enter text box will be empty
                        $('#file_path').val("");

                    },
                    error: function(jqXHR, status, err) {
                        console.log(err);
                    },
                    complete: function() {
                        scrollToBottomFunc();
                    }
                })
            }
        });
    });
    // make a function to scroll down auto
    function scrollToBottomFunc() {
        $('.messages').animate({
            scrollTop: $('.messages').get(0).scrollHeight
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
    $(document).on('click', '.addToReq', function() {

        var customerID = $(this).attr('data-type');
        var messageID = $(this).attr('id');

        $('#nameError').text('');
        document.getElementById("filename").value = "";
        $('#nameError').text('');
        $('#myModal1').modal('show');


        $(document).one('click', '#addToReqButton', function() {
            var nameFile = document.getElementById("filename").value;

            if (nameFile != '') {


                $.get("{{ route('addFile')}}", {
                        messageID: messageID,
                        customerID: customerID,
                        nameFile: nameFile,
                    },
                    function(data) {

                        // console.log(data);
                        if (data.status == 2)
                            $('#addError').text('العميل لديه طلب مُعلق');
                        else if (data.status == 0)
                            $('#addError').text('حدث خطأ ، حاول مجددا');
                        else {
                            $('#myModal1').modal('hide');
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>تم إضافته بنجاح");

                        }


                    })
            } else
                $('#nameError').text('الحقل مطلوب');

        });


    });
</script>

@endsection
