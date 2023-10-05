@if(count($users) > 0 )
    @foreach($users as $key => $value)
        @php
            $lastmsg = \App\Http\Controllers\MessageController::getAllMessagesWhereCustomer($value['0']);
        @endphp
        <form method="post" action="{{route('chatClientInbox')}}"  >
            @csrf
            <input type="hidden" name="receivers[]" value="{{ $value['0'] }}" />
            <input type="hidden" name="receiver_model_type" value="App\User" />
            @if(auth()->user()->name != $lastmsg['senderName'])
            <a id="{{ $value[0] }}" onclick="$(this).closest('form').submit();" class="pointer list-group-item list-group-item-action list-group-item-light rounded-0 {{ ($lastmsg['is_read'] == 0) ? 'unread' : '' }}">
                <div class="media {{ ($value[0]) ? 'online' : 'offline'}} ">
                    <img src="{{ asset('interface_style/images/customer-icon.png')}}" alt="عميل" width="50" class="rounded-circle" />
                    <div class="media-body ml-4">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="mb-0">{{ $lastmsg['senderName'] }}</h6>
                                @if($lastmsg['is_read'] == 0)
                                    <small class="small font-weight-bold badge badge-danger">
                                        {{ $lastmsg['text'] }}
                                    </small>
                                @elseif($lastmsg['is_read'] == 1)
                                    <small class="small font-weight-bold">
                                        <i class="fas fa-caret-square-down mr-1" title="#"></i>  {{@  \Carbon\Carbon::parse($lastmsg['created_at'])->diffForHumans() }}
                                    </small>
                                @endif
                        </div>
                            @if ($lastmsg['message_type'] == 'file')
                                <p class="font-italic text-muted mb-0 text-small user-msg-body"> مستند مُستلم</p>
                            @elseif ($lastmsg['message_type'] == 'image')
                                <p class="font-italic text-muted mb-0 text-small user-msg-body"> صورة مُستلمة</p>
                            @elseif ($lastmsg['message_type'] == 'video')
                                <p class="font-italic text-muted mb-0 text-small user-msg-body"> مقطع مُستلم</p>
                            @elseif ($lastmsg['message_type'] == 'text')
                                <p class="font-italic text-muted mb-0 text-small user-msg-body">{{substr($lastmsg['text'],0,20)}}</p>
                            @else
                                <p class="font-italic text-muted mb-0 text-small user-msg-body"></p>
                            @endif
                    </div>
                </div>
            </a>
            @endif
        </form>
    @endforeach
@endif
