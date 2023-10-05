@if(count($correspondents) > 0 )
    @foreach($correspondents as $user)
        @php
            $lastmsg= App\User::lastMessage($user->id);
        @endphp
        <form method="post" action="{{route('newChat')}}"  >
            @csrf
            <input type="hidden" name="receivers[]" value="{{ $user->id }}" />
            <input type="hidden" name="receiver_model_type" value="App\{{class_basename($user)}}" />
            <a id="{{ $user->id }}" onclick="$(this).closest('form').submit();" class="pointer list-group-item list-group-item-action list-group-item-light rounded-0 {{ ($user->unread) ? 'unread' : '' }}">
                <div class="media ">
                    <img src="" alt="{{ $user->name }}" width="50" class="rounded-circle">
                    <div class="media-body ml-4">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="mb-0">{{ $user->name }}</h6>
                            @if($user->unread)
                            <small class="small font-weight-bold badge badge-danger">
                                {{ $user->unread }}
                            </small>
                            @elseif($lastmsg)
                            <small class="small font-weight-bold">
                                <i class="fas fa-caret-square-down mr-1" title="#"></i>  {{@$lastmsg->created_at->translatedFormat('d-M')}}
                            </small>
                            @endif
                        </div>
                        @if ($lastmsg != null)
                        @if ($lastmsg->message_type == 'file')
                        <p class="font-italic text-muted mb-0 text-small user-msg-body"> مستند مُستلم</p>
                        @elseif ($lastmsg->message_type == 'image')
                        <p class="font-italic text-muted mb-0 text-small user-msg-body"> صورة مُستلمة</p>
                        @elseif ($lastmsg->message_type == 'video')
                        <p class="font-italic text-muted mb-0 text-small user-msg-body"> مقطع مُستلم</p>
                        @elseif ($lastmsg->message_type == 'text')
                        <p class="font-italic text-muted mb-0 text-small user-msg-body">{{substr($lastmsg->message,0,20)}}</p>
                        @else
                        <p class="font-italic text-muted mb-0 text-small user-msg-body"></p>
                        @endif
                        @else
                        <p class="font-italic text-muted mb-0 text-small user-msg-body"></p>
                        @endif
                    </div>
                </div>
            </a>
        </form>
    @endforeach
@endif
