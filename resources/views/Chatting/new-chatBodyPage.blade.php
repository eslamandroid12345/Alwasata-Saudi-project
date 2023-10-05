                                 
 @if(count($messages ) != 0 )
 @foreach($messages as $message)
 @if($message->from == auth()->user()->id && $message->from_type == $model_type)
 @if($message->from_is_show == 1)
 <div class="{{$message->to == auth()->user()->id ? 'recei' : 'send'}}-mess-wrap" id="msg{{$message->id}}">
     <span class="mess-time">{{ $message->created_at->diffForHumans() }}</span>
     <div class="{{$message->to == auth()->user()->id ? 'recei' : 'send'}}-mess__inner">
         @if($message->from != auth()->user()->id)

         @if ($message->from_type != 'App\customer')
         <img src="{{ @$message->sender->avatar }}" alt="{{ @$message->sender->name }}" />
         @else
         <img src="{{ asset('interface_style/images/customer-icon.png')}}" alt="عميل" />
         @endif

         @endif
         <div class="{{$message->to == auth()->user()->id ? 'recei' : 'send'}}-mess-list">
             <div class="{{$message->to == auth()->user()->id ? 'recei' : 'send'}}-mess">


                 @if($message->message_type == 'text')
                 {{ $message->message }}

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

             </div>
             <input type="hidden" name="message_id" id="message_id" value="{{$message->id}}">
             {{--<span onclick="hideMessage({{$message->id}});"> <i class="fa fa-trash"></i> </span>--}}
         </div>
     </div>
 </div>
 @endif
 @elseif($message->to == auth()->user()->id && $message->to_type == $model_type)
 @if($message->to_is_show == 1)
 <div class="{{$message->to == auth()->user()->id ? 'recei' : 'send'}}-mess-wrap" id="msg{{$message->id}}">
     <span class="mess-time">{{ $message->created_at->diffForHumans() }}</span>
     <div class="{{$message->to == auth()->user()->id ? 'recei' : 'send'}}-mess__inner">
         @if($message->from != auth()->user()->id)
         <div class="avatar avatar--tiny">
             @if ($message->from_type != 'App\customer')
             <img src="{{ @$message->sender->avatar }}" alt="{{ @$message->sender->name }}" />
             @else
             <img src="{{ asset('interface_style/images/customer-icon.png')}}" alt="عميل" />
             @endif
         </div>
         @endif
         <div class="{{$message->to == auth()->user()->id ? 'recei' : 'send'}}-mess-list">
             <div class="{{$message->to == auth()->user()->id ? 'recei' : 'send'}}-mess">


                 @if($message->message_type == 'text')
                 {{ $message->message }}

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
             </div>
             <input type="hidden" name="message_id" id="message_id" value="{{$message->id}}">
             {{--<span onclick="hideMessage({{$message->id}});"> <i class="fa fa-trash"></i> </span>--}}
         </div>
     </div>
 </div>
 @endif
 @endif
 @endforeach
 @endif
