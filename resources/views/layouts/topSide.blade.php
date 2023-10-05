@php
    $notifyWithoutReminders = $notifyWithoutReminders ?? collect();
    $notifyWithOnlyReminders  = $notifyWithOnlyReminders ?? collect();
    $notifyWithHelpdesk  = $notifyWithHelpdesk ?? collect();
    $received_task_count  = $received_task_count ?? 0;
    $unread_conversions  = $unread_conversions ?? 0;
    $unread_messages  = $unread_messages ?? collect();
@endphp
<div class="contHeader">
    <div class="headContAria">

        <header class="single-head user_head">

            <div class="user-nav container-fluid px-lg-5">

                <div class="UserNav-cont d-flex  ">

                    <div class="userName d-flex">

                        <div class="dropdown">

                            <button class="user_btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{auth()->user()->name}}
                            </button>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{route('profile')}}"> <i class="fas fa-user mr-3"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Profile') }}
                                </a>

                                @if(!session('user_is_switched'))
                                    <a class="dropdown-item logout" href="{{route('logout')}}"> <i class="fas fa-power-off mr-3"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Logout') }}
                                    </a>
                                @endif

                            </div>

                        </div>

                    </div>

                {{-- <div class="tableUserOption  flex-wrap ">

                    <form action="{{ url('/search_user_account') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="input-group col-md-12 mt-lg-0 mt-9">
                            <input class="form-control py-2" placeholder="ابحث هنا" name="search">
                            <span class="input-group-append">
                                <button class="btn btn-outline-info" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>

                </div> --}}

                <!-- <a href="{{ url('/search_user_account') }}" class="btn btn-xs btn-info pull-right"><i class="fa fa-search"></i></a> -->

                    <div class="userNote d-flex">

                        @if(session('user_is_switched'))
                            <div class="notifactions mr-4 not_bar tableAdminOption">
                            <span data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ MyHelpers::admin_trans(auth()->user()->id,'Back to your account') }}">
                                <a href="{{ route('switch.userRestore') }}" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Back to your account') }}" class="text-white">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </span>
                            </div>
                        @endif
                        @if (auth()->user() && in_array(auth()->user()->role,  [0,1,2,3,5,6]))

                            <div class="notifactions mr-4 not_bar ">
                        <span title="الدعم الفني" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa fa-comments" aria-hidden="true"></i>
                        </span>
                            </div>

                        @endif
                        @if (auth()->user()->role != '12')
                            {{--NOTIFICATION WITHOUT REMINDERS--}}
                            <div class="notifactions mr-4 not_bar notfi_call">
                                <i class="fas fa-plus-square"></i>
                                @if ($notifyWithoutReminders->where('recived_id', (auth()->user()->id))->whereIn('status', [0, 1])->count() > 0)
                                    <span class="note-msg">{{$notifyWithoutReminders->where('recived_id', (auth()->user()->id))->whereIn('status', [0, 1])->count()}}</span>
                            @endif
                            <!-- @if ($notifyWithoutReminders->count() > 0)
                                <span class="note-msg">{{$notifyWithoutReminders->count()}}</span>
                                @endif -->
                                <ul class="list-unstyled notfi_ul">
                                    @foreach (array_slice($notifyWithoutReminders->toArray(), 0, 3) as $notify)
                                        <li>
                                            <div class="single_Pop d-flex">
                                                @if ($notify->type == 0)
                                                    <div class="popIcon">
                                                        <i class="fas fa-file-medical"></i>
                                                    </div>

                                                @elseif ($notify->type == 1)
                                                    <div class="goldIcon">
                                                        <i class="fas fa-lightbulb"></i>
                                                    </div>
                                                @elseif ($notify->type == 3)
                                                    <div class="greenIcon">
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                @elseif ($notify->type == 5)
                                                    <div class="darkRed Icon">
                                                        <button data-notify="{{json_encode($notify,JSON_UNESCAPED_UNICODE)}}" class="notify-modal-btn mr-2 py-2 rounded-circle btn btn-info btn-sm" title="تفاصيل سريعة">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                        {{--                                                        <i class="fas fa-exclamation-circle"></i>--}}
                                                    </div>
                                                @elseif ($notify->type == 6)
                                                    <div class="silverIcon">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </div>
                                                @elseif ($notify->type == 20)
                                                    <div class="orangIcon">
                                                        <i class="fas fa-calculator"></i>
                                                    </div>

                                                @elseif ($notify->type == 10)
                                                    <div class="orangIcon">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                @endif
                                                @if ($notify->type != 20)
                                                    @if(auth()->user()->role != 8 && auth()->user()->role != 7 )
                                                        {{-- Service evaluation for the customer --}}
                                                        @if ($notify->type == 10 && auth()->user()->role != 5)
                                                            <div class="popCont">
                                                                <a href="{{ route('proper.property.request',$notify->req_id) }}?not_id={{$notify->id}}">
                                                                    {{$notify->value}}
                                                                </a>
                                                            </div>
                                                        @elseif ($notify->type == 10 && auth()->user()->role == 5)
                                                            <div class="popCont">
                                                                <a href="{{ route('all.reqHistory',$notify->req_id) }}">
                                                                    {{$notify->value}}
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="popCont">
                                                                <a href="{{ route('all.openNotify', ['id'=>$notify->req_id, 'notify'=>$notify->id] ) }}">
                                                                    {{$notify->value}}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @elseif(auth()->user()->role == 7 && $notify->type != 8)
                                                        <div class="popCont">
                                                            <a href="{{ route('all.notification') }}">
                                                                {{$notify->value}}
                                                            </a>
                                                        </div>
                                                    @elseif(auth()->user()->role == 8 && auth()->user()->accountant_type == 0)
                                                        <div class="popCont">
                                                            <a href="{{ route('report.tsaheelAccountingReportWithNotifiy' ) }}">
                                                                {{$notify->value}}
                                                            </a>
                                                        </div>
                                                    @elseif(auth()->user()->role == 8 && auth()->user()->accountant_type == 1)
                                                        <div class="popCont">
                                                            <a href="{{ route('report.wsataAccountingReportWithNotifiy' ) }}">
                                                                {{$notify->value}}
                                                            </a>
                                                        </div>

                                                    @endif
                                                @else
                                                    @if(auth()->user()->role ==7)
                                                        <div class="popCont">
                                                            <a href="{{ route('admin.suggestions.index') }}?notify={{$notify->id}}">
                                                                {{$notify->value}}
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="popCont">
                                                            <a href="{{ route('all.suggestions.index') }}?notify={{$notify->id}}">
                                                                {{$notify->value}}
                                                            </a>
                                                        </div>
                                                    @endif


                                                @endif

                                                {{--                                                @if($notify->type == 5)--}}
                                                {{--                                                    <div>--}}
                                                {{--                                                        <button class="btn btn-info btn-sm" title="تفاصيل سريعة">--}}
                                                {{--                                                            <i class="fa fa-eye"></i>--}}
                                                {{--                                                        </button>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                @endif--}}
                                            </div>
                                        </li>
                                    @endforeach

                                    <li>
                                        <div class="all-note text-center">
                                            <a href="{{ route('all.notification') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All') }}
                                                {{ MyHelpers::admin_trans(auth()->user()->id,'Notifications') }}</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            {{--NOTIFICATION WITHOUT REMINDERS--}}

                            {{--REMINDERS--}}
                            <div class="notifactions mr-4 not_bar notf_call">
                                <i class="fas fa-bell"></i>
                                @if ($notifyWithOnlyReminders->count() > 0)
                                    <span class="note-msg">{{$notifyWithOnlyReminders->count()}}</span>
                                @endif
                                <ul class="list-unstyled note_ul">

                                    @foreach (array_slice($notifyWithOnlyReminders->toArray(), 0, 3) as
                                    $notify)
                                        <li>
                                            <div class="single_Pop d-flex">

                                                @if ($notify->type == 0)
                                                    <div class="popIcon">
                                                        <i class="fas fa-file-medical"></i>
                                                    </div>

                                                @elseif ($notify->type == 1)
                                                    <div class="goldIcon">
                                                        <i class="fas fa-lightbulb"></i>
                                                    </div>
                                                @elseif ($notify->type == 3)
                                                    <div class="greenIcon">
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                @elseif ($notify->type == 5)
                                                    <div class="darkRedIcon">
                                                        <i class="fas fa-exclamation-circle"></i>
                                                    </div>
                                                @elseif ($notify->type == 6)
                                                    <div class="silverIcon">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </div>
                                                @endif

                                                @if(auth()->user()->role != 8 && auth()->user()->role != 7 )
                                                    <div class="popCont">
                                                        <a href="{{ route('all.openNotify', ['id'=>$notify->req_id, 'notify'=>$notify->id] ) }}">
                                                            {{$notify->value}}
                                                        </a>
                                                    </div>
                                                @elseif(auth()->user()->role == 7 && $notify->type != 8)
                                                    <div class="popCont">
                                                        <a href="{{ route('all.notification') }}">
                                                            {{$notify->value}}
                                                        </a>
                                                    </div>
                                                @elseif(auth()->user()->role == 8 && auth()->user()->accountant_type
                                                == 0)
                                                    <div class="popCont">
                                                        <a href="{{ route('report.tsaheelAccountingReportWithNotifiy' ) }}">
                                                            {{$notify->value}}
                                                        </a>
                                                    </div>
                                                @elseif(auth()->user()->role == 8 && auth()->user()->accountant_type
                                                == 1)
                                                    <div class="popCont">
                                                        <a href="{{ route('report.wsataAccountingReportWithNotifiy' ) }}">
                                                            {{$notify->value}}
                                                        </a>
                                                    </div>

                                                @endif

                                            </div>
                                        </li>
                                    @endforeach

                                    <li>
                                        <div class="all-note text-center">
                                            <a href="{{ route('all.notification') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All') }}
                                                {{ MyHelpers::admin_trans(auth()->user()->id,'Notifications') }}</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            @if (auth()->user()->role != 6)
                            {{--TASKS--}}
                            <div class="notifactions mr-4 not_bar msg_call">
                                <i class="fas fa-comment-alt"></i>
                                @if($received_task_count+$notifyWithHelpdesk->count() > 0)
                                    <span class="note-msg">{{  $received_task_count+$notifyWithHelpdesk->count() }}</span>
                                @endif
                                <ul class="list-unstyled msg_ul">

                                    @if( $received_task_count > 0 || $notifyWithHelpdesk->count() > 0)
                                        @foreach($task->take(2) as $item)
                                            <li>
                                                <div class="single_Pop d-flex">

                                                    <div class="pinkIcon">
                                                        <i class="fas fa-comment-alt"></i>
                                                    </div>

                                                    <div class="popCont">
                                                        <a href="{{ route('all.show_users_task' , $item->id )}}">
                                                            مهمة جديدة تمت إضافتها
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                        @foreach($task_contents->take(2) as $task_content)
                                            <li>
                                                <div class="single_Pop d-flex">

                                                    <div class="greenIcon">
                                                        <i class="fas fa-comments"></i>
                                                    </div>

                                                    <div class="popCont">
                                                        <a href="{{ route('all.show_users_task' , $task_content->id )}}">
                                                            يوجد رد جديد على التذكرة
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                        @foreach($notifyWithHelpdesk->take(2) as $helpDesk)
                                            <li>
                                                <div class="single_Pop d-flex">

                                                    <div class="darkBlueIcon">
                                                        <i class="fas fa-cog"></i>
                                                    </div>

                                                    <div class="popCont">
                                                        @if (auth()->user()->role == '7')
                                                            <a href="{{ url('admin/openhelpDeskPage/'.$helpDesk->req_id )}}">
                                                                {{$helpDesk->value}}
                                                            </a>
                                                        @else
                                                            <a href="{{ url('all/openhelpDeskPage/'.$helpDesk->req_id )}}">
                                                                {{$helpDesk->value}}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif

                                    <li>
                                        <div class="all-note text-center">
                                            @if (auth()->user()->role == '5')
                                                <a style="border-bottom: 1px solid; padding:5px 0 ;" href="{{ route('quality.manager.mytask') }}">فتح جميع التذاكر <span class="note-msg" style="position: unset; display: inline-block;">{{  $received_task_count }}</span></a>
                                            @else
                                                {{-- <a style="border-bottom: 1px solid; padding:5px 0 ;" href="{{ route('all.recivedtask') }}">فتح جميع التذاكر</a> --}}
                                                <a style="border-bottom: 1px solid; padding:5px 0 ;" href="{{ route('all.notifytasks') }}">فتح جميع التذاكر <span class="note-msg" style="position: unset; display: inline-block;">{{  $received_task_count }}</span></a>
                                            @endif
                                        </div>
                                    </li>
                                    @if($notifyWithHelpdesk->count() > 0)
                                        <li>
                                            <div class="all-note text-center">
                                                <a style="border-bottom: 1px solid; padding:5px 0 ;" href="{{ route('all.notifyhelpdesk') }}">فتح تنبيهات الدعم الفني <span class="note-msg" style="position: unset; display: inline-block;">{{ $notifyWithHelpdesk->count() }}</span></a>
                                            </div>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                            {{--TASKS--}}
                            @endif


                            {{--
    @if (auth()->user()->role == '0')
                            <div class="notifactions mr-4 not_bar client_message_call" title="تواصل مع العملاء">
                                <i class="fas fa-comments"></i>
                                    @if($count_unread_message_from_client > 0)
                                        <span class="note-msg">{{ $count_unread_message_from_client }}</span>
                                    @endif
                                    <ul class="list-unstyled message_ul_client" style="overflow-y: scroll; height:300px;">
                                        @php
                                            $senders= [];
                                        @endphp
                                        @if(count($get_all_unread_messages) != 0)
                                            @foreach($get_all_unread_messages as $message)
                                                @if(! in_array($message['senderId'] ,$senders) )
                                                    <form method="post" action="{{route('chatClientInbox')}}">
                                                        @csrf
                                                        <input type="hidden" name="receivers[]" value="{{ $message['senderId'] }}" />
                                                        @if ($message['from_type'] == 'App\customer')
                                                            <input type="hidden" name="receiver_model_type" value="App\customer" />
                                                        @endif
                                                        <li>
                                                            <div class="single_Pop d-flex" onclick="$(this).closest('form').submit();">
                                                                <div class="popIcon">
                                                                        <img src="{{ asset('interface_style/images/customer-icon.png')}}" alt="عميل" width="35" />
                                                                </div>
                                                                <div class="popCont" style="padding-right: 7px">
                                                                        <b> العميل : {{ $message['senderName'] }}</b>
                                                                    @if ($message['message_type'] == 'file')
                                                                        <p> مستند مُستلم</p>
                                                                    @elseif ($message['message_type'] == 'image')
                                                                        <p> صورة مستلمة</p>
                                                                    @elseif ($message['message_type'] == 'video')
                                                                        <p> مقطع مستلم</p>
                                                                    @else
                                                                        <p>{{$message['text']}}</p>
                                                                    @endif
                                                                    <small class="time">
                                                                        <i class="fas fa-click-o"></i>
                                                                        {{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </form>
                                                @endif
                                                @php
                                                    array_push($senders , $message['senderId']);
                                                @endphp
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                @endif


                                --}}


                            @if (auth()->user()->role != '13' && auth()->user()->role != 6)
                            {{--MESSAGE--}}
                            <div class="notifactions not_bar mail_call" title="الرسائل">
                                <i class="fas fa-envelope"></i>
                                @if ($unread_conversions > 0)
                                    <span class="note-msg">{{$unread_conversions}}</span>
                                @endif
                                <ul class="list-unstyled mail_ul" style="overflow-y: scroll; height:300px;">
                                    @php
                                        $senders= [];
                                    @endphp
                                    @if(count($unread_messages) != 0)
                                        @foreach($unread_messages as $message)
                                            @if(! in_array($message->from ,$senders) )
                                                <form method="post" action="{{route('newChat')}}">
                                                    @csrf
                                                    <input type="hidden" name="receivers[]" value="{{ $message->from }}"/>
                                                    @if ($message->from_type == 'App\customer')
                                                        <input type="hidden" name="receiver_model_type" value="App\customer"/>
                                                    @endif
                                                    <li>
                                                        <div class="single_Pop d-flex" onclick="$(this).closest('form').submit();">
                                                            <div class="popIcon">
                                                                @if ($message->from_type != 'App\customer')
                                                                    <img src="{{ @$message->sender->avatar }}" alt="{{ @$message->sender->name }}" width="35"/>
                                                                @else
                                                                    <img src="{{ asset('interface_style/images/customer-icon.png')}}" alt="عميل" width="35"/>
                                                                @endif
                                                            </div>
                                                            <div class="popCont" style="padding-right: 7px">
                                                                @if ($message->from_type != 'App\customer')
                                                                    <b>{{ @$message->sender->name }}</b>
                                                                @else
                                                                    <b> العميل : {{ @$message->senderCustomer->name }}</b>
                                                                @endif
                                                                @if ($message->message_type == 'file')
                                                                    <p> مستند مُستلم</p>
                                                                @elseif ($message->message_type == 'image')
                                                                    <p> صورة مستلمة</p>
                                                                @elseif ($message->message_type == 'video')
                                                                    <p> مقطع مستلم</p>
                                                                @else
                                                                    <p>{{$message->message}}</p>
                                                                @endif
                                                                <small class="time">
                                                                    <i class="fas fa-click-o"></i>
                                                                    {{ $message->created_at->diffForHumans() }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </form>
                                            @endif
                                            @php
                                                array_push($senders , $message->from);
                                            @endphp
                                        @endforeach
                                    @endif
                                    <li>
                                        <div class="all-note text-center">
                                            <a href="{{route('chat')}}">{{ MyHelpers::admin_trans(auth()->user()->id,'View all messages') }}</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            {{--MESSAGE--}}
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </header>
    </div>
    <div data-backdrop="static" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="notify-modal">
        <div class="modal-dialog modal-lg modal-dialog-centered " role="document" data-backdrop="static">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">بيانات الطلب</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="notify-modal-loading"> جار جلب البيانات...</div>
                    <div id="notify-modal-content">
                        <div class="container">
                            <div class="row" id="rows-container"></div>
                            <div class="row my-2">
                                <div class="col-12">
                                    <h4>تتبع الطلب</h4>
                                </div>
                            </div>
                            <div class="row" id="history-container"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="modal-btn-no7" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    {{--                    <button type="button" id="submitMove" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Move') }}</button>--}}
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        window.notifyModal = null

        $(document).on('click', '.notify-modal-btn', function (e) {
            window.notifyModal = JSON.parse($(this).attr('data-notify'));
            $('#notify-modal').modal();
        })

        $('#notify-modal')
            .on('hidden.bs.modal', function (e) {
                $("#notify-modal-loading").addClass('d-none');
                $("#notify-modal-content").addClass('d-none');
                $("#rows-container").html('');
                $("#history-container").html('');
                window.notifyModal = null
            })
            .on('show.bs.modal', function (e) {
                const loading = $("#notify-modal-loading");
                const content = $("#notify-modal-content");
                loading.removeClass('d-none');
                content.addClass('d-none');
                setTimeout(() => {
                    if (!window.notifyModal) {
                        alertError("حدث خطأ الرجاء المحاولة لاحقا")
                        return;
                    }
                    const n = window.notifyModal;
                    $.get("{{route('getRequestInfo')}}", {request_id: n.req_id})
                        .done(r => {
                            loading.addClass('d-none');
                            content.removeClass('d-none');
                            const rows = r.rows || [];
                            let historyRows = r.historyRows || [];
                            const rowsContainer = $("#rows-container");
                            const historyContainer = $("#history-container");
                            $.each(rows, (i, e) => {
                                $(`<div class="col-12 col-md-6"><span>-${e.text}: </span><span>${e.value} </span></div>`).appendTo(rowsContainer)
                            })
                            // console.log(historyRows)
                            // historyRows = historyRows.sort( (a,b) => b.date - a.date )
                            $.each(historyRows, (i, e) => {
                                $(`<div class="col-12 mb-2"><span class="font-weight-bold">${e.date} </span><span>${e.text}: </span><span>${e.value} </span></div>`).appendTo(historyContainer)
                            })
                        })
                        .catch(e => {
                            alertError(e)
                        })
                }, 1000)
            })
    </script>
@endpush
