@php
    $all_reqs_count = $all_reqs_count ?? 0;
    $received_reqs_count = $received_reqs_count ?? 0;
    $com_reqs_count = $com_reqs_count ?? 0;
    $prepay_reqs_count = $prepay_reqs_count ??  0;
    $mor_pur_reqs_count = $mor_pur_reqs_count ??  0;
    $agent_received_reqs_count = $agent_received_reqs_count ?? 0;
    $follow_reqs_count = $follow_reqs_count ?? 0;
    $star_reqs_count = $star_reqs_count ?? 0;
    $arch_reqs_count = $arch_reqs_count ?? 0;
    $pending_request_count = $pending_request_count ?? 0;
    $need_action_request_count = $need_action_request_count ?? 0;
    $sent_task_count = $sent_task_count ?? 0;
    $received_task_count = $received_task_count ?? 0;
    $completed_task_count = $completed_task_count ?? 0;
    $calculator_suggests = $calculator_suggests ?? 0;
    $onlineUsers = $onlineUsers ?? [];
    $freesCount = $freesCount ?? 0;
    $need_turned_requests = $need_turned_requests ?? 0;
    $need_turned_done_requests = $need_turned_done_requests ?? 0;
@endphp

<div class="sidePar ">
    <div class="logo-aria d-flex align-items-center  ">
        <img src="{{ url('assest/images/logo.png') }}" alt="">
        <div class="toogle ml-auto">
            <i class="fas fa-exchange-alt"></i>
        </div>
    </div>
    <div class="NavCont">
        <ul class="list-unstyled">
            @if(auth()->user()->role == 13)
            @php
                    $wasata_received_reqs_count = \DB::table('wasata_requestes')->where('user_id', auth()->id())->where('req_status', '1')->count();
                    $wasata_arch_reqs_count = \DB::table('wasata_requestes')->where('user_id', auth()->id())->where('req_status', '0')->count();

            @endphp
            <li class="dropdown">
                <div>
                    {{-- <a href="{{ route('V2.BankDelegate.sended') }}"> --}}
                    <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <span><i class="fas fa-users" style="color: sandybrown;font-size:large;"></i></span>
                         مستلمة من الوساطة
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                        <a class="dropdown-item" href="{{ route('V2.BankDelegate.sended', 'active') }}">الطلبات النشطة
                            <span class="Red">{{$wasata_received_reqs_count}}</span>
                        </a>
                        <a class="dropdown-item"
                        href="{{ route('V2.BankDelegate.sended', 'archived') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
                            <span class="Silver">{{$wasata_arch_reqs_count}}</span>
                        </a>
                    </div>
                    </div>
            </li>

                {{-- <li>
                     <a href="{{ route('V2.ExternalCustomer.requestes-of-wasata')}}">
                         <span> <i class="far fa-copy" style="color:royalblue;font-size:large;"></i> </span>
                         طلبات الوساطة
                     </a>
                 </li>--}}

                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span><i class="fas fa-users" style="color: sandybrown;font-size:large;"></i></span>
                            مرسلة إلى الوساطة
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item"
                               href="{{ route('V2.BankDelegate.customer.create') }}">

                                اضافه عميل الى الوساطه
                                {{--  <span class="DarkBlue"><i class="fa fa-plus"></i></span>--}}
                            </a>
                            <hr>
                            <a class="dropdown-item"
                               href="{{ route('V2.BankDelegate.requests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
                                <span class="DarkBlue">{{$all_reqs_count}}</span></a>
                            <a class="dropdown-item" href="{{ route('V2.BankDelegate.actives') }}">الطلبات النشطة
                                <span class="Red">{{$received_reqs_count}}</span></a>
                            <a class="dropdown-item"
                               href="{{ route('V2.BankDelegate.archives') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
                                <span class="Silver">{{$arch_reqs_count}}</span></a>


                        </div>
                    </div>
                </li>
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span><i class="fas fa-users" style="color:sandybrown;font-size:large;"></i></span>
                            طلباتي الخاصة
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item"
                               href="{{ route('V2.BankDelegate.external.customer.create') }}">
                                اضافة إلى طلباتى الخاصة
                            </a>
                            <hr>
                            <a class="dropdown-item" href="{{ route('V2.ExternalCustomer.index') }}">
                                @lang('global.customers_list')
                            </a>

                            <a class="dropdown-item" href="{{ route('V2.ExternalCustomer.Archive.index') }}">
                                @lang('global.archived_customers')
                            </a>
                        </div>
                    </div>
                </li>
                {{-- <li class="dropdown">
                     <div>
                         <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             <span><i class="fas fa-users" style="color:sandybrown;font-size:large;"></i></span>
                             @lang('global.customers')
                         </a>

                         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                             <a class="dropdown-item" href="{{ route('V2.ExternalCustomer.index') }}">
                                 @lang('global.customers_list')
                             </a>

                             <a class="dropdown-item" href="{{ route('V2.ExternalCustomer.Archive.index') }}">
                                 @lang('global.archived_customers')
                             </a>
                         </div>
                     </div>
                 </li>--}}
{{--
                <li>
                    <a href="{{ route('reminders') }}">
                        <span> <i class="far fa-calendar-minus" style="color: brown;font-size:large;"></i> </span>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Reminders') }}
                    </a>
                </li>--}}

            @endif


            @if (auth()->user()->role == '0'||auth()->user()->role == '1' || auth()->user()->role == '2' || auth()->user()->role == '3' || auth()->user()->role == '4' || auth()->user()->role == '5' || auth()->user()->role == '7'  || auth()->user()->role == '11'|| auth()->user()->role == '6' )
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="far fa-copy" style="color:royalblue;font-size:large;"></i> </span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }} </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            {{--ALL REQUEST--}}
                            @if (auth()->user()->role == '0')
                                <a class="dropdown-item"
                                   href="{{ route('agent.myRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
                                    <span class="DarkBlue">{{$all_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '1')
                                <a class="dropdown-item"
                                   href="{{ route('sales.manager.myRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
                                    <span class="DarkBlue">{{$all_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '2')
                                <a class="dropdown-item"
                                   href="{{ route('funding.manager.myRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
                                    <span class="DarkBlue">{{$all_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '3')
                                <a class="dropdown-item"
                                   href="{{ route('mortgage.manager.myRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
                                    <span class="DarkBlue">{{$all_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '4')
                                <a class="dropdown-item"
                                   href="{{ route('general.manager.myRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
                                    <span class="DarkBlue">{{$all_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '5' || auth()->user()->role == '9')
                                <a class="dropdown-item"
                                   href="{{ route('quality.manager.myRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
                                    <span class="DarkBlue">{{$all_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '11')
                                <a class="dropdown-item"
                                   href="{{ route('training.myRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
                                    <span class="DarkBlue">{{$all_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '7')
                                <a class="dropdown-item"
                                   href="{{ route('admin.myRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
                                    <span class="DarkBlue">{{$all_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '6')
                                <a class="dropdown-item"
                                   href="{{ route('proper.requests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
                                    <span class="DarkBlue">{{$all_reqs_count}}</span></a>
                                <a class="dropdown-item" href="{{ route('proper.actives') }}">الطلبات النشطة
                                    <span class="Red">{{$received_reqs_count}}</span></a>
                                <a class="dropdown-item"
                                   href="{{ route('proper.archives') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
                                    <span class="Silver">{{$arch_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '13')
                                <a class="dropdown-item"
                                   href="{{ route('V2.BankDelegate.requests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
                                    <span class="DarkBlue">{{$all_reqs_count}}</span></a>
                                <a class="dropdown-item" href="{{ route('V2.BankDelegate.actives') }}">الطلبات النشطة
                                    <span class="Red">{{$received_reqs_count}}</span></a>
                                <a class="dropdown-item"
                                   href="{{ route('V2.BankDelegate.archives') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
                                    <span class="Silver">{{$arch_reqs_count}}</span></a>
                            @endif

                            {{--ALL REQUEST--}}



                            {{--RECIVED REQUEST--}}
                            @if (auth()->user()->role == '0')
                                <a class="dropdown-item"
                                   href="{{ route('agent.recivedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }}
                                    <span class="Red">{{$received_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '1')
                                <a class="dropdown-item"
                                   href="{{ route('sales.manager.recivedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }}
                                    <span class="Red">{{$received_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '2')
                                <a class="dropdown-item"
                                   href="{{ route('funding.manager.recivedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }}
                                    <span class="Red">{{$received_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '3')
                                <a class="dropdown-item"
                                   href="{{ route('mortgage.manager.recivedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }}
                                    <span class="Red">{{$received_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '4')
                                <a class="dropdown-item"
                                   href="{{ route('general.manager.recivedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }}
                                    <span class="Red">{{$received_reqs_count}}</span></a>

                            @elseif (auth()->user()->role == '5')
                                <a class="dropdown-item"
                                   href="{{ route('quality.manager.recivedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }}
                                    <span class="Red">{{$received_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '11')
                                <a class="dropdown-item"
                                   href="{{ route('training.recivedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }}
                                    <span class="Red">{{$received_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '7')
                                <a class="dropdown-item"
                                   href="{{ route('admin.agentRecivedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }}
                                    <span class="Red">{{$agent_received_reqs_count}}</span></a>
                            @endif

                            {{--RECIVED REQUEST--}}



                            {{--FOLLOW REQUEST--}}

                            @if (auth()->user()->role == '0')
                                <a class="dropdown-item"
                                   href="{{ route('agent.followedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Requests') }}
                                    <span class="Cloud">{{$follow_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '11')
                                <a class="dropdown-item"
                                   href="{{ route('training.followedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Requests') }}
                                    <span class="Cloud">{{$follow_reqs_count}}</span></a>

                            @elseif (auth()->user()->role == '5')

                                <a class="dropdown-item"
                                   href="{{ route('quality.manager.followRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Requests') }}
                                    <span class="Cloud">{{$follow_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '7')
                                <a class="dropdown-item"
                                   href="{{ route('admin.followedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Requests') }}
                                    <span class="Cloud">{{$follow_reqs_count}}</span></a>
                            @endif

                            {{--FOLLOW REQUEST--}}



                            {{--STAR REQUEST--}}

                            @if (auth()->user()->role == '0')
                                <a class="dropdown-item"
                                   href="{{ route('agent.staredRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Stared Requests') }}
                                    <span class="Gold">{{$star_reqs_count}} </span></a>
                            @elseif (auth()->user()->role == '11')
                                <a class="dropdown-item" href="{{ route('training.staredRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Stared Requests') }}
                                    <span class="Gold">{{$star_reqs_count}} </span></a>
                            @elseif (auth()->user()->role == '7')
                                <a class="dropdown-item"
                                   href="{{ route('admin.staredRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Stared Requests') }}
                                    <span class="Gold">{{$star_reqs_count}} </span></a>
                            @endif

                            {{--STAR REQUEST--}}



                            {{--ARCH REQUEST--}}

                            @if (auth()->user()->role == '0')
                                <a class="dropdown-item"
                                   href="{{ route('agent.archRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
                                    <span class="Silver">{{$arch_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '1')
                                <a class="dropdown-item"
                                   href="{{ route('sales.manager.archRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Requests') }}
                                    <span class="Cloud">{{$arch_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '2')
                                <a class="dropdown-item"
                                   href="{{ route('funding.manager.archRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Requests') }}
                                    <span class="Cloud">{{$arch_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '3')
                                <a class="dropdown-item"
                                   href="{{ route('mortgage.manager.archRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
                                    <span class="Silver">{{$arch_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '4')
                                <a class="dropdown-item"
                                   href="{{ route('general.manager.archRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
                                    <span class="Silver">{{$arch_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '5' )
                                <a class="dropdown-item"
                                   href="{{ route('quality.manager.archRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
                                    <span class="Silver">{{$arch_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '11')
                                <a class="dropdown-item"
                                   href="{{ route('training.archRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
                                    <span class="Silver">{{$arch_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '7')
                                <a class="dropdown-item"
                                   href="{{ route('admin.archRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
                                    <span class="Silver">{{$arch_reqs_count}}</span></a>
                            @endif

                            {{--ARCH REQUEST--}}



                            {{--COMPLETE REQUEST--}}

                            @if (auth()->user()->role == '0')
                                <a class="dropdown-item"
                                   href="{{ route('agent.completedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Completed Requests') }}
                                    <span class="Green">{{$com_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '1')
                                <a class="dropdown-item"
                                   href="{{ route('sales.manager.completedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Completed Requests') }}
                                    <span class="Green">{{$com_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '2')
                                <a class="dropdown-item"
                                   href="{{ route('funding.manager.completedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Completed Requests') }}
                                    <span class="Green">{{$com_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '3')
                                <a class="dropdown-item"
                                   href="{{ route('mortgage.manager.completedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Completed Requests') }}
                                    <span class="Green">{{$com_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '4')
                                <a class="dropdown-item"
                                   href="{{ route('general.manager.completedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Completed Requests') }}
                                    <span class="Green">{{$com_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '5')
                                <a class="dropdown-item"
                                   href="{{ route('quality.manager.completedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Completed Requests') }}
                                    <span class="Green">{{$com_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '11')
                                <a class="dropdown-item"
                                   href="{{ route('training.completedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Completed Requests') }}
                                    <span class="Green">{{$com_reqs_count}}</span></a>
                            @endif
                            @if (auth()->user()->role == '0')
                                <a class="dropdown-item"
                                   href="{{ route('agent.PrepaymentReq') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'pur-pre') }}
                                    <span class="DarkRed">{{$prepay_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '1')
                                <a class="dropdown-item"
                                   href="{{ route('sales.manager.PrepaymentReq') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'pur-pre') }}
                                    <span class="DarkRed">{{$prepay_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '2')
                                <a class="dropdown-item"
                                   href="{{ route('funding.manager.PrepaymentReq') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'pur-pre') }}
                                    <span class="DarkRed">{{$prepay_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '3')
                                <a class="dropdown-item"
                                   href="{{ route('mortgage.manager.PrepaymentReq') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'pur-pre') }}
                                    <span class="DarkRed">{{$prepay_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '4')
                                <a class="dropdown-item"
                                   href="{{ route('general.manager.PrepaymentReq') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'pur-pre') }}
                                    <span class="DarkRed">{{$prepay_reqs_count}}</span></a>
                            @endif
                            @if (auth()->user()->role == '0')
                                <a class="dropdown-item"
                                   href="{{ route('agent.morPurRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests') }}
                                    <span class="Pink">{{$mor_pur_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '1')
                                <a class="dropdown-item"
                                   href="{{ route('sales.manager.morPurRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests') }}
                                    <span class="Pink">{{$mor_pur_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '2')
                                <a class="dropdown-item"
                                   href="{{ route('funding.manager.morPurRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests') }}
                                    <span class="Pink">{{$mor_pur_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '3')
                                <a class="dropdown-item"
                                   href="{{ route('mortgage.manager.morPurRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests') }}
                                    <span class="Pink">{{$mor_pur_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '4')
                                <a class="dropdown-item"
                                   href="{{ route('general.manager.morPurRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests') }}
                                    <span class="Pink">{{$mor_pur_reqs_count}}</span></a>
                            @endif
                            @if (auth()->user()->role == '7')
                                <a class="dropdown-item"
                                   href="{{ route('admin.PendingRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'PendingRequests') }}
                                    <span class="Pink">{{$pending_request_count}}</span></a>
                            @endif
                            @if (auth()->user()->role == '0')
                            <!-- الطلبات الاضافيه - المعلقه -->
                                <a class="dropdown-item"
                                   href="{{ route('agent.additionalRequests')  }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Additional Requests') }}
                                    <span class="Red2">{{$pending_request_count}}</span></a>

                                    <!-- الطلبات المُرسله من الادمن - طلبات الشخص الاخر -->
                                <!-- <a class="dropdown-item"
                                   href="{{ route('agent.additionalRequests')  }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Additional Requests') }}
                                    <span class="Red2">{{$pending_request_count}}</span></a> -->

                            @endif
                            @if (auth()->user()->role == '7')
                                <a class="dropdown-item" href="{{ route('admin.needActionRequestsNew') }}"> للتحويل -
                                    جديدة
                                    <span class="Red2">{{$need_action_request_count}}</span></a>
                                <a class="dropdown-item" href="{{ route('admin.needActionRequestsDone') }}"> للتحويل -
                                    تمت معالجتها </a>
                            @endif
                            @if (auth()->user()->role == '7')
                                <a class="dropdown-item" href="{{ route('admin.waitingReqsNew') }}"> الإنتظار -
                                    جديدة</a>
                                <a class="dropdown-item" href="{{ route('admin.waitingReqsDone') }}"> الإنتظار - تمت
                                    معالجتها </a>
                                <a class="dropdown-item" href="{{ route('V2.Admin.FreezeRequest.index') }}">
                                    {{trans_choice('choice.FreezeRequests',2)}}
                                    <span class="DarkBlue">{{$freesCount}}</span>
                                </a>
                            @endif
                            @if (auth()->user()->role == '4')
                                <a class="dropdown-item"
                                   href="{{ route('general.manager.cancelRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Canceled Requests') }}
                                    <span class="Silver">{{$cancel_reqs_count}}</span></a>
                            @endif
                            @if (auth()->user()->role == '1')
                                <a class="dropdown-item"
                                   href="{{ route('sales.manager.purReqs') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'requests') }}
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Purchase') }}
                                    <span class="Pink">{{$pur_reqs_count}}</span></a>
                                <a class="dropdown-item"
                                   href="{{ route('sales.manager.morReqs') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'requests') }}
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}
                                    <span class="Pink">{{$mor_reqs_count}}</span></a>
                            @endif
                            @if (auth()->user()->role == '2')
                                <a class="dropdown-item"
                                   href="{{ route('funding.manager.UnderProcessPage') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Financial Reports') }}
                                    <span class="DarkBlue"> {{$under_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '3')
                                <a class="dropdown-item"
                                   href="{{ route('mortgage.manager.UnderProcessPage') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Reports') }}
                                    <span class="DarkBlue"> {{$under_reqs_count}}</span></a>
                            @endif
                            @if (auth()->user()->role == '1')
                                <a class="dropdown-item"
                                   href="{{ route('sales.manager.rejRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Rejected Requests') }}
                                    <span class="Red2"> {{$rej_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '2')
                                <a class="dropdown-item"
                                   href="{{ route('funding.manager.rejRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Rejected Requests') }}
                                    <span class="Red2"> {{$rej_reqs_count}}</span></a>
                            @elseif (auth()->user()->role == '3')
                                <a class="dropdown-item"
                                   href="{{ route('mortgage.manager.rejRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Rejected Requests') }}
                                    <span class="Red2"> {{$rej_reqs_count}}</span></a>
                            @endif


                            {{--Need To be Turned Requests--}}
                            @if (auth()->user()->role == '5')
                            <a class="dropdown-item"
                                   href="{{ route('quality.manager.needToBeTurnedRequests') }}"> {{ MyHelpers::admin_trans(auth()->user()->id,'Need To be Turned Requests') }}
                            <span class="Gold">{{$need_turned_requests}}</span></a>
                            @elseif (auth()->user()->role == '7')
                            <a class="dropdown-item" style="font-size:x-small"
                                   href="{{ route('admin.needToBeTurnedReqNew') }}"> {{ MyHelpers::admin_trans(auth()->user()->id,'Need To be Turned Requests') }} - جودة (جديد)
                            <span class="Pink">{{$need_turned_requests}}</span></a>
                            <a class="dropdown-item" style="font-size:x-small"
                                   href="{{ route('admin.needToBeTurnedReqDone') }}"> {{ MyHelpers::admin_trans(auth()->user()->id,'Need To be Turned Requests') }} - (تم معالجتها)
                            <span class="Pink">{{$need_turned_done_requests}}</span></a>
                            @endif
                            {{--Need To be Turned Requests--}}

                        </div>
                    </div>
                </li>
            @endif
            @if (auth()->user()->role == '1')
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-users" style="color:sandybrown;font-size:large;"></i> </span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agents') }} </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('sales.manager.agentManager') }}">
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agents') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('sales.manager.dailyReq') }}">كل الطلبات
                                <span class="Pink"> {{$daily_reqs_count}}</span></a>
                            <a class="dropdown-item"
                               href="{{ route('sales.manager.agentRecivedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }}
                                <span class="Red"> {{$agent_received_reqs_count}}</span></a>
                            <a class="dropdown-item"
                               href="{{ route('sales.manager.staredRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Stared Requests') }}
                                <span class="Gold"> {{$star_reqs_count}}</span></a>
                            <a class="dropdown-item"
                               href="{{ route('sales.manager.followedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Requests') }}
                                <span class="Cloud"> {{$follow_reqs_count}}</span></a>
                            <a class="dropdown-item"
                               href="{{ route('sales.manager.agentCompletedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Completed Requests') }}
                                <span class="Green"> {{$agent_com_reqs_count}}</span></a>
                            <a class="dropdown-item"
                               href="{{ route('sales.manager.agentArchRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
                                <span class="Silver"> {{$agent_arch_reqs_count}}</span></a>
                        </div>
                    </div>
                </li>
            @endif
            @if (auth()->user()->role == '9')
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-users" style="color:sandybrown;font-size:large;"></i> </span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Quality Users') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('quality.manager.qualityUsers') }}">
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Quality Users') }}
                                <span class="Pink"> {{\App\User::where("role",5)->where("subdomain","<>",null)->count()}}</span>
                            </a>
                            <a class="dropdown-item"
                               href="{{ route('quality.manager.myRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
                                <span class="DarkBlue">{{$all_reqs_count}}</span></a>
                            <a class="dropdown-item"
                               href="{{ route('quality.manager.recivedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }}
                                <span class="Red">{{$received_reqs_count}}</span></a>
                            <a class="dropdown-item"
                               href="{{ route('quality.manager.followRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Requests') }}
                                <span class="Cloud">{{$follow_reqs_count}}</span></a>
                            <a class="dropdown-item"
                               href="{{ route('quality.manager.archRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
                                <span class="Silver">{{$arch_reqs_count}}</span></a>
                            <a class="dropdown-item"
                               href="{{ route('quality.manager.completedRequests') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Completed Requests') }}
                                <span class="Green">{{$com_reqs_count}}</span></a>
                        </div>
                    </div>
                </li>
            @endif
            @if (auth()->user()->role == '0' || auth()->user()->role == '7' || auth()->user()->role == '5'|| auth()->user()->role == '9'|| auth()->user()->role == '6')
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-users" style="color:sandybrown;font-size:large;"></i> </span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Customers') }} </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @if (auth()->user()->role == '0')
                                <a class="dropdown-item"
                                   href="{{ route('agent.addCustomerWithReq') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}
                                </a>
                            @endif
                            @if (auth()->user()->role == '7')
                                <a class="dropdown-item"
                                   href="{{ route('admin.addCustomerWithReq') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}
                                </a>
                                <a class="dropdown-item"
                                   href="{{ route('admin.allCustomers') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'customers_list') }}
                                </a>
                            @endif
                            @if (auth()->user()->role == '5' || auth()->user()->role == '9')
                                <a class="dropdown-item"
                                   href="{{ route('quality.manager.allCustomers') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Search Customer') }}
                                </a>
                            @endif
                            @if (auth()->user()->role == '6')
                                <a class="dropdown-item"
                                   href="{{ route('proper.customer.create') }}">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </li>
            @endif

                @if (auth()->user()->role == '7')
                    <li class="dropdown">
                        <div>
                            <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span><i class="fas fa-user" style="color:slategrey;font-size:large;"></i> </span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'manage') }}
                                {{ MyHelpers::admin_trans(auth()->user()->id,'users') }} </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('admin.users') }}">
                                    المستخدمين
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.colloberatorusers') }}">
                                    المتعاونين
                                </a>
                                {{--  <a class="dropdown-item" href="{{ route('admin.archUsers') }}">
                                      المؤرشفين
                                  </a>--}}
                            </div>
                        </div>
                    </li>
                @endif

         {{--   @if (auth()->user()->role == '7')
                <li class="">
                    <a class="" href="{{ route('admin.users') }}">
                        <span><i class="fas fa-user" style="color:slategrey;font-size:large;"></i> </span>
                        المستخدمين
                    </a>
                </li>
            @endif--}}

            @if (auth()->user()->role == '7')
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span><i class="fas fa-home" style="color:slategrey;font-size:large;"></i> </span>
                            العقارات </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('admin.customer_real_estate') }}">
                                طلبات العملاء
                            </a>
                            <a class="dropdown-item" href="{{ route('admin.collaborator_real_estate') }}">
                                المضافة-المتعاون
                            </a>
                            <a class="dropdown-item" href="{{ route('admin.fire-events') }}">
                                العقارات - ادوات القياس
                             </a>
                            <a class="dropdown-item" href="{{ route('real_types.index') }}">
                                  انواع العقارات
                            </a>
                        </div>
                    </div>
                </li>


                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span><i class="fas fa-solid fa-briefcase" style="color:blue;font-size:large;"></i> </span>
                            طلبات التوظيف </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('job_titles.index') }}">
                            اداره المسمى الوظيفى
                            </a>
                            <a class="dropdown-item" href="{{ route('nationality.index') }}">
                            اداره الجنسيات
                            </a>
                            <a class="dropdown-item" href="{{ route('university.index') }}">
                                  اداره الجامعات
                            </a>
                            <a class="dropdown-item" href="{{ route('job_applications.index') }}">
                                  طلبات التوظيف
                            </a>
                            <a class="dropdown-item" href="{{ route('job_applications_types.index') }}">
                                   قائمه التصنيفات
                            </a>
                        </div>
                    </div>
                </li>
            @endif

            @if (auth()->user()->role == '7')
                <li class="actives">
                    <a href="{{ route('HumanResource.users.index') }}"> <span> <i class="fas fa-users"
                                                                                  style="color:orangered;font-size:large;"></i>
                        </span>ملفات الموظفين </a>
                </li>
            @endif
            @if (auth()->user()->role == '12' )
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span><i class="fas fa-users"
                                     style="color:orangered;font-size:large;"></i></span>
                            ملفات الموظفين </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('HumanResource.users.index') }}">
                                ملفات الموظفين
                            </a>
                            <a class="dropdown-item" href="{{ route('HumanResource.addUserPage') }}">
                                إضافة  مستخدم
                            </a>
                        </div>
                    </div>
                </li>

                <li class="dropdown">
                    <div>
                        <a href="{{ route('HumanResource.job_applications.index') }}">
                            <span><i class="fas fa-solid fa-briefcase" style="color:blue;font-size:large;"></i> </span>
                            طلبات التوظيف
                        </a>
                    </div>
                </li>

                <li class="dropdown">
                    <div>
                        <a href="{{ route('HumanResource.hr_dailyPrefromence') }}">
                            <span> <i class="fas fa-chart-bar" style="color:  #4d9900;font-size:large;"></i></span>
                            تقرير الاداء اليومى - الاستشارى
                        </a>
                    </div>
                </li>

            @endif

            @if (auth()->user()->role == '7' || auth()->user()->role == '4')
                <li>
                    <a href="{{ route('filterEngine') }}"> <span> <i class="fas fa-search"
                                                                     style="color:blueviolet;font-size:large;"></i> </span>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Filter Engine') }} </a>
                </li>
            @endif
            @if (auth()->user()->role == '3')
                <li>
                    <a href="{{ route('mortgage.manager.allMortgageReqs') }}"> <span> <i class="fas fa-search"
                                                                                         style="color:blueviolet;font-size:large;"></i> </span>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'all_mortgage_applications') }} </a>
                </li>
            @endif
            @if (auth()->user()->role == '7')
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-bell" style="color:orangered;font-size:large;"></i> </span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Notifications') }} </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('all.notification') }}">
                                جديدة
                            </a>
                            <a class="dropdown-item" href="{{ route('all.notification_Done') }}">
                                تمت المعالجة
                            </a>
                        </div>
                    </div>
                </li>
            @else
                @if (auth()->user()->role != '12' && auth()->user()->role != '9' )
                    <li>
                        <a href="{{ route('all.notification') }}"> <span> <i class="fas fa-bell"
                                                                             style="color:orangered;font-size:large;"></i>
                    </span> {{ MyHelpers::admin_trans(auth()->user()->id,'Notifications') }} </a>
                    </li>
                @endif
            @endif


            @if (auth()->user()->role != '12'  && auth()->user()->role != '9')
                    @if (auth()->user()->role != '13')
                @if(auth()->user()->role != 6)
                    <li>
                        <a href="{{ route('chat') }}"> <span> <i class="fas fa-envelope"
                                                                 style="color:mediumblue;font-size:large;"></i>
                    </span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Messages') }} </a>
                    </li>
                @endif
                @endif
                <li>
                    <a href="{{ route('reminders') }}"> <span> <i class="far fa-calendar-minus"
                                                                  style="color: brown;font-size:large;"></i> </span>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Reminders') }} </a>
                </li>
                @if (auth()->user()->role != '6')
                    <li class="dropdown">
                        <div>
                            <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span><i class="fas fa-comment-alt" style="color: #1f3d7a;font-size:large;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'tasks') }} </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if (auth()->user()->role == '5')
                                    <a class="dropdown-item"
                                       href="{{ route('quality.manager.sentTask') }}"> {{ MyHelpers::admin_trans(auth()->user()->id,'tasks') }} {{ MyHelpers::admin_trans(auth()->user()->id,'sent') }}
                                        <span class="Pink"> {{$sent_task_count}}</span></a>
                                    <a class="dropdown-item"
                                       href="{{ route('quality.manager.mytask') }}"> {{ MyHelpers::admin_trans(auth()->user()->id,'tasks') }} {{ MyHelpers::admin_trans(auth()->user()->id,'recived') }}
                                        <span class="Pink">{{$received_task_count}}</span></a>
                                    <a class="dropdown-item"
                                       href="{{ route('quality.manager.completetask') }}"> {{ MyHelpers::admin_trans(auth()->user()->id,'completed Task') }}
                                        <span class="Pink"> {{$completed_task_count}}</span></a>
                                @else
                                    <a class="dropdown-item"
                                       href="{{ route('all.sentTask') }}"> {{ MyHelpers::admin_trans(auth()->user()->id,'tasks') }} {{ MyHelpers::admin_trans(auth()->user()->id,'sent') }}
                                        <span class="Pink"> {{$sent_task_count}}</span></a>
                                    <a class="dropdown-item"
                                       href="{{ route('all.recivedtask') }}"> {{ MyHelpers::admin_trans(auth()->user()->id,'tasks') }} {{ MyHelpers::admin_trans(auth()->user()->id,'recived') }}
                                        <span class="Pink"> {{$received_task_count}}</span></a>
                                    <a class="dropdown-item"
                                       href="{{ route('all.completedtask') }}"> {{ MyHelpers::admin_trans(auth()->user()->id,'completed Task') }}
                                        <span class="Pink"> {{$completed_task_count}}</span></a>
                                @endif
                            </div>
                        </div>
                    </li>
                @endif
            @endif
            @if (auth()->user()->role == '7' )
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span><i class="fas fa-question-circle" style="color: #55552b;font-size:large;"></i></span>
                            التقييمات </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('admin.asks.index') }}">أسئلة التقييم
                            </a>

                            <a class="dropdown-item" href="{{ route('admin.asks.answers') }}"> الطلبات الملغاه
                            </a>
                        </div>
                    </div>
                </li>
            @endif
            @if (auth()->user()->role == '7' || auth()->user()->role == '4' || auth()->user()->role == '8')
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-chart-line" style="color: #4d0026;font-size:large;"></i> </span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Reports') }} </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                            @if (auth()->user()->role == '7' || auth()->user()->role == '4' || (auth()->user()->role
                            == '8' && auth()->user()->accountant_type == 1))
                                <a class="dropdown-item" href="{{ route('report.wsataAccountingReport') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'to wsata') }} - مفرغة
                                </a>
                                <a class="dropdown-item" href="{{ route('report.wsataAccountingUnderReport') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'to wsata') }} - تحت المعالجة
                                </a>
                            @endif
                            @if (auth()->user()->role == '7' || auth()->user()->role == '4' || (auth()->user()->role
                            == '8' && auth()->user()->accountant_type == 0))
                                <a class="dropdown-item" href="{{ route('report.tsaheelAccountingReport') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'to tsaheel') }} - مفرغة
                                </a>
                                <a class="dropdown-item" href="{{ route('report.tsaheelAccountingUnderReport') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'to tsaheel') }} - تحت المعالجة
                                </a>
                            @endif

                            @if (auth()->user()->role == '7')

                                <a class="dropdown-item" href="{{ route('measurement_tools') }}">
                                    ادوات القياس - التصنيفات
                                </a>
                            @endif
                        </div>
                    </div>
                </li>
            @endif
            @if (auth()->user()->role == '1' ||auth()->user()->role == '9' || auth()->user()->role == '7' || auth()->user()->role == '4'
            ||auth()->user()->role == '0' ||auth()->user()->role == '11')
                {{--||auth()->user()->role == '5'--}}
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-chart-bar" style="color:  #4d9900;font-size:large;"></i></span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }} </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @if (auth()->user()->role == 9)
                                <a class="dropdown-item" href="{{ route('quality.manager.dailyPrefromenceChartQuality') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }} - الجودة
                                </a>
                            @endif
                            @if (auth()->user()->role == '7' || auth()->user()->role == '4')

                                <a class="dropdown-item" href="{{ route('charts.sources') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'request_sources') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('charts.sources.wsata') }}">
                                    @lang('language.request_sources') -
                                    وساطة
                                </a>
                                <a class="dropdown-item" href="{{ route('charts.sources.requests') }}">
                                    @lang('language.request_sources') -
                                    معلقة
                                </a>
                                <a class="dropdown-item" href="{{ route('dailyPrefromenceChartR') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('dailyPrefromenceChartR') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.collaborator.requests.repeated') }}">
                                    تقرير تكرار الطلبات ( متعاون )
                                </a>
                                <!-- الموقع الإلكتروني -->
                                <a class="dropdown-item" href="{{ route('websiteChartR') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Website') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('otaredUpdateChartR') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Otared Update') }}
                                </a>
                                {{-- <a class="dropdown-item" href="{{ route('requestChartR') }}">
                                     {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                                 </a>--}}

                                <a class="dropdown-item" href="{{ route('charts.requests.status') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                                </a>

                                <a class="dropdown-item" href="{{ route('movedRequestChartR') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'assigned_assignment') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('movedRequestWtihPostiveClass') }}">
                                    طلبات (مرفوع ، مكتمل)
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.guests.index') }}">
                                    موقع الحاسبة
                                </a>
                                <a class="dropdown-item" href="{{ route('qualityTaskChartR') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Quality Task') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('qualityServayChartR') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Quality Servay Results') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('updateRequestChartR') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'req time line') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('finalResultChartR') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'final result') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('V2.Admin.report1') }}">
                                    @lang('reports.report1')
                                </a>
                                <a class="dropdown-item" href="{{ route('V2.Admin.report2') }}">
                                    @lang('reports.report2')
                                </a>
                                <a class="dropdown-item" href="{{ route('V2.Admin.report3') }}">
                                    @lang('reports.report3')
                                </a>
                                <a class="dropdown-item" href="{{ route('V2.Admin.report4') }}">
                                    @lang('reports.report4')
                                </a>
                            @elseif (auth()->user()->role == '1')
                                {{--<a class="dropdown-item"
                                   href="{{ route('sales.manager.requestChartRForSalesManager') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                                </a>--}}

                                <a class="dropdown-item" href="{{ route('sales.manager.charts.sales.requests.status') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                                </a>
                                <a class="dropdown-item"
                                   href="{{ route('sales.manager.dailyPrefromenceChartRForSalesManager') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }}
                                </a>


                            @elseif (auth()->user()->role == '0')
                                {{-- <a class="dropdown-item" href="{{ route('agent.requestChartRForAgent') }}">
                                     {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                                 </a>--}}
                                <a class="dropdown-item" href="{{ route('agent.charts.requests.status') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                                </a>
                            @elseif (auth()->user()->role == '5')
                                {{--    <a class="dropdown-item"
                                       href="{{ route('quality.manager.dailyPrefromenceChartQuality') }}">
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }}
                                    </a>--}}
                            @elseif (auth()->user()->role == '11')
                                <a class="dropdown-item" href="{{ route('training.charts.requests.status') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                                </a>

                                {{--<a class="dropdown-item" href="{{ route('training.requestChartRForTraining') }}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
                                </a>--}}
                            @endif
                        </div>
                    </div>
                </li>
            @endif

            @if (auth()->user()->role == '7')
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-cog" style="color:darkgrey;font-size:large;"></i> </span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Website Settings') }} </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ url('admin/settings/form/askforconsultant') }}">
                                طلب استشارة
                            </a>
                            <a class="dropdown-item" href="{{ url('admin/settings/form/askforfunding') }}">
                                طلب تمويل
                            </a>
                            <a class="dropdown-item" href="{{ url('admin/settings/form/realEstateCalculator') }}">
                                الحاسبة العقارية
                            </a>
                            <a class="dropdown-item" href="{{ route ('admin.requestConditionSettings') }}">
                                شروط الطلبات
                            </a>
                        </div>
                    </div>
                </li>

                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-cog" style="color:darkgrey;font-size:large;"></i> </span>
                            التصنيفات
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('V2.Admin.ClassificationAlertSetting.index') }}">
                                اعداد التنبيهات
                            </a>
                            <a class="dropdown-item" href="{{ route('V2.Admin.Statistics.classifications') }}">
                                @lang('global.classificationsStatistics')
                            </a>
                        </div>
                    </div>
                </li>
            @endif

            @if (auth()->user()->role == '7')
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-cog" style="color:darkgrey;font-size:large;"></i> </span>إعدادات
                            الموارد البشرية </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route ('admin.controls.index','company') }}">
                                التحكم بالشركات
                            </a>
                            <a class="dropdown-item" href="{{ route ('admin.controls.index','section') }}">
                                التحكم بالأقسام
                            </a>
                            <a class="dropdown-item" href="{{ route ('admin.controls.index','subsection') }}">
                                التحكم بالأقسام الفرعية
                            </a>
                            <a class="dropdown-item" href="{{ route ('admin.controls.index','nationality') }}">
                                التحكم بالجنسيات
                            </a>
                            <a class="dropdown-item" href="{{ route ('admin.controls.index','guaranty') }}">
                                التحكم بالكفالة
                            </a>
                            <a class="dropdown-item" href="{{ route ('admin.controls.index','insurances') }}">
                                التحكم بالتأمينات
                            </a>
                            <a class="dropdown-item" href="{{ route ('admin.controls.index','medical') }}">
                                التحكم بالتأمين الطبى
                            </a>
                            <a class="dropdown-item" href="{{ route ('admin.controls.index','work') }}">
                                التحكم بطريقة العمل
                            </a>
                            <a class="dropdown-item" href="{{ route ('admin.controls.index','identity') }}">
                                التحكم بأنواع الهوية
                            </a>
                            <a class="dropdown-item" href="{{ route ('admin.controls.index','custody') }}">
                                التحكم بالعهدة
                            </a>
                            <hr>
                            <a class="dropdown-item" href="{{ route ('admin.vacancies.index') }}">
                                التحكم بأنواع الأجازات
                            </a>
                            <a class="dropdown-item" href="{{ route ('admin.vacancies.count') }}">
                                التحكم برصيد الأجازات
                            </a>
                        </div>
                    </div>
                </li>
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-user-cog" style="color:coral;font-size:large;"></i> </span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Customer Account Settings') }} </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ url('admin/settings/form/customerReq') }}">
                                محتوى طلب العميل
                            </a>
                            <a class="dropdown-item" href="{{ route('admin.helpDeskPage') }}">
                                الدعم الفني
                            </a>
                            <a class="dropdown-item" href="{{ route('admin.rates') }}">
                                تقييمات الخدمة</a>
                        </div>
                    </div>
                </li>
            @endif

            @if (auth()->user()->role == '7')
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-certificate"
                                      style="color:palevioletred;font-size:large;"></i></span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Quality Settings') }} </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ url('admin/settings/questions') }}">
                                أسئلة التقييم
                            </a>
                            <a class="dropdown-item" href="{{ url('admin/settings/stutusRequest') }}">
                                شروط الطلب
                            </a>
                            <a class="dropdown-item" href="{{ route('V2.Admin.report5') }}">
                                @lang('reports.report5')
                            </a>
                        </div>
                    </div>
                </li>
            @endif
            @if (auth()->user()->role == '7')
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-cogs" style="color:slateblue;font-size:large;"></i> </span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Request Settings') }}</a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('admin.ips') }}">
                                IpAddress
                            </a>
                            <a class="dropdown-item" href="{{ url('admin/settings/classifications') }}">
                                تصنيفات الطلب
                            </a>
                            <a class="dropdown-item" href="{{ url('admin/settings/city') }}">
                                المدن
                            </a>
                            <a class="dropdown-item" href="{{ url('admin/settings/realtype') }}">
                                أنواع العقار
                            </a>
                            <a class="dropdown-item" href="{{ url('admin/settings/agentAskRequest') }}">
                                نقل الطلب
                            </a>
                            <a class="dropdown-item" href="{{ url('admin/settings/importExcelPage') }}">
                                استيراد الطلبات
                            </a>
                            <a class="dropdown-item" href="{{ url('admin/settings/importExcelForTwoCloumnsPage') }}">
                                استيراد الطلبات - عمودين
                            </a>
                            <a class="dropdown-item" href="{{ url('admin/settings/requestWithoutUpdate') }}">
                                طلبات بدون تحديث
                            </a>
                            <a class="dropdown-item" href="{{ url('admin/rejections') }}">
                                أسباب الرفض
                            </a>
                        </div>
                    </div>
                </li>
            @endif

            {{--            @if(auth()->user()->role == '10')--}}
            @if(auth()->user()->role == 6)
                @if(auth()->user()->allow_recived == 1 )
                    <li class="dropdown">
                        <div>
                            <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span> <i class="fas fa-home" style="color:slateblue;font-size:large;"></i> </span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'realEstate') }}</a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                <a class="dropdown-item" href="{{route('property.list')}}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'all_real_estate') }} </a>

                                {{--                                <a class="dropdown-item" href="{{route('propertiesRequests.list')}}">--}}
                                {{--                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Properties Request') }} </a>--}}
                            </div>
                        </div>
                    </li>
                @else
                    <li class="dropdown">
                        <div>
                            <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span> <i class="fas fa-home" style="color:slateblue;font-size:large;"></i> </span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'realEstate') }}</a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{route('property.list')}}">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate') }} </a>
                            </div>
                        </div>
                    </li>

                @endif
            @endif

            @if (auth()->user()->role == '7')
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-city" style="color:steelblue;"></i></span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Property Settings') }}</a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{route('admin.showToGuestCustomer')}}">
                                إظهارها للعميل <br> الغير مسجل
                            </a>
                        </div>
                    </div>
                </li>
            @endif

            @if (auth()->user()->role == '7')
                <li class="dropdown">
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-wrench" style="color: black;"></i></span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'System Settings') }}</a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{route('admin.emails')}}">
                                البريد الإلكتروني
                            </a>
                            <a class="dropdown-item" href="{{route('admin.announcements')}}">
                                التعميمات
                            </a>
                            <a class="dropdown-item" href="{{route('admin.trainingPremtions')}}">
                                صلاحيات الأكاديمي
                            </a>
                            <a class="dropdown-item" href="{{route('admin.settings.days_of_resubmit')}}">
                                الأيام المتاحة لإستلام <br>اشعارات تسجيل عميل مكرر
                            </a>
                            <a class="dropdown-item" href="{{route('admin.waiting_requests_settings')}}">
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Waiting Requests') }}
                            </a>
                        </div>
                    </div>
                </li>
            @endif

            @if (auth()->user()->role == '7')
                <li>
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-calculator" style="color:orangered;"></i></span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Calculator Settings') }}</a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{route('admin.banks')}}" style="font-size:small;">
                                جهات التمويل
                            </a>
                            <a class="dropdown-item" href="{{route('admin.jobPositionIndex')}}"
                               style="font-size:small;">
                                جهات العمل
                            </a>
                            <a class="dropdown-item" href="{{route('admin.extraFundingYearIndex')}}"
                               style="font-size:small;">
                                تمديد السنوات
                            </a>
                            <a class="dropdown-item" href="{{route('admin.profitPercentageIndex')}}"
                               style="font-size:small;">
                                نسب جهات التمويل
                            </a>
                            <a class="dropdown-item" href="{{route('admin.supportInstallment')}}"
                               style="font-size:small;">
                                آلية اضافة قسط الدعم
                            </a>
                            <a class="dropdown-item" href="{{route('admin.availableExtended')}}"
                               style="font-size:small;">
                                اتاحة احتساب برنامج ممتد
                            </a>
                            <a class="dropdown-item" href="{{route('admin.SalaryDeduction')}}" style="font-size:small;">
                                نسب الاستقطاع
                            </a>
                            <a class="dropdown-item" href="{{route('admin.SalaryEquation')}}" style="font-size:small;">
                                حساب صافي الراتب
                            </a>
                            <a class="dropdown-item" href="{{route('admin.firstBatchIndex')}}" style="font-size:small;">
                                نوع المنتج / نسب الدفعة الأولى
                            </a>

                            <a class="dropdown-item" href="{{route('admin.productTypeIndex')}}"
                               style="font-size:small;">
                                نوع المنتج / أنواع المنتج
                            </a>

                            <a class="dropdown-item" href="{{route('admin.productTypeCheckTotalIndex')}}"
                               style="font-size:small;">
                                إجمالي شيك نوع المنتج
                            </a>
                            <a class="dropdown-item" href="{{route('admin.calculatorRuleIndex')}}"
                               style="font-size:small;">
                                إشتراطات الحاسبة
                            </a>
                            <a class="dropdown-item" href="{{route('admin.propertyStatusRuleIndex')}}"
                               style="font-size:small;">
                                تخصيص حالات العقار
                            </a>
                            <a class="dropdown-item" href="{{route('admin.rulesWithoutTransferIndex')}}"
                               style="font-size:small;">
                                شروط بدون تحويل الراتب
                            </a>

                            <a class="dropdown-item" href="{{route('admin.getCalculatorSettings')}}"
                               style="font-size:small;">
                                إعدادات الحسبة
                            </a>
                            <a class="dropdown-item" href="{{route('ResultProgramsCustomize')}}"
                               style="font-size:small;">
                                تخصيص نتائج البرامج
                            </a>
                            <a class="dropdown-item" href="{{ route('admin.formula.results.page') }}"
                               style="font-size:small;">
                                صلاحيات نتائج البرامج
                            </a>
                            <a class="dropdown-item" href="{{route('admin.scenarios.index')}}" style="font-size:small;">
                                إعدادت النتائج
                            </a>
                            <a class="dropdown-item" href="{{ url('admin/settings/difference') }}"
                               style="font-size:small;">
                                تنبيهات فرق النتائج
                            </a>

                        </div>
                    </div>
                </li>
            @endif

            @if (auth()->user()->role == '7')
                <li>
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-cog" style="color:crimson;"></i></span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Calculator Suggestion Settings') }}  @if ($calculator_suggests != 0)
                                <span class="Red3"
                                      style="  color:white;width: 30px;height: 30px;background: #F6F6F6;text-align: center;border-radius: 50px;display: inline-block;line-height: 30px;margin-right: 8px;transition: .3s;"> {{$calculator_suggests}}</span>
                            @endif</a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{route('admin.formula.page')}}">
                                صلاحيات التعديل
                            </a>
                            <a class="dropdown-item" href="{{route('admin.suggestions.index')}}">
                                مقترحات المستخدمين
                            </a>
                        </div>
                    </div>
                </li>
            @endif
            @if(\App\EditCalculationFormulaUser::where(['user_id' => auth()->user()->id,'type' => 1])->count() > 0)
                <li>
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-calculator" style="color:orangered;"></i></span>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Calculator Settings') }}</a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{route('ResultProgramsCustomize')}}">
                                تخصيص نتائج البرامج
                            </a>
                        </div>
                    </div>
                </li>
            @endif

            @if (auth()->user()->role == '2' || auth()->user()->role == '1' || auth()->user()->role == '7')
                <li>
                    <a href="{{ route('all.calculaterPage') }}"> <span> <i class="fas fa-calculator"
                                                                           style="color:darkcyan;font-size:large;"></i> </span>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Calculater') }} </a>
                </li>
            @endif
            @if (auth()->user()->role == '1'  && 0)
                <li>
                    <a href="{{ route('sales.manager.staff_index') }}"> <span> <i class="fas fa-users"
                                                                                  style="color:darkcyan;font-size:large;"></i> </span>
                        فريق العمل</a>
                </li>
            @endif

            @if(\App\EditCalculationFormulaUser::where(['user_id' => auth()->user()->id,'type' => 0])->count() > 0)
                <li>
                    <div>
                        <a href="#" class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> <i class="fas fa-calculator" style="color:#0b2e13;"></i></span>
                            اقتراحات الحاسبة @if ($calculator_suggests != 0)
                                <span class="Red3"
                                      style="  color:white;width: 32px;height: 32px;background: #F6F6F6;text-align: center;border-radius: 50px;display: inline-block;line-height: 30px;margin-right: 8px;transition: .3s;"> {{$calculator_suggests}}</span>
                            @endif
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{route('all.suggestions.extraFundingYearIndex')}}">
                                تمديد السنوات
                            </a>
                            <a class="dropdown-item" href="{{route('all.suggestions.profitPercentageIndex')}}">
                                نسب البنوك
                            </a>
                            <a class="dropdown-item" href="{{route('all.suggestions.index')}}">
                                مقترحات المستخدمين
                            </a>
                        </div>
                    </div>
                </li>
            @endif
            @if(auth()->check() && auth()->user()->role == 0)
                <li>
                    <a href="{{ route('V2.Agent.myChat') }}">
                        <span> <i class="fas fa-comment-alt" style="color:#333;font-size:large;"></i></span>
                        {!! __("global.app_chats") !!}
                    </a>
                </li>
            @endif
            {{--For Hr Prevent--}}
            {{-- @if (auth()->user()->role != '12' && auth()->user()->role != 13 )--}}
            <li>
                <a href="{{ route('all.announcements') }}">
                    <span> <i class="fas fa-history" style="color:#333;font-size:large;"></i></span>
                    سجل التعميمات </a>
            </li>
            {{--@endif--}}

            @if(auth()->user()->role == 7 )
                <li>
                    <a href="{{ route('admin.welcomeMessage') }}">
                        <span> <i class="fas fa-send" style="color:#333;font-size:large;"></i></span>
                        إعدادات الرسائل الترحيبيبة </a>
                </li>
            @endif

            @if (auth()->user() && in_array(auth()->user()->role,  [0,1,2,3,5,6,13]))
                <li>
                    <a href="javascript:void();"  data-toggle="modal" data-target="#exampleModal">
                        <span> <i class="fa fa-comments" style="color:#333;font-size:large;"></i></span>
                        الدعم الفني</a>
                </li>
            @endif


            @if (auth()->user()->role == '7')
            <li>
                    <a href="{{route('app_details.index')}}">
                        <span> <i class="fa fa-cog" style="color:crimson;font-size:large;"></i></span>
                        تفاصيل التطبيق</a>
                </li>
            @endif

            @if(auth()->user()->role == 0 )
                <li>
                    <a  href="{{ route('agent.rates') }}">
                    <span> <i class="fas fa-star" style="color:royalblue;font-size:large;"></i> </span>
                        تقييمات الخدمة</a>
                </li>
            @endif

        </ul>
    </div>
</div>
